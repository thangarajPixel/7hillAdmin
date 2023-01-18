<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealers extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'enquiry_name',
        'state',
        'location',
    ];
}