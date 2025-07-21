<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'product_sold_name',
        'quantity_sold',
        'total_amount',
        'invoice_date',
    ];
}
