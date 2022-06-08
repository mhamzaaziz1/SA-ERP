<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sub_category extends Model
{
    protected $fillable = [
        'id', 'name', 'business_id', 'category_id'
    ];
}
