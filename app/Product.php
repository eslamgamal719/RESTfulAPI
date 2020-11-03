<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{

    use softDeletes;

    public $transformer = ProductTransformer::class;


    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';


    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    protected $hidden = ['pivot'];


    protected $dates = ['deleted_at'];


###################################### Functions ############################################

    public function isAvailable()   //return true or false
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }



###################################### Relations ############################################
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }


    public function transactions() {
        return $this->hasMany(Transaction::class);
    }


    public function categories() {
        return $this->belongsToMany(Category::class, 'category_product');
    }

}
