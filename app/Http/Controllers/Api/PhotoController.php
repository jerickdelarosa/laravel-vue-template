<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __invoke(Request $request, $image)
    {
        $image = Storage::get('/uploads/images/photos/{$image}');

        return response($image)
            ->header('Content-Type', 'image/jpeg');
    }

    public function uploadPhoto(PhotoRequest $request)
    {
        $stored = false;

        $filename = "APPLICANT_test_" . strtotime(now());
        $hashed_filename = md5($filename) . ".jpg";

        $image = Image::make($request->image)->encode('jpeg');

        $stored = Storage::put("/uploads/images/photos/{$hashed_filename}", $image);

        if ($stored) {
            $request->update([
                'photo' => $hashed_filename
            ]);
        }

        return [
            'status' => $stored
        ];
    }
}
