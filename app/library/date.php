<?php

namespace App\library;

class Date
{

  public function getToday($time = true, $timestamp = false) {

    $today = date('Y-m-d 00:00:00');
    if(!$time) {
      $today = date('Y-m-d');
    }
    
    if($timestamp) {
      return strtotime($today);
    }

    return $today;

  }

  public function getNow($time = true, $timestamp = false) {

    $now = date('Y-m-d H:i:s');
    if(!$time) {
      $now = date('Y-m-d');
    }
    
    if($timestamp) {
      return strtotime($now);
    }

    return $now;

  }

  public function covertDateToSting($date) {
    $date = explode('-', $date);
    return (int)$date[2].' '.$this->getMonthName($date[1]).' '.($date[0]+543);
  }

  public function covertDateTimeToSting($dateTime,$includeSec = false) {

    list($date,$time) = explode(' ', $dateTime);

    $date = explode('-', $date);
    $time = explode(':', $time);

    return (int)$date[2].' '.$this->getMonthName($date[1]).' '.($date[0]+543). ' เวลา '.(int)$time[0].':'.$time[1];
  }

  public function explodeDateTime($dateTime) {
    list($date,$time) = explode(' ', $dateTime);

    $date = explode('-', $date);
    $time = explode(':', $time);

    return array(
      'year' => $date[0],
      'month' => $date[1],
      'day' => $date[2],
      'hour' => $time[0],
      'min' => $time[1],
      'sec' => $time[2],
    );
  }

  public function getMonthName($month) {   

    $monthName = array(
      'มกราคม',
      'กุมภาพันธ์',
      'มีนาคม',
      'เมษายน',
      'พฤษภาคม',
      'มิถุนายน',
      'กรกฎาคม',
      'สิงหาคม',
      'กันยายน',
      'ตุลาคม',
      'พฤศจิกายน',
      'ธันวาคม',
    );

    return !empty($monthName[$month-1]) ? $monthName[$month-1] : null;

  }

  public function appendTimeForDateStartAndDateEnd($dateStart,$dateEnd) {
    return array(
      'date_start' => date('Y-m-d',strtotime($dateStart)). ' 00:00:00',
      'date_end' => date('Y-m-d',strtotime($dateEnd)). ' 23:59:59'
    );
  }

  public function setPeriodData($attributes) {

    $data = array();

    $data = array(
      'start_year' => null,
      'start_month' => null,
      'start_day' => null,
      'end_year' => null,
      'end_month' => null,
      'end_day' => null,
      'current' => null,
    );

    if(!empty($attributes['date_start'])) {
      foreach ($attributes['date_start'] as $key => $value) {
        $data['start_'.$key] = $value;
      }
    }

    if(empty($attributes['current']) && !empty($attributes['date_end'])) {
      foreach ($attributes['date_end'] as $key => $value) {
        $data['end_'.$key] = $value;
      }
    }
    elseif(!empty($attributes['current'])) {
      $data['current'] = $attributes['current'];
    }

    return $data;

  }

}
