<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaction_maincategory extends Model
{
   protected $table = 'transaction_maincategory';
    protected $fillable = [
        'category_id', 'transaction_id'
    ];
}
