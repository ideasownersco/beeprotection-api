<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @var User
     */
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = null;

        if($request->sort) {
            $sort = $request->sort;
        }

        $users = $this->userModel->with(['driver']);

        $searchQuery = $request->search;


        if($searchQuery) {
            $users = $users
                ->where(function($q) use ($searchQuery) {
                    $q
                        ->where ('mobile', 'LIKE', "%$searchQuery%")
                        ->orWhere ('name', 'LIKE', "%$searchQuery%")
                        ->orWhere ('email', 'LIKE', "%$searchQuery%")
                    ;

                })
            ;
        }

        if($sort) {
            $users = $users->orderBy('active','ASC');
        }

        $users =
            $users
//                ->where('active',1)
                ->latest()
                ->paginate(100);
        $title = 'Users';

        return view('admin.users.index', compact('users','title','searchQuery','sort'));
    }

    public function show($id)
    {
        $user = $this->userModel->with(['orders'=>function($q){
            $q->success();
        }])->find($id);
        $title = $user->name;
        return view('admin.users.view',compact('title','user'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'     => 'required|max:50',
            'email'    => 'email|required|unique:users,email',
            'password' => 'required|confirmed',
            'mobile'   => 'integer|required|unique:users,mobile|digits:8',
            'offline' => 'boolean'
        ]);

        $name = $request->name;
        $email = strtolower($request->email);
        $password = bcrypt($request->password);
        $mobile = $request->mobile;
        $apiToken = str_random(16);

        $user = $this->userModel->create([
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,
            'mobile'    => $mobile,
            'api_token' => $apiToken,
            'admin' => $request->admin
        ]);

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $user->image = $image;
                $user->save();
            } catch (\Exception $e) {
                redirect()->back()->with('success','The Image failed to Upload');
            }
        }
        return redirect()->back()->with('success','User Saved');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'     => 'nullable|max:50',
            'mobile'   => 'nullable|unique:users,mobile,'.$id,
            'mobile'   => 'nullable|integer||digits:8|unique:users,mobile,'.$id,
            'email'    => 'email|nullable|unique:users,email,'.$id,
            'password' => 'nullable|confirmed',
        ]);

//        dd($request->all());

        $user = $this->userModel->find($id);

        if($request->filled('name')) {
            $user->name = $request->name;
        }

        if($request->filled('mobile')) {
            $user->mobile = $request->mobile;
        }

        if($request->filled('email')) {
            $user->email = $request->email;
        }

        if($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if($request->filled('admin')) {
            $user->admin = $request->admin;
        }

        if($request->filled('active')) {
            $user->active = $request->active;
        }

        if($request->filled('blocked')) {
            $user->blocked = $request->blocked;
        }

        $user->save();

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $user->image = $image;
                $user->save();
            } catch (\Exception $e) {
                $user->delete();
                redirect()->back()->with('success','Users Could not be saved because The Image failed to Upload');
            }
        }

        return redirect()->route('admin.users.index')->with('success','User Updated');
    }

    public function destroy($id)
    {
        $user = $this->userModel->find($id);

        if(auth()->user()->id === $user->id) {
            return redirect()->back()->with('warning','Invalid operation');
        }

        if($user->driver) {
            $driver = $user->driver;
            $driver->delete();
        }

        $user->delete();
        return redirect()->back()->with('success','User Deleted');
    }
}
