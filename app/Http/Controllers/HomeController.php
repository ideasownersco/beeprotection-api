<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var Order
     */
    private $orderModel;

    /**
     * Create a new controller instance.
     *
     * @param Order $orderModel
     */
    public function __construct(Order $orderModel)
    {
//        $this->middleware('auth');
        $this->orderModel = $orderModel;
    }

    public function test()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    public function getInvoice($orderID)
    {
        $order = $this->orderModel->with(['packages','services'])->find($orderID);
        $services = $order->services->pluck('id');
        return view('partials.invoice_plain',compact('order','services'));
    }

    public function printInvoice(Request $request)
    {
        $invoiceID = $request->invoice_id;

    }
}
