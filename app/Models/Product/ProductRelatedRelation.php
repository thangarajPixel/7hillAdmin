<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRelatedRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_product_id',
        'to_product_id'
    ];
    
}
