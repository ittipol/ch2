<?php

namespace App\Models;

class Province extends Model
{
  protected $table = 'provinces';
  protected $fillable = ['name','region'];

  // private $regionCode = array(
  //   'n' => 'ภาคเหนือ',
  //   'w' => 'ภาคตะวันตก',
  //   'c' => 'ภาคกลาง',
  //   'e' => 'ภาคตะวันออก',
  //   'ne' => 'ภาคตะวันออกเฉียงเหนือ',
  //   's' => 'ภาคใต้'
  // );

  public function getProvinceByRegion() {
    $provinces = $this->orderBy('top','asc')->orderBy('id','asc')->get();

    $_provinces = array(
      'n' => array(
        'name' => 'ภาคเหนือ',
        'data' => array()
      ),
      'w' => array(
        'name' => 'ภาคตะวันตก',
        'data' => array()
      ),
      'c' => array(
        'name' => 'ภาคกลาง',
        'data' => array()
      ),
      'e' => array(
        'name' => 'ภาคตะวันออก',
        'data' => array()
      ),
      'ne' => array(
        'name' => 'ภาคตะวันออกเฉียงเหนือ',
        'data' => array()
      ),
      's' => array(
        'name' => 'ภาคใต้',
        'data' => array()
      )
    );

    foreach ($provinces as $province) {
      $_provinces[$province->region]['data'][$province->id] = $province->name;

    }

    return $_provinces;

  }

}
