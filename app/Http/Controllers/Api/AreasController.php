<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Package;
use App\Models\Service;
use App\Models\Timing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreasController extends Controller
{

    /**
     * @var Area
     */
    private $areaModel;

    /**
     * @param Area $areaModel
     */
    public function __construct(Area $areaModel)
    {
        $this->areaModel = $areaModel;
    }

    public function index(Request $request)
    {
        $areas = $this->areaModel->whereNotNull('parent_id')->get();
        return response()->json(['success' => true, 'data' => AreaResource::collection($areas)]);

    }

}

