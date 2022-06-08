<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transection_subcategory extends Model
{
    protected $table = 'transaction_subcategory';
    protected $fillable = [
        'subcategory_id', 'quantity', 'amount', 'main_category_id', 'contact_id'
    ];

}
