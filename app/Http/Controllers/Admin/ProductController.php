<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductCreatedRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use Gate;
use Illuminate\Http\Request;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController
{
    public function index() {
        Gate::authorize('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function show($id) {
        Gate::authorize('view', 'products');

        return new ProductResource(Product::find($id));
    }

    public function store(ProductCreatedRequest $request) {
        /**
         * First we get the image as a file from request
         * Then assign image a random name
         * then assign the randomly named image and image extension
         * to the variable $url
         *
         * The Gate is to identify what user permissions are
         */

        Gate::authorize('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id) {
        Gate::authorize('edit', 'products');

        $product = Product::find($id);
        $product->update($request->only('title', 'description', 'image', 'price'));

        return response($product, Response::HTTP_CREATED);
    }

    public function destroy($id) {
        Gate::authorize('edit', 'products');

        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
