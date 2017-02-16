<?php

namespace App\library;

class Date
{
  public function covertDateToSting($date) {
    $date = explode('-', $date);
    return (int)$date[2].' '.$this->getMonthName($date[1]).' '.($date[0]+543);
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

}
