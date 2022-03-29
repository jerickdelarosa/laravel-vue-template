<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
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
        /* dd('file name: ', $filename); */

        $hashed_filename = md5($filename) . ".jpg";

        /* dd($hashed_filename); */

        $image = Image::make($request->image)->encode('jpeg');

        $stored = Storage::put("/uploads/images/photos/{$hashed_filename}", $image);

        if ($stored) {
            Photo::create([
                'photo' => $hashed_filename
            ]);
        }

        return [
            'status' => $stored
        ];
    }
}
