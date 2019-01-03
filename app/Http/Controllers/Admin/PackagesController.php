<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
{
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * @param Package $packageModel
     * @param Category $categoryModel
     */
    public function __construct(Package $packageModel,Category $categoryModel)
    {
        $this->packageModel = $packageModel;
        $this->categoryModel = $categoryModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $packages = $this->packageModel->with(['category','services'])->has('category')->active()->get();
        $categories = $this->categoryModel->active()->pluck('name_en','id');
        $title = 'Packages';
        return view('admin.packages.index', compact('packages','title','packages','categories','packageIDs'));
    }

    public function show($id)
    {
        $package = $this->packageModel->with(['category','services.package'])->find($id);
        $categories = $this->categoryModel->active()->pluck('name_en','id');
        $title = $package->title;
        return view('admin.packages.view',compact('title','package','categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'category_id' => 'required',
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'image',
            'duration' => 'integer',
            'price' => 'numeric',
        ]);

        $package =$this->packageModel->create($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $package->image = $image;
                $package->save();
            } catch (\Exception $e) {
//                $package->delete();
                redirect()->back()->with('success','Package Savee but The Image failed to Upload');
            }
        }
        return redirect()->back()->with('success','Package Saved');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'category_id' => 'required',
            'name_en' => 'string|required',
            'name_ar' => 'string|required',
            'duration' => 'integer',
            'price' => 'numeric',
            'image' => 'image',
        ]);

        $package = $this->packageModel->find($id);
        $package->update($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $package->image = $image;
                $package->save();
            } catch (\Exception $e) {
                redirect()->back()->with('success','Package saved but The Image failed to Upload');
            }
        }

        return redirect()->back()->with('success','Package Updated');
    }

    public function destroy($id)
    {
        $package = $this->packageModel->find($id);
        $package->delete();
        return redirect()->back()->with('success','Package Deleted');
    }

    public function reOrganize(Request $request)
    {
        $package  = $this->packageModel->find($request->id);
        $package->order = $request->order;
        $package->save();
        return response()->json(['success'=>true,'id' => $request->id,'order' => $request->order]);
    }


}
