<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Resources\LinkResource;
use App\Link;
use Illuminate\Http\Request;

class LinkController
{
    public function show($code) {
        // $link = Link::whereCode($code)->first(); // this works too
        $link = Link::where('code', $code)->first();

        return new LinkResource($link);
    }
}
