<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimingResource;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimingsController extends Controller
{
    /**
     * @var Timing
     */
    private $timingModel;
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Service
     */
    private $serviceModel;
    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var Driver
     */
    private $driverModel;

    /**
     * @param Timing $timingModel
     * @param Package $packageModel
     * @param Service $serviceModel
     * @param Order $orderModel
     * @param Driver $driverModel
     */
    public function __construct(Timing $timingModel, Package $packageModel, Service $serviceModel, Order $orderModel,Driver $driverModel)
    {
        $this->timingModel = $timingModel;
        $this->packageModel = $packageModel;
        $this->serviceModel = $serviceModel;
        $this->orderModel = $orderModel;
        $this->driverModel = $driverModel;
    }

    public function index(Request $request)
    {

        $timings = $this->timingModel->all();

        foreach ($timings as $timing) {
            $timing['isDay'] = Carbon::parse($timing->name_en)->format('H') < 18;
        }

        return response()->json(['success' => true, 'data' => $timings]);
    }

}

