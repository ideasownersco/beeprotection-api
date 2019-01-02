<?php

namespace App\Http\Controllers\Api\Customer;

use App\Core\GoogleMapsGeocoder;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressesController extends Controller
{
    /**
     * @var Address
     */
    private $addressModel;
    /**
     * @var Area
     */
    private $areaModel;

    /**
     * AddressesController constructor.
     * @param Address $addressModel
     * @param Area $areaModel
     */
    public function __construct(Address $addressModel, Area $areaModel)
    {
        $this->addressModel = $addressModel;
        $this->areaModel = $areaModel;
    }

    public function index()
    {
        $user = Auth::guard('api')->user();
        $user->load(['addresses'=>function($q) {
           $q->has('area');
        }]);
        return response()->json(['success' => true, 'data' => new UserResource($user)]);
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $user = Auth::guard('api')->user();

        $address = $user->addresses()->create($request->all());

        $response = \GoogleMaps::load('geocoding')
            ->setParam ([
                'latlng' => $request->latitude .','.$request->longitude,
            ])
            ->get();

        $collection = collect(json_decode($response))->flatten();

//        dd($collection);

        $longNames = $collection->pluck('address_components')->flatten()->pluck('long_name');

        $block = '';
        $street = '';
        $subLocality = '';

        foreach ($longNames as $name) {
            if (strpos($name, 'Block') !== false) {
                $block = trim(str_replace('Block', '', $name));
            }
        }

        foreach ($longNames as $name) {
            if (strpos($name, 'Street') !== false) {
                $street = trim(str_replace('Street', '', $name));
            }
        }

        foreach ($collection as $record) {
            $types = property_exists($record,'types') ? $record->types : [];
            if(in_array('sublocality',$types)) {
                $subLocality = $record->address_components[0]->long_name;
            }
        }

        if(empty($subLocality)) {
            foreach ($collection as $record) {
                $types = property_exists($record,'types') ? $record->types : [];
                if(in_array('locality',$types)) {
                    $subLocality = $record->address_components[0]->long_name;
                }
            }
        }

//        dd($address);

        $address->block = $block;
        $address->street = $street;

        if($subLocality) {
            $area = $this->areaModel
                ->where('parent_id','!=',null)
                ->where('name_en',$subLocality)->first();
            if($area) {
                $address->area_id = $area->id;
            }
        }

        $address->save();
        $address->load('area');

        $user->load(['addresses'=>function($q) {
            $q->has('area');
        }]);

        if(!$address->area) {
            $address->delete();
            return response()->json(['message' => 'We dont deliver our services to this address yet.', 'success' => false]);
        }

        return response()->json(['data' => new UserResource($user), 'success' => true,'address_id'=>$address->id, 'address' => $address->area ? new AddressResource($address) : null,]);
    }

    public function update($id,Request $request)
    {

        $validation = Validator::make($request->all(), [
            'street'  => 'required',
            'block'  => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()->first()], 422);
        }

        $user = Auth::guard('api')->user();

        $address = $this->addressModel->find($id);

        $address->block = $request->block;
        $address->street = $request->street;
        $address->avenue = $request->avenue;
        $address->building = $request->building;
        $address->label = $request->label;
        $address->save();
        $address->load('area');

        $user->load(['addresses'=>function($q) {
            $q->has('area');
        }]);

        return response()->json(['data' => new UserResource($user), 'success' => true,'address_id'=>$address->id, 'address' => $address->area ? new AddressResource($address) : null,]);
    }


    public function delete(Request $request)
    {
        $address = $this->addressModel->find($request->address_id);

        if(!$address) {
            return response()->json(['success' => false, 'message' => 'Unknown Address'] );
        }

        $address->delete();

        $address->deleted = true;

        $user = Auth::guard('api')->user();

//        $user->addresses = [(new AddressResource($address))->additional(['deleted'=>true])];
//        $user->addresses = [$address];

//        $user->load(['addresses'=>function($q) {
//            $q->has('area');
//        }]);

//        $data = (new UserResource($user))->additional(['addresses' => [(new AddressResource($address))->additional(['deleted'=>true])] ]);

//        return response()->json(['data' => $data, 'success' => true]);
        return response()->json(['data' => $user, 'success' => true, 'address_id' => $address->id]);
    }
}

