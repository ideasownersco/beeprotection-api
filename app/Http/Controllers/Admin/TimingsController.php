<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimingsController extends Controller
{
    /**
     * @var Package
     */
    private $timingModel;
    /**
     * @var Package
     */
    private $packageModel;
    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * @param Timing $timingModel
     * @param Package $packageModel
     * @param Category $categoryModel
     */
    public function __construct(Timing $timingModel,Package $packageModel,Category $categoryModel)
    {
        $this->timingModel = $timingModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $timings = $this->timingModel->orderBy('name_en','asc')->get();
        $title = 'Timings';
        return view('admin.timings.index', compact('title','timings'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name_en' => 'required',
        ]);

        $parsedTime = Carbon::parse($request->name_en)->toTimeString();

        $duplicate = $this->timingModel->where('name_en',$parsedTime)->first();

        if($duplicate) {
            return redirect()->back()->with('success','Timing Saved');
        }

        $timing =$this->timingModel->create(['name_en'=>$parsedTime,'name_ar'=>$parsedTime]);

        return redirect()->back()->with('success','Timing Saved');
    }

    public function destroy($id)
    {
        $timing = $this->timingModel->find($id);
        $timing->delete();
        return redirect()->back()->with('success','Timing Deleted');
    }

}
