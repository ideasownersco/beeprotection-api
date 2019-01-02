<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoriesController extends Controller
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

    public function index()
    {
        $categories = $this->categoryModel
            ->whereHas('packages', function ($q) {
                $q->active();
            })
            ->with(['packages.services'=>function($q){
                $q->active();
            }])
            ->active()
            ->orderBy('order','asc')
            ->paginate(100);

        return CategoryResource::collection($categories)->additional(['success' => true]);
    }

}

