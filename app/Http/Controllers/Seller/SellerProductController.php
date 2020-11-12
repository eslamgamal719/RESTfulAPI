<?php

namespace App\Http\Controllers\Seller;

use App\Transformers\ProductTransformer;
use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class SellerProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:' . ProductTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-products')->except('index');

        $this->middleware('can:view,seller')->only('index');
        $this->middleware('can:sale,seller')->only('store');
        $this->middleware('can:edit-product,seller')->only('update');
        $this->middleware('can:delete-product,seller')->only('destroy');
    }


    public function index(Seller $seller)
    {
        if(request()->user()->tokenCan('read-general') || request()->user()->tokenCan('manage-products')) {

            $products = $seller->products;

            return $this->showAll($products);
        }

        throw new AuthorizationException('Invalid Scope(s)');

    }//end of index


    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');        //store('path', 'disk')
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);

    }//end of store




    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [

            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::UNAVAILABLE_PRODUCT . ',' . Product::AVAILABLE_PRODUCT,
            'image' => 'image'

        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);

        $product->fill($request->only([

            'name', 'description', 'quantity'

        ]));

        if($request->has('status')) {

            $product->status = $request->status;

            if($product->isAvailable() && $product->categories()->count() == 0) {

                return $this->errorResponse("An active product must have at least one category", 409);

            }
        }

        if($request->has('image')) {

            Storage::delete($product->image);

            $product->image = $request->image->store('');

        }

        if($product->isClean()) {

            return $this->errorResponse("You need to specify a different value to update", 422);

        }

        $product->save();

        return $this->showOne($product);

    }//end of update




    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->delete();

        Storage::delete($product->image);

        return $this->showOne($product);

    }//end of destroy





    public function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id) {

            throw new HttpException(422, "The specified seller is not the actual seller of the product");

        }

    }//end of check seller





}//end of controller
