<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    /**
     * @var Package
     */
    private $serviceModel;
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * @param Service $serviceModel
     * @param Package $packageModel
     * @param Category $categoryModel
     */
    public function __construct(Service $serviceModel,Package $packageModel,Category $categoryModel)
    {
        $this->serviceModel = $serviceModel;
        $this->packageModel = $packageModel;
        $this->categoryModel = $categoryModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $packages = $this->packageModel->has('category')->active()->pluck('name_en','id');
        $services = $this->serviceModel->with(['package'])->has('package')->active()->get();
        $title = 'Services';
        return view('admin.services.index', compact('packages','title','services'));
    }

    public function show($id)
    {
        $service = $this->serviceModel->find($id);
        $packages = $this->packageModel->has('category')->pluck('name_en','id');
        $title = $service->name;
        return view('admin.services.view',compact('title','service','packages'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
//            'category_id' => 'required',
            'name_en' => 'required',
            'name_ar' => 'required',
            'parent_id' => 'nullable|numeric',
            'duration' => 'integer',
            'image' => 'required|image',
            'price' => 'numeric'
        ]);

        $service =$this->serviceModel->create($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $service->image = $image;
                $service->save();
            } catch (\Exception $e) {
                redirect()->back()->with('success','Services Saved but The Image failed to Upload');
            }
        }
        return redirect()->back()->with('success','Service Saved');
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            'name_en' => 'required',
            'name_ar' => 'required',
            'parent_id' => 'nullable|numeric',
            'duration' => 'integer',
            'image' => 'image',
            'price' => 'numeric'
        ]);

        $service = $this->serviceModel->find($id);
        $service->update($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                dd($image);
                $service->image = $image;
                $service->save();
            } catch (\Exception $e) {
                dd($e->getMessage());
                redirect()->back()->with('success','Services Could not be saved because The Image failed to Upload');
            }
        }

        return redirect()->back()->with('success','Service Updated');
    }

    public function destroy($id)
    {
        $service = $this->serviceModel->find($id);
        $service->delete();
        return redirect()->back()->with('success','Service Deleted');
    }
}
