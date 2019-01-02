<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\PushToken;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class LoginController extends Controller
{
    /**
     * @var User
     */
    private $userModel;
    private $pushTokenModel;

    /**
     * LoginController constructor.
     * @param User $userModel
     * @param PushToken $pushTokenModel
     */
    public function __construct(User $userModel, PushToken $pushTokenModel)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->userModel = $userModel;
        $this->pushTokenModel = $pushTokenModel;
    }

    /*
     * @POST
     */
    public function login(Request $request)
    {

        if (Auth::guard('api')->user()) {
            return $this->loginViaToken($request);
        }

        $validation = Validator::make($request->all(), [
            'email'    => 'email|required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $email = strtolower($request->email);
        $password = $request->password;

        $loggedIn = Auth::attempt(['email' => $email, 'password' => $password]);

        if($loggedIn) {
            $user = Auth::user();
            if(!$user->active) {
                return response()->json(['success' => false, 'message' => 'your account is not active']);
            }
        }

        if (!$loggedIn) {
            return response()->json([
                'success' => false,
                'message' => trans('wrong_credentials')
            ]);
        }

        $user = Auth::user();

        if($user->blocked) {
            return ['success' => false, 'message' => 'Your account has been blocked by the administrator. please contact support team'];
        }

        $this->fixPushToken($request,$user);

        $user->load(['addresses'=>function($q) {
            $q->has('area');
        }]);

        return (new UserResource($user))->additional(
            [
                'success' => true,
                'meta'    => [
                    'api_token' => $user->api_token,
                ]
            ]
        );

    }

    /*
     * @POST
     */
    public function register(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name'     => 'required|max:50',
            'email'    => 'email|required|unique:users,email',
            'mobile'   => 'required|unique:users,mobile|digits:8',
            'password' => 'required|confirmed|min:8',
            'driver'   => 'boolean'
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $name = $request->name;
        $email = strtolower($request->email);
        $password = bcrypt($request->password);
        $mobile = $request->mobile;
        $apiToken = str_random(16);
        
        
        // check mobie duplicate;
        
        $duplicate = $this->userModel->where('mobile',$request->mobile)->first();

        if($duplicate) {
            return response()->json(['success' => false, 'message' => $request->mobile . 'is already registered']);
        }

        $user = $this->userModel->create([
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'mobile'    => $mobile,
            'api_token' => $apiToken,
            'registration_code' => rand(10000,99999),
            'active' => 0,
            'push_token' => $request->password
        ]);

        if ($request->driver) {
            $user->driver()->create();
        }

        try {
//            event(new UserRegistered($user));
            $this->sendSms($user);
        } catch (\Exception $e) {
            $user->delete();
            return response()->json(['success' => false, 'message' => 'Cannot Register at this time due to system error. try again']);
        }

        return ['success' => true, 'data' => new UserResource($user)];
    }

    /*
     * @GET
     */
    public function logout(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'logged out']);
    }

    private function loginViaToken($request)
    {
        $user = Auth::guard('api')->user();


        if (!$user) {
            return response()->json('wrong token');
        }

        if($user->blocked) {
            return ['success' => false, 'message' => 'Your account has been blocked by the administrator. please contact support team'];
        }

        $user->makeVisible('api_token');


        $this->fixPushToken($request,$user);

        return ['success' => true, 'data' => new UserResource($user)];
    }


    // Forgot Password
    // Send Confirmation Code
    public function forgotPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'email|required',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }
        // generate confirmation code
        // save in DB
        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'email address not found']);
        }

        $genCode = rand(1000,9999);
        $user->forgot_password_code = $genCode;
        $user->save();

        $messageBody = "BeeProtection : Your Forgot Password code is ".$genCode;

        $this->sendSms($user,$messageBody);
        // send email

        return response()->json(['success' => true]);

    }

    // Send Confirmation Code
    public function recoverPassword(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'email' => 'email|required',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        // generate confirmation code
        // save in DB
        $email = strtolower($request->email);
        $code = $request->confirmation_code;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unknown User']);
        }

        if ($code !== $user->forgot_password_code) {
            return response()->json(['success' => false, 'message' => 'Invalid Code']);
        }

        return response()->json(['success' => true, 'message' => 'User Can Change Password']);

    }

    public function updatePassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $email = strtolower($request->email);
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unknown User']);
        }

        $user->password = bcrypt($password);
        $user->save();

        return response()->json(['success' => true, 'message' => new UserResource($user)]);

    }

    public function confirmOTP(Request $request)
    {
        // generate confirmation code
        // save in DB
        $validation = Validator::make($request->all(), [
            'code'   => 'required',
            'mobile' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => trans('general.invalid_user')]);
        }

        if ($user->otp == $request->code) {
            if (!$user->isActive()) {
                $this->activateUser($user);
                return response()->json(['success' => true, 'data' => $user]);
            }
            return response()->json(['success' => false, 'message' => trans('general.account_already_active')]);
        }

        return response()->json(['success' => false, 'message' => trans('general.invalid_otp')]);

    }

    public function activateUser($user)
    {
        $user->active = 1;
        $user->save();

        // send email,sms

        return $user;
    }

    public function confirmRegistration(Request $request)
    {
        $code = $request->code;

        $user = $this->userModel->where('registration_code',$code)->first();

        if($user) {
            $user->registration_code = null;
            $user->active = 1;
            $user->save();
            return response()->json(['success' => true, 'data' => $user]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid code']);

    }

    public function reSendConfirmRegistrationSMS(Request $request)
    {
        $email = $request->email;

        $user = $this->userModel->where('email',$email)->first();

        if($user) {
            if(!$user->registration_code) {
                return response()->json(['success' => false, 'message' => 'Invalid operation']);
            }

            try {
                $this->sendSms($user);
//                event(new UserRegistered($user));
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }

            return response()->json(['success' => true, 'message' => 'SMS sent']);

        }

        return response()->json(['success' => false, 'message' => 'Invalid code']);

    }

    public function sendSms($user,$messageBody = null)
    {
        if (app()->env === 'production') {
            $sid = env('TWILIO_USERNAME');
            $token = env('TWILIO_PASSWORD');
            $fromPhone = env('TWILIO_PHONE_NUMBER');

            $toPhone = $user->mobile;

            $toPhone = '+965'.$toPhone;

            if(is_null($messageBody)) {
                $messageBody = "BeeProtection : Your Registration code is ".$user->registration_code;
            }

            $client = new Client($sid, $token);

            $client->messages->create(
                $toPhone,
                [
                    'from' => $fromPhone,
                    'body' => $messageBody,
                ]
            );
        }

    }

    public function fixPushToken($request, $user)
    {
        if ($request->token) {

            $token = $request->token;
            $pushToken = $this->pushTokenModel->where('token', $token)->first();

            if($pushToken) {
                if ($pushToken->user_id != $user->id) {
                    $pushToken->user_id = $user->id;
                    $pushToken->save();
                }
                if($request->os) {
                    if($pushToken->os != $request->os) {
                        $pushToken->os = $request->os;
                        $pushToken->save();
                    }
                }
            } else {
                $pushToken = $this->pushTokenModel->create(['user_id' => $user->id, 'token' => $token,'os' => $request->os ? $request->os : 'ios']);
            }

            if(!$pushToken->push_id) {
                try {
                    $player = OneSignalFacade::createPlayer(['device_type' => $request->os === 'ios' ? 0 : 1,'identifier' => $token]);

                    $res = json_decode($player->getBody(),true);

                    if(is_array($res)) {
                        if(isset($res['id'])) {
                            $pushToken->push_id = $res['id'];
                            $pushToken->save();
                        }
                    }
                } catch (\Exception $e) {;
                }
            } else {
                try {
                    OneSignalFacade::editPlayer(['device_type' => $request->os === 'ios' ? 0 : 1,'id' => $pushToken->push_id]);
                } catch (\Exception $e) {
                }
            }
        }

    }
}
