<?php

namespace App\Models;

class StockStatus extends Model
{
  protected $table = 'stock_statuses';
  protected $fillable = ['name'];
  public $timestamps  = false;
}
