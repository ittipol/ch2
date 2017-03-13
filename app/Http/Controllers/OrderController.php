<?php

namespace App\Http\Controllers;

use App\library\service;

class OrderController extends Controller
{
  public function detail() {

    $order = Service::loadModel('Order')->where(
      ['id','=',$this->param['id']],
      ['person_id','=',session()->get('Person.id')]
    );

    dd($order);

  }
}
