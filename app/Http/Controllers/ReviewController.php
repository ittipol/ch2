<?php

namespace App\Http\Controllers;

class ReviewController extends Controller
{
  public function add() {

    $model = Service::loadModel('Review');

  }

  public function addingSubmit() {

    $model = Service::loadModel('Review');

    if($model->fill($request->all())->save()) {
      MessageHelper::display('รีวิวถูกบันทึกแล้ว','success');
      return Redirect::to('item/detail/'.$model->id);
    }else{
      return Redirect::back();
    }

  }

  public function edit() {

    $model = Service::loadModel('Review')->find($this->param['id']);

  }

  public function editingSubmit() {

    $model = Service::loadModel('Review')->find($this->param['id']);

  }

  public function delete() {

  }
}
