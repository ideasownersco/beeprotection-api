<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushToken;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{


    private $pushTokenModel;

    /**
     * ServicesController constructor.
     * @param Pushtoken $pushTokenModel
     * @internal param Service $serviceModel
     * @internal param Category $categoryModel
     */
    public function __construct(PushToken $pushTokenModel )
    {
        $this->pushTokenModel = $pushTokenModel;
    }

    public function index(Request $request)
    {
        return view('admin.notifications.index');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'message' => 'required|max:255'
        ]);

        try {
            OneSignalFacade::sendNotificationToAll($request->message);
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Cannot send notifications '.$e->getMessage());
        }

        return redirect()->back()->with('success','Push Notifications Sent');
    }
//    public function store(Request $request)
//    {
//        $this->validate($request,[
//            'message' => 'required|max:255'
//        ]);
//
//        $pushTokens = $this->pushTokenModel->where('os','ios')->pluck('token')->toArray();
//
//        try {
//            SendPushNotificationsToAllDevice::dispatch($pushTokens,$request->message)->onQueue('push');
//        } catch (\Exception $e) {
//            return redirect()->back()->with('warning', 'Cannot send notifications '.$e->getMessage());
//        }
//
//        return redirect()->back()->with('success','Push Notifications Sent');
//    }
}
