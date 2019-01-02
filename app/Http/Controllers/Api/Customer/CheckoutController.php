<?php

namespace App\Http\Controllers\Api\Customer;

use App\Events\OrderCreated;
use App\Exceptions\DriversNotAvailableException;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersResource;
use App\Models\Address;
use App\Models\BlockedDate;
use App\Models\Driver;
use App\Models\Holiday;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Timing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Service
     */
    private $serviceModel;

    /**
     * @var BlockedDate
     */
    private $blockedDateModel;
    /**
     * @var Driver
     */
    private $driverModel;
    /**
     * @var Timing
     */
    private $timingModel;
    /**
     * @var Address
     */
    private $addressModel;
    /**
     * @var Holiday
     */
    private $holidayModel; //9pm

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param User $userModel
     * @param Package $packageModel
     * @param Service $serviceModel
     * @param BlockedDate $blockedDateModel
     * @param Driver $driverModel
     * @param Timing $timingModel
     * @param Address $addressModel
     * @param Holiday $holidayModel
     */
    public function __construct(Order $orderModel, User $userModel, Package $packageModel, Service $serviceModel,BlockedDate $blockedDateModel,Driver $driverModel,Timing $timingModel,Address $addressModel,Holiday $holidayModel)
    {
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
        $this->packageModel = $packageModel;
        $this->serviceModel = $serviceModel;
        $this->blockedDateModel = $blockedDateModel;
        $this->driverModel = $driverModel;
        $this->timingModel = $timingModel;
        $this->addressModel = $addressModel;
        $this->holidayModel = $holidayModel;
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     *
     * Date has to be 05-10-2017 d-m-Y or Y-m-d
     * time has to be 10:00 or 10pm
     */
    public function checkout(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date'       => 'required|date',
            'time' => 'required',
            'address_id' => 'required',
            'items'      => 'array',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $timeModel = $this->timingModel->find($request->time);

        $time = Carbon::parse($timeModel->name_en)->toTimeString();

        $date = Carbon::parse($request->date)->toDateString();

        $appointmentTime = Carbon::parse($date . ' ' . $time)->toDateTimeString();

        $holiday = $this->holidayModel->where('date',$date)->first();

        if($holiday) {
            return ['success' => false, 'message' => 'Sorry, We are closed on '.$date];
        }

        //@todo:max order time
        $user = Auth::guard('api')->user();

        $addressID = $request->address_id;

        $address = $this->addressModel->find($addressID);

        if(!$address) {
            return ['success' => false, 'message' => 'Unknown address'];
        }

        if(!$address->area || !$address->area->active) {

            $area = $address->area ? ucfirst($address->area) : 'Service not available at your requested address';

            return ['success' => false, 'message' => 'Service not available in ' . $area];
        }

        $paymentMode = $request->payment_mode === 'knet' ? 'knet' : 'cash';

        $order = $user->orders()->create([
            'address_id'   => $addressID,
            'date'         => $date,
            'time'         => $time,
            'appointment_time' => $appointmentTime,
            'payment_mode' => $paymentMode,
            'invoice'      => $this->generateInvoiceCode(),
            'status'       => 'checkout',
            'free_wash' => $request->free_wash ? 1 : 0,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_mobile' => $request->customer_mobile,
            'total' => 0,
        ]);

        if(!$request->free_wash) {

            foreach ($request->items as $item) {
                if (array_key_exists('package', $item)) {
                    $package = $this->packageModel->find($item['package']);
                    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                    if ($package) {
                        $order->packages()->attach($package->id, ['price' => $package->price * $quantity,'quantity'=>$quantity]);
                    }
                }

                if (array_key_exists('services', $item)) {
                    foreach ($item['services'] as $service) {
                        $service = $this->serviceModel->find($service);
                        if ($service) {
                            $order->services()->attach($service->id, ['price' => $service->price]);
                        }
                    }
                }
            }

            $order->load(['address', 'packages.category', 'services.package']);

            $orderPackages = $order->packages->sum('pivot.price');
            $orderServices = $order->services->sum('pivot.price');

            $order->update([
                'total' => $orderPackages + $orderServices,
            ]);

        }

        // if payment mode is knet
        if ($paymentMode === 'cash') {
            try {
                $order->create();
                $this->orderCreated($order);
            } catch (DriversNotAvailableException $e) {
                $message = 'All drivers are busy at '.Carbon::parse($time)->format('g:i a').', Please choose other time slot.';
                return response()->json(['success' => false, 'message' => $message]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true, 'data' => new OrdersResource($order)]);

    }

    /**
     * @param Order $order
     *
     */
    public function orderCreated(Order $order)
    {
        //        if (app()->environment('production')) {
//            event(new OrderCreated($order));
//        }
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

}
