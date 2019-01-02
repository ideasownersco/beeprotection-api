<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\PushToken;
use App\Models\User;

class TestController extends Controller
{
    /**
     * @var PushToken
     */
    private $pushTokenModel;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Area
     */
    private $area;

    /**
     * TestController constructor.
     * @param PushToken $pushTokenModel
     * @param User $userModel
     * @param Area $area
     */
    public function __construct(PushToken $pushTokenModel,User $userModel,Area $area)
    {
        $this->pushTokenModel = $pushTokenModel;
        $this->userModel = $userModel;
        $this->area = $area;
    }

    public function index()
    {

        $cities = $this->area->whereNotNull('parent_id')->get();

        $googleKey = 'AIzaSyCpQX4H0QPxVgKuNMZ0ELG_ymgT8RHcKh4';

        foreach($cities as $city) {
            $geoCodeAddress = $this->geocodeAddress($city->name_ar . ', كويت');

            if(is_array($geoCodeAddress)) {
                $city->update($geoCodeAddress);
            } else {
                $geoCodeAddress = $this->geocodeAddress($city->name_en . ', Kuwait');
                if(is_array($geoCodeAddress)) {
                    $city->update($geoCodeAddress);
                }
            }
        }

        dd($cities->toArray());
    }

    /*
     Geocodes an addres so we can get the latitude and longitude
   */
    public function geocodeAddress( $city ){
        /*
          Builds the URL and request to the Google Maps API
        */
//        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode( $address.' '.$city.', '.$state.' '.$zip ).'&key='.env( 'GOOGLE_MAPS_KEY' );
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode( $city ).'&key=AIzaSyCpQX4H0QPxVgKuNMZ0ELG_ymgT8RHcKh4&components=country:KW';

        /*
          Creates a Guzzle Client to make the Google Maps request.
        */
        $client = new \GuzzleHttp\Client();

        /*
          Send a GET request to the Google Maps API and get the body of the
          response.
        */
        $geocodeResponse = $client->get( $url )->getBody();

        /*
          JSON decodes the response
        */
        $geocodeData = json_decode( $geocodeResponse );

        /*
          Initializes the response for the GeoCode Location
        */
        $coordinates['latitude'] = null;
        $coordinates['longitude'] = null;

        /*
          If the response is not empty (something returned),
          we extract the latitude and longitude from the
          data.
        */
        if( !empty( $geocodeData )
            && $geocodeData->status != 'ZERO_RESULTS'
            && isset( $geocodeData->results )
            && isset( $geocodeData->results[0] ) ){
            $coordinates['latitude'] = $geocodeData->results[0]->geometry->location->lat;
            $coordinates['longitude'] = $geocodeData->results[0]->geometry->location->lng;
        }

        /*
          Return the found coordinates.
        */
        return $coordinates;

    }

}

