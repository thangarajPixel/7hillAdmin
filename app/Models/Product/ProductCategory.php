<?php

namespace App\Models\Product;

use App\Models\CategoryMetaTags;
use App\Models\Industrial;
use App\Models\Settings\Tax;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'industrial_id',
        'description',
        'image',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'status',
        'order_by',
        'added_by',
        'updated_by',
    ];

    public function meta()
    {
        return $this->hasOne(CategoryMetaTags::class, 'category_id', 'id');
    }

    // public function tax()
    // {
    //     return $this->hasOne(Tax::class, 'id', 'tax_id');
    // }

    public function userInfo()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function parent()
    {
        return $this->belongsTo(Industrial::class, 'industrial_id', 'id');
    }
    public function categoryParentData()
    {
        return $this->hasOne(Industrial::class, 'id', 'industrial_id');
    }

    public function parentIndustry()
    {
        return $this->belongsTo(Industrial::class, 'industrial_id', 'id');
    }

    
}
