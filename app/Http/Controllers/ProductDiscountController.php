<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\currency;
use App\library\date;
use Redirect;

class ProductDiscountController extends Controller
{
  public function add() {
  
    $currency = new Currency;

    $model = Service::loadModel('ProductDiscount');

    $price = Service::loadModel('Product')
    ->select('price')
    ->find($this->param['product_id'])
    ->price;

    $date = new Date;

    $currentYear = date('Y');
    
    $day = array();
    $month = array();
    $year = array();

    for ($i=1; $i <= 31; $i++) { 
      $day[$i] = $i;
    }

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    for ($i=$currentYear; $i <= $currentYear + 1; $i++) { 
      $year[$i] = $i+543;
    }

    $this->data = $model->formHelper->build();
    $this->setData('price',$price);
    $this->setData('priceWithFormat',$currency->format($price));

    $this->setData('day',$day);
    $this->setData('month',$month);
    $this->setData('year',$year);

    $this->setData('currentDay',date('j'));
    $this->setData('currentMonth',date('n'));
    $this->setData('currentYear',$currentYear);

    return $this->view('pages.sale_promotion.form.product_discount_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('ProductDiscount');

    $price = Service::loadModel('Product')
    ->select('price')
    ->find($this->param['product_id'])
    ->price;

    switch ($request->get('input_type')) {
        case 1:
            $reducedPrice = round($price - $request->get('price_text'),0);
            break;
        
        case 2:
            $reducedPrice = round($price - (($price * $request->get('percent_text')) / 100),0);
            break;

        default:
            return Redirect::back();
    }

    $request->request->add(['reduced_price' => $reducedPrice]);
    $request->request->add(['product_id' => $this->param['product_id']]);

    if($model->fill($request->all())->save()) {
      Message::display('ลงประกาศเรียบร้อยแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product_sale_promotion/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }

  }
}
