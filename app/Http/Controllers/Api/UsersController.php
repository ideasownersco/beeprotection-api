<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\DeviceInfo;
use App\Models\FreeWash;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Address
     */
    private $addressModel;
    /**
     * @var Customer
     */
    private $customerModel;
    /**
     * @var PushToken
     */
    private $pushTokenModel;
    /**
     * @var DeviceInfo
     */
    private $deviceInfoModel;
    /**
     * @var FreeWash
     */
    private $freeWashModel;

    /**
     * @param User $userModel
     * @param Address $addressModel
     * @param Customer $customerModel
     * @param PushToken $pushTokenModel
     * @param DeviceInfo $deviceInfoModel
     * @param FreeWash $freeWashModel
     */
    public function __construct(User $userModel, Address $addressModel, Customer $customerModel, PushToken $pushTokenModel, DeviceInfo $deviceInfoModel,FreeWash $freeWashModel)
    {
        $this->userModel = $userModel;
        $this->addressModel = $addressModel;
        $this->customerModel = $customerModel;
        $this->pushTokenModel = $pushTokenModel;
        $this->deviceInfoModel = $deviceInfoModel;
        $this->freeWashModel = $freeWashModel;
    }

    public function storePushToken(Request $request)
    {

        if(!$request->token) {
            return response()->json(['success'=>false,'message'=>'Invalid token']);
        }

        $user = Auth::guard('api')->user();

        $pushToken = $this->fixPushToken($request,$user);

        return response()->json(['success'=>true,'data'=>$pushToken]);

    }

    public function storeDeviceID(Request $request)
    {
        if(!$request->uuid) {
            return response()->json(['success'=>false,'message'=>'Invalid Device ID']);
        }

        $user = Auth::guard('api')->user();

        $userID = $user ? $user->id : null;

        $device = $this->deviceInfoModel->where('uuid',$request->uuid)->first();

        if(!$device) {
            $device =$this->deviceInfoModel->create(['user_id' => $userID,'uuid' => $request->uuid]);
        }

        return response()->json(['success'=>true,'data'=>$device]);

    }

    public function hasFreeWash(Request $request)
    {

        // No more free washes
        return response()->json(['success'=>false,'has_free_wash' => true]);

        if(!$request->uuid) {
            return response()->json(['success'=>false,'message'=>'Invalid Device ID']);
        }

        $uuid = $request->uuid;
        $user = Auth::guard('api')->user();

        $freewash = $this->freeWashModel->where('uuid',$uuid);

        if($user) {
            $freewash = $freewash->orWhere('user_id',$user->id);
        }

        $freewash = $freewash->first();

        if(!$freewash) {
            $freewash = $this->freeWashModel->create([
                'uuid'=>$uuid,
                'user_id' =>$user ? $user->id : null,
                'has_free_wash'=>true
            ]);
        }

        if($freewash && !$freewash->has_free_wash) {
            return response()->json(['success'=>true,'data'=>$freewash,'has_free_wash' => false]);
        }

        return response()->json(['success'=>true,'data'=>$freewash,'has_free_wash' => true]);

    }

    public function setFreeWash(Request $request)
    {
        if(!$request->uuid) {
            return response()->json(['success'=>false,'message'=>'Invalid Device ID']);
        }

        $freewash = $this->freeWashModel->where('uuid',$request->uuid)->first();

        if(!$freewash) {
            return response()->json(['success'=>false,'message'=>'Invalid Device ID']);
        }

        $user = Auth::guard('api')->user();

        if(!$user && !$request->force_fill) {
            return response()->json(['success'=>false,'message'=>'Invalid User']);
        }

        $freewash->has_free_wash = $request->has_free_wash;

        if(!$freewash->user_id && $user) {
            $freewash->user_id = $user->id;
        }

        $freewash->save();

        return response()->json(['success'=>true,'data'=>$freewash]);
    }

    public function fixPushToken($request, $user)
    {
        $pushToken = $this->pushTokenModel->where('token',$request->token)->first();

        if($pushToken) {
            if($user && $pushToken->user_id !== $user->id) {
                $pushToken->user_id = $user->id;
                $pushToken->save();
            }
            if($request->os && $pushToken->os != $request->os) {
                $pushToken->os = $request->os;
                $pushToken->save();
            }
        } else {
            $pushToken = $this->pushTokenModel->create([
                'token' => $request->token,
                'os' => $request->os,
                'user_id' => $user ? $user->id : null
            ]);
        }

        if(!$pushToken->user_id) {
            try {
                $player = OneSignalFacade::createPlayer(['device_type' => $request->os === 'ios' ? 0 : 1,'identifier' => $request->token]);

                $res = json_decode($player->getBody(),true);

                if(is_array($res)) {
                    if(isset($res['id'])) {
                        $pushToken->push_id = $res['id'];
                        $pushToken->save();
                    }
                }

            } catch (\Exception $e) {
            }
        } else {
            try {
                OneSignalFacade::editPlayer(['device_type' => $request->os === 'ios' ? 0 : 1,'id' => $pushToken->push_id]);
            } catch (\Exception $e) {
//                Log::info($e->getMessage());
            }
        }

        return $pushToken;
    }

}

