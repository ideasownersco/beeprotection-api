<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * CategoriesController constructor.
     * @param Category $categoryModel
     */
    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function makeItems(Request $request)
    {

        $categories = explode(',',$request->categories);
        $packages = explode(',',$request->packages);
        $services = explode(',',$request->services);

        $categories = $this->categoryModel
            ->whereHas('packages', function ($q) {
                $q->active();
            })
            ->with(['packages' => function($q) use ($packages) {
                $q
                    ->active()
                    ->whereIn('id',$packages)
                ;
            }])
            ->with(['packages.services'=>function($q) use ($services) {
                $q
                    ->active()
                    ->whereIn('id',$services)
                ;
            }])->active()->whereIn('id',$categories)->get();

        return new CategoriesCollection($categories);
    }

}

