<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * @param Category $categoryModel
     */
    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = $this->categoryModel->all();

        $categoryIDs = range(1,$categories->count());

        $title = 'Categories';
        return view('admin.categories.index', compact('categories','title','categories','categoryIDs'));
    }

    public function show($id)
    {
        $category = $this->categoryModel->find($id);
        $categories = $this->categoryModel->pluck('name_en','id');
        $title = $category->title;
        $packageIDs = range(1,$category->packages->count());

        return view('admin.categories.view',compact('title','category','categories','packageIDs'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'required|image'
        ]);

        $category =$this->categoryModel->create($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $category->image = $image;
                $category->save();
            } catch (\Exception $e) {
//                $category->delete();
                redirect()->back()->with('success','Category saved but the image failed to Upload');
            }
        }

        return redirect()->back()->with('success','Category Saved');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'image'
        ]);

        $category = $this->categoryModel->find($id);
        $category->update($request->all());

        if($request->hasFile('image')) {
            try {
                $image = $this->uploadImage($request->image);
                $category->image = $image;
                $category->save();
            } catch (\Exception $e) {
                redirect()->back()->with('success','Category saved but the image failed to Upload');
            }
        }

        return redirect()->back()->with('success','Category Updated');
    }

    public function destroy($id)
    {
        $category = $this->categoryModel->find($id);
        $category->delete();
        return redirect()->back()->with('success','Category Deleted');
    }

    public function reOrganize(Request $request)
    {
        $category  = $this->categoryModel->find($request->id);
        $category->order = $request->order;
        $category->save();
        return response()->json(['success'=>true,'id' => $request->id,'order' => $request->order]);
    }

}
