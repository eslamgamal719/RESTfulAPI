<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $buyers = Buyer::has('transactions')->get();

        return $this->showAll($buyers);
    }


    public function show(Buyer $buyer)
    {
       // $buyer = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($buyer);
    }
}


