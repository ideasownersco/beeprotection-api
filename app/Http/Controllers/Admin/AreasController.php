<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Area;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreasController extends Controller
{
    /**
     * @var Area
     */
    private $areaModel;
    /**
     * @var User
     */
    private $userModel;

    /**
     * AreasController constructor.
     * @param Area $areaModel
     * @param User $userModel
     */
    public function __construct(Area $areaModel,User $userModel)
    {
        $this->areaModel = $areaModel;
        $this->userModel = $userModel;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $governorates = $this
            ->areaModel
            ->with(['children'=>function($q){
                $q->withCount('orders');
            }])
            ->where('parent_id',null)->get();

        $areas = $this->areaModel->with(['parent'])->withCount(['orders'])->where('parent_id','!=',null)->get();

        $parentAreas = $this->areaModel->where('parent_id',null)->pluck('name_en','id');
        $title = 'Areas';
        return view('admin.areas.index', compact('areas','title','parentAreas','governorates'));
    }

    public function show($id)
    {
        $area = $this->areaModel->find($id);
        $parentAreas = $this->areaModel->where('parent_id',null)->pluck('name_en','id');
        $title = $area->name;
        return view('admin.areas.view',compact('title','area','parentAreas'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'parent_id' => 'required|integer',
            'name_ar'     => 'required|max:100',
            'name_en'     => 'required|max:100',
            'active' => 'boolean'
        ]);

        $this->areaModel->create([
            'country_id' => 1,
            'parent_id' => $request->parent_id,
            'name_ar'      => $request->name_ar,
            'name_en'      => $request->name_en,
            'active' => $request->active
        ]);

        return redirect()->back()->with('success','Area Saved');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'parent_id' => 'required|integer',
            'name_ar'     => 'required|max:100',
            'name_en'     => 'required|max:100',
            'active' => 'boolean'
        ]);

        $area = $this->areaModel->find($id);

        $area->update([
            'name_ar'      => $request->name_ar,
            'name_en'      => $request->name_en,
            'active' => $request->active ? $request->active : false
        ]);

        return redirect()->route('admin.areas.index')->with('success','Area Updated');
    }

    public function destroy($id)
    {
        $area = $this->areaModel->find($id);
        $area->delete();
        return redirect()->back()->with('success','Area Deleted');
    }
}
