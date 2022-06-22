<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Storage;
use Str;

class ImageController
{
    public function upload(ImageUploadRequest $request) {
        if(!$request->has('image')) {
            return response()->json(['message' => 'Missing File'], 422);
        }

        $file = $request->file('image'); // get the image file from the request
        $name = Str::random(10);
        $url = Storage::putFileAs('images', $file, $name . '.' . $file->extension());

        return [
            'url' => env('APP_URL') . '/' . $url,
        ];
    }
}
