<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index(Buyer $buyer)
    {
        $this->allowedAdminAction();

        $sellers = $buyer->transactions()->with('product.seller')
                         ->get()
                         ->pluck('product.seller')
                         ->unique('id')->values();

        return $this->showAll($sellers);
    }


}
