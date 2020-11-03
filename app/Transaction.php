<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaction extends Model
{

    use softDeletes;

    public $transformer = TransactionTransformer::class;


    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    protected $dates = ['deleted_at'];


###################################### Relations ############################################

    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }


    public function product() {
        return $this->belongsTo(Product::class);
    }

}
