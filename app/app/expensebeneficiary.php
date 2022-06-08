<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class expensebeneficiary extends Model
{
    protected $fillable = [
        'id', 'name','email','phone_number', 'business_id',
    ];
}
