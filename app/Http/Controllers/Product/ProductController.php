<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


class ProductController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
    }



    public function index()
    {
        $products = Product::all();

        return $this->showAll($products);
    }


    public function show(Product $product)
    {
        return $this->showOne($product);
    }


}
