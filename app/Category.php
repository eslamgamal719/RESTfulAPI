<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use softDeletes;

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = ['pivot'];

    protected $dates = ['deleted_at'];





###################################### Relations ############################################

    public function products() {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
