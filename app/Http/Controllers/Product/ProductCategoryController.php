<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('client.credentials')->only(['index']);
    }


    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);

    }//end of index



    public function update(Request $request, Product $product, Category $category)
    {
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);

    }//end of update



    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)) {

            return $this->errorResponse("The specified category is not the category of this product", 404);

        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories);

    }//end of destroy



}//end of controller
