<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimingResource;
use App\Models\Driver;
use App\Models\Holiday;
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
     * @var Holiday
     */
    private $holidayModel;

    /**
     * @param Timing $timingModel
     * @param Package $packageModel
     * @param Service $serviceModel
     * @param Order $orderModel
     * @param Driver $driverModel
     * @param Holiday $holidayModel
     */
    public function __construct(Timing $timingModel, Package $packageModel, Service $serviceModel, Order $orderModel, Driver $driverModel, Holiday $holidayModel)
    {
        $this->timingModel = $timingModel;
        $this->packageModel = $packageModel;
        $this->serviceModel = $serviceModel;
        $this->orderModel = $orderModel;
        $this->driverModel = $driverModel;
        $this->holidayModel = $holidayModel;
    }

    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date'  => 'required|date',
            'items' => 'array',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $orderDate = Carbon::parse($request->date)->toDateString();

        $packageDuration = 0;
        $serviceDuration = 0;

        $timings = $this->timingModel->all();

        $isToday = Carbon::parse($orderDate)->isToday();


        $isHoliday = $this->holidayModel->whereDate('date',$orderDate)->first();

        if($isHoliday) {
            foreach ($timings as $timing) {
                $timing['disabled'] = true;
            }
        } else {
            if ($request->free_wash) {

                $dateMax = Carbon::createFromDate('2019','1','14');

                if(Carbon::parse($orderDate)->gt($dateMax)) {
                    foreach ($timings as $timing) {
                        $timing['disabled'] = true;
                    }
                } else {
                    foreach ($timings as $timing) {
                        try {
                            $orderDateTime = Carbon::parse($orderDate . ' ' . $timing->name_en);

                            if($isToday) {
                                $timing['disabled'] = $orderDateTime->lt(Carbon::now()->addMinutes(29));
                            }

                            if(!$timing['disabled']) {
                                $orderDuration = $this->orderModel->calculateDuration($timing->name_en, $packageDuration, $serviceDuration,true);

                                $workingFinishingHour = Carbon::parse($orderDate . $orderDuration)->format('H');

                                if(!($workingFinishingHour >= 9 && $workingFinishingHour <= 23)) {
                                    $timing['disabled'] = true;
                                } else {
                                    $hasFreeDriver = $this->driverModel->getAvailableDriver($orderDate, $timing->name_en, $orderDuration);
                                    $timing['disabled'] = $hasFreeDriver ? false : true;
                                }

                            }
                        } catch (\Exception $e) {
                            return response()->json(['success' => false, 'message' => 'some error occured']);
                        };
                        $timing['isDay'] = Carbon::parse($timing->name_en)->format('H') < 18;
                    }

                }
            } else {

                // If not free wash
                foreach ($request->items as $item) {
                    if (array_key_exists('package', $item)) {
                        $package = $this->packageModel->find($item['package']);
                        if ($package) {
                            $hourDuration = 1;
                            if (isset($item['quantity']) && $item['quantity'] > 1) {
                                $hourDuration = $this->getPackageQuantityHours($item['quantity']);
                            }
                            $packageDuration += $package->duration * $hourDuration;
                        } else {
                            $packageDuration += $package->duration;
                        }
                    }

                    if (array_key_exists('services', $item)) {
                        $serviceDurations = $this->serviceModel->whereIn('id', $item['services'])->sum('duration');
                        $serviceDuration += $serviceDurations;
                    }
                }

                foreach ($timings as $timing) {
                    try {
                        $orderDateTime = Carbon::parse($orderDate . ' ' . $timing->name_en);

                        if($isToday) {
                            $timing['disabled'] = $orderDateTime->lt(Carbon::now()->addMinutes(29));
                        }

                        if(!$timing['disabled']) {
                            $orderDuration = $this->orderModel->calculateDuration($timing->name_en,$packageDuration, $serviceDuration,false);

                            // Check if Order end time crosses maximum limit of working hour for driver. i.e 11PM
                            $workingFinishingHour = Carbon::parse($orderDate . $orderDuration)->format('H');
                            if(!($workingFinishingHour >= 9 && $workingFinishingHour <= 23)) {
                                $timing['disabled'] = true;
                            } else {
                                $hasFreeDriver = $this->driverModel->getAvailableDriver($orderDate, $timing->name_en, $orderDuration);
                                $timing['disabled'] = $hasFreeDriver ? false : true;
                            }

                        }

                    } catch (\Exception $e) {
                        return response()->json(['success' => false, 'message' => 'some error occured']);
                    };

                    $timing['isDay'] = Carbon::parse($timing->name_en)->format('H') < 18;

                }

            }

        }


        return response()->json(['success' => true, 'data' => $timings,'packageDuration' => $packageDuration]);
    }


    public function getPackageQuantityHours($quantity)
    {

        switch ($quantity) {
            case $quantity < 11 :
                $hourDuration = 1;
                break;
            case $quantity < 21 :
                $hourDuration = 2;
                break;
            case $quantity < 31 :
                $hourDuration = 3;
                break;
            case $quantity < 41 :
                $hourDuration = 4;
                break;
            case $quantity < 51 :
                $hourDuration = 5;
                break;
            case $quantity < 61 :
                $hourDuration = 6;
                break;
            case $quantity < 71 :
                $hourDuration = 7;
                break;
            case $quantity < 81 :
                $hourDuration = 8;
                break;
            case $quantity < 91 :
                $hourDuration = 9;
                break;
            case $quantity <= 100 :
                $hourDuration = 10;
                break;
            default :
                $hourDuration = 1;
                break;
        }

        return $hourDuration;
    }

}