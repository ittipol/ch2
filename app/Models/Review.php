<?php

namespace App\Models;

class Review extends Model
{
  public $table = 'reviews';
  protected $fillable = ['model','model_id','title','message','score','created_by'];
  protected $modelRelations = array('Product');

  private $fullScore = 5;
}
