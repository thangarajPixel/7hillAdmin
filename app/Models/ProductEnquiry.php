<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductEnquiry extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'product_id',
        'email',
        'mobile',
        'company_name',
        'city',
        'status',
    ];
    public function productData()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
