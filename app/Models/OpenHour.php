<?php

namespace App\Models;

class OpenHour extends Model
{
  protected $table = 'open_hours';
  protected $fillable = ['shop_id','same_time','time','active'];
  public $timestamps  = false;

  public $formHelper = true;

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      $officeHours = array();

      for ($i=1; $i <= 7; $i++) { 

        $data['open'] = 0;
        $data['start_time'] = '00:00:00';
        $data['end_time'] = '00:00:00';

        if(!empty($attributes['openHours'][$i])) {

          $data['open'] = $attributes['openHours'][$i]['open'];

          if(strlen($attributes['openHours'][$i]['start_time']['min']) == 1) {
            $attributes['openHours'][$i]['start_time']['min'] = '0'.$attributes['openHours'][$i]['start_time']['min'];
          }

          if(strlen($attributes['openHours'][$i]['end_time']['min']) == 1) {
            $attributes['openHours'][$i]['end_time']['min'] = '0'.$attributes['openHours'][$i]['end_time']['min'];
          }

          $data['start_time'] = $attributes['openHours'][$i]['start_time']['hour'].':'.$attributes['openHours'][$i]['start_time']['min'].':00';
          $data['end_time'] = $attributes['openHours'][$i]['end_time']['hour'].':'.$attributes['openHours'][$i]['end_time']['min'].':00';

        }

        $officeHours[$i] = $data;

      }

      $attributes['time'] = json_encode($officeHours);
      unset($attributes['openHours']);

      if(empty($attributes['same_time'])) {
        $attributes['same_time'] = 0;
      }

      if(empty($attributes['active'])) {
        $attributes['active'] = 0;
      }

    }

    return parent::fill($attributes);

  }

}
