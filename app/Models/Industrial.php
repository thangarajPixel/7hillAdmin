<?php

namespace App\Models;

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
        'parent_id',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'sorting_order',
        'status',
        'added_by',
    ];
}
