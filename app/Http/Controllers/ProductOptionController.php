<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\messageHelper;
use Redirect;

class ProductOptionController extends Controller
{
  public function add() {

    $model = Service::loadModel('ProductDiscount');

    $this->data = $model->formHelper->build();

    $this->setPageTitle('เพิ่มตัวเลือกสินค้า');

    return $this->view('pages.product_option.form.product_option_add');

  }
}
