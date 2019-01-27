<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Cache;

class Controller extends BaseController
{

    const UPLOAD_PATH = '/uploads/';

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function customValidate($requestArray, $rules)
    {

        $validator = Validator::make($requestArray->all(), $rules);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->first()];
        }

        return true;

    }

    /**
     * @param $image
     * @return string
     * @throws \Exception
     */
    protected function uploadImage($image)
    {
        if (!$image->isValid()) {
            throw new \Exception('invalid image');
        }

        $imageName = md5(uniqid(rand() * (time()))) . '.' . $image->getClientOriginalExtension();
        $savePath = public_path() . self::UPLOAD_PATH . $imageName;

        Image::make($image)->save($savePath, 80);

        $fullImagePath = app()->make('url')->to(self::UPLOAD_PATH . $imageName);

        return $fullImagePath;

    }

    protected function uploadAWSImage($image,$folder = null)
    {
        $imageName = md5(uniqid(rand() * (time()))) . '.' . $image->getClientOriginalExtension();

        if($folder) {
            $imageName = $folder.'/'.$imageName;
        }

        Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
        $image = 'beeprotection.net/'.$imageName;
//        $fullImagePath = Storage::disk('s3')->url($image);

        return $imageName;
    }


}
