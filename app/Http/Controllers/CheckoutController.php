<?php

namespace App\Http\Controllers;

use App\Events\ProjectCompleted;
use App\Exceptions\DriversNotAvailableException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use IZaL\Knet\KnetBilling;
use App\Events\OrderCreated;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private $orderModel;

    /**
     * CheckoutController constructor.
     * @param Order $orderModel
     */
    public function __construct( Order $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function makeKnetPayment($orderID)
    {
        $order = $this->orderModel->find($orderID);

        $invoiceID = $this->generateInvoiceCode();
        $order->invoice = $invoiceID;
        $order->save();
        $successURL = route('payment.knet.response.success');
        $errorURL = route('payment.knet.error');
        $knetAlias = env('KNET_ALIAS');
        try {
            $knetGateway = new KnetBilling([
                'alias'        => $knetAlias,
                'resourcePath' => base_path() . '/'
            ]);
            $knetGateway->setResponseURL($successURL);
            $knetGateway->setErrorURL($errorURL);
            $knetGateway->setAmt($order->total);
            $knetGateway->setTrackId($order->id);
            $knetGateway->requestPayment();
            $paymentURL = $knetGateway->getPaymentURL();
            $order->payment_id = $knetGateway->getPaymentID();
            $order->status = 'checkout';
            $order->save();
            return redirect()->away($paymentURL);
        } catch (\Exception $e) {
            $order->status = 'error';
            $order->save();
            return redirect()->route('home')->with('error', 'حدث خلل اثناء التحويل الي موقع الدفع');
        }

    }

    public function onKnetPaymentResponseSuccess(Request $request)
    {
        $paymentID = $request->paymentid;
        $result = $request->result;
        $transactionID = $request->tranid;
        $trackID = $request->trackid;
        $urlParams = "/?paymentID=" . $paymentID . "&transactionID=" . $transactionID . "&trackID=" . $trackID;
        $order = $this->orderModel->find($trackID);
        $order->transaction_id = $transactionID;
        $order->payment_id = $paymentID;
        if ($result == "CAPTURED") {
            $order->status = 'success';
            $order->save();
            $redirectURL = route('payment.knet.success');
        } else {
            $order->status = 'failed';
            $order->save();
            $redirectURL = route('payment.knet.error');
        }

        return "REDIRECT=" . $redirectURL . $urlParams;
    }

    public function onKnetPaymentSuccess(Request $request)
    {
        $order = $this->orderModel->with(['job'])->find($request->trackID);

        if(!$order->job) {
            try {
                $this->onPaymentSuccess($order);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('order.success', [$order->id, 'ref' => $order->transaction_id]);
    }

    public function onKnetPaymentError(Request $request)
    {
        $order = $this->orderModel->find($request->trackID);
        if($order) {
            $order->status = 'failed';
            $order->transaction_id = $request->transactionID;
            $order->save();
        }
        return view('payment.failure',compact('order'));
    }

    public function generateInvoiceCode()
    {
        $randNumber = mt_rand(100000000, 900000000);
        $hasUsed = $this->orderModel->where('invoice',$randNumber)->count() > 0;
        if($hasUsed) {
            return $this->generateInvoiceCode();
        }
        return $randNumber;
    }

    public function onPaymentSuccess(Order $order)
    {
        try {
            $order->create();
            $this->orderCreated($order);
        } catch (DriversNotAvailableException $e) {
            $message = 'All drivers are busy at , Please choose other time slots.';
            return response()->json(['success' => false, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function orderCreated(Order $order)
    {
        //        if (app()->environment('production')) {
//            event(new OrderCreated($order));
//        }
    }

    public function onOrderSuccess($id, Request $request)
    {
        $order = $this->orderModel->find($id);

        if (!$request->ref || $order->transaction_id != $request->ref) {
            return response()->json(['success'=>false,'message' => 'Invalid Order ID '.$id]);
        }

        return view('payment.success', ['order' => $order]);
    }


}
