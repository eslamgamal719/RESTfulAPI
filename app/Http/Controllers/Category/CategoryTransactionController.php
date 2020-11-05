<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions')    //to take products that has transactions only
            ->with('transactions')       //to get these transactions with each product
            ->get()
            ->pluck('transactions')
            ->collapse();

        return $this->showAll($transactions);
    }


}
