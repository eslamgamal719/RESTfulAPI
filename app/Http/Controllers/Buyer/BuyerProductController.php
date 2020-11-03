<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;


class BuyerProductController extends ApiController
{

    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')
            ->get()                        //to get collection of transactions with product for each one
            ->pluck('product');      //to get product field only from each transaction and ignore the others

        return $this->showAll($products);
    }


}
