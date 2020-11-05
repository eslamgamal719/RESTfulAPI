<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()
            ->with('product.categories')  //nested relations
            ->get()
            ->pluck('product.categories')
            ->collapse()                                 //to make all categories arrays in one array
            ->unique('id')->values();               //to avoid category from duplicate

        return $this->showAll($sellers);
    }


}
