<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industrial extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id',
        'title',
        'slug',
        'image',
        'icon',
        'parent_id',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'sorting_order',
        'status',
        'added_by',
    ];

    public function parent()
    {
        return $this->hasOne(Industrial::class,'id','parent_id') ;
    }

    public function childOnlyNames()
    {
        return $this->hasMany(Industrial::class, 'parent_id', 'id');
    }
    public function parentOnlyName()
    {
        return $this->hasMany(ProductCategory::class,'industrial_id','id');
    }
    public function userInfo()
    {
        return $this->hasOne(User::class,'id','added_by');
    }
    
    public function childCategory()
    {
        return $this->hasMany(ProductCategory::class, 'industrial_id', 'id')->where('parent_id', 0);
    }
    public function selectOption()
    {
       return $this->hasOne(Industrial::class,'id','parent_id')->select('title');
    }
}
