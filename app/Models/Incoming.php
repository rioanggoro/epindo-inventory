<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incoming extends Model
{
  protected $fillable = ['item_name', 'quantity', 'incoming_date'];
}
