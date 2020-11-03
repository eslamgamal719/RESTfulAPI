<?php

namespace App;


use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{

    public $transformer = BuyerTransformer::class;

    // global scope
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope());
    }


###################################### Relations ############################################

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
