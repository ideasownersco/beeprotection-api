<?php

namespace App\Http\Controllers\Api\Driver;

use App\Events\DriverLocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersResource;
use App\Managers\JobManager;
use App\Models\Job;
use App\Models\Order;
use App\Models\PushToken;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cache;

class JobsController extends Controller
{

    /**
     * @var Order
     */
    private $orderModel;
    /**
     * @var Job
     */
    private $jobModel;
    /**
     * @var JobManager
     */
    private $jobManager;
    /**
     * @var PushToken
     */
    private $pushTokenModel;

    /**
     * OrdersController constructor.
     * @param Order $orderModel
     * @param Job $jobModel
     * @param JobManager $jobManager
     * @param PushToken $pushTokenModel
     */
    public function __construct(Order $orderModel, Job $jobModel, JobManager $jobManager, PushToken $pushTokenModel)
    {
        $this->orderModel = $orderModel;
        $this->jobModel = $jobModel;
        $this->jobManager = $jobManager;
        $this->pushTokenModel = $pushTokenModel;
    }

    public function startDriving($jobID,Request $request)
    {
        $job = $this->jobModel->with(['order.user'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        $user = Auth::guard('api')->user();
        $driver = $user->driver;

        $driver->update(['latitude'=>$request->latitude,'longitude'=>$request->longitude]);

        try {
            $job->startDriving();
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'message' => $e->getMessage()]);
        }

        return response()->json(['success'=>true,'data'=> new OrdersResource($job->order)]);
    }

    public function stopDriving($jobID, Request $request)
    {
        $job = $this->jobModel->with(['order'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        $user = Auth::guard('api')->user();
        $driver = $user->driver;

        $driver->update(['latitude'=>$request->latitude,'longitude'=>$request->longitude]);

        try {
            $job->stopDriving();
        } catch (\Exception $e) {
        }
        return response()->json(['success'=>true,'data'=> new OrdersResource($job->order)]);
    }

    public function startWorking($jobID)
    {
        $job = $this->jobModel->with(['order.user'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        try {
            $job->startWorking();
        } catch (\Exception $e) {
        }
        return response()->json(['success'=>true,'data'=> new OrdersResource($job->order)]);
    }

    public function stopWorking($jobID)
    {
        $job = $this->jobModel->with(['order'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        try {
            $job->stopWorking();
        } catch (\Exception $e) {
        }
        return response()->json(['success'=>true,'data'=> new OrdersResource($job->order)]);
    }

    public function updateLocation($jobID,Request $request)
    {

        $job = $this->jobModel->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message'=>'Invalid Job']);
        }

        if(!$job->driving) {
            return response()->json(['success'=>false,'message'=>'Tracking Not Available']);
        }

        $coords = $request->location['coords'];
        $payload = [
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'heading' => $coords['heading'],
            'driver_id' => $request->driver_id,
            'job_id' => $jobID,
            'order_id' => $job->order->id,
        ];

        event(new DriverLocationUpdated($payload));

        $driver = $job->driver;

        $cacheKey = 'job_id_'.$jobID;

        if(!Cache::has($cacheKey)) {
            Cache::set($cacheKey,uniqid(),10);
            $driver->update(['latitude' => $coords['latitude'],'longitude' => $coords['longitude']]);
        }

        return response()->json(['success'=>true,'data'=>$payload]);
    }

    public function getPhotos($jobID)
    {
        $job = $this->jobModel->with(['order'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        $order = $job->order;
        $order->load('job.photos');
        return response()->json(['success'=>true,'data'=>new OrdersResource($order)]);
    }

    public function uploadPhotos($jobID,$request)
    {
//        return response()->json(['success'=>true,'message' => 'wa']);

        $job = $this->jobModel->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        $images = $request->images;

        $uploadedImages = [];

        if(count($images)) {

            try {
                foreach ($images as $image) {
                    $uploadedImage = $image.'.jpg';
//                    $uploadedImage = $this->uploadAWSImage($image,'jobs');
                    $uploadedImages[] = ['url'=>$uploadedImage];
                }
                $job->photos()->createMany($uploadedImages);
            } catch (\Exception $e) {
                return response()->json(['success'=>false,'message'=>'uploading image failed. '.$e->getMessage()]);
            }
        }

        $order = $job->order;
        $order->load('job.photos');

        return response()->json(['success'=>true,'data'=>new OrdersResource($order)]);
    }

    public function approvePhotos($jobID,Request $request)
    {
        $job = $this->jobModel->with(['order'])->find($jobID);

        if(!$job) {
            return response()->json(['success'=>false,'message' => 'Invalid Job']);
        }

        $job->photos_approved = true;
        $job->photo_comment = $request->comment;
        $job->save();
        $order = $job->order;

        return response()->json(['success'=>true,'data'=>new OrdersResource($order)]);
    }
}

