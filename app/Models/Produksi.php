<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
   protected $fillable = [
        'product_name',
        'quantity_produced',
        'raw_material_used',
        'production_date',
        'raw_material_item_name'
    ];
}
