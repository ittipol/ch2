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

    // Time
    $hours = array();
    $mins = array();

    for ($i=0; $i <= 23; $i++) { 

      if($i < 10) {
        $hours['0'.$i] = $i;
      }else{
        $hours[$i] = $i;
      }

    }

    for ($i=0; $i <= 59; $i++) {

      if($i < 10) {
        $mins['0'.$i] = '0'.$i;
      }else{
        $mins[$i] = $i;
      }
      
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

    $this->setData('hours',$hours);
    $this->setData('mins',$mins);

    return $this->view('pages.sale_promotion.form.product_discount_add');

  }

  public function addingSubmit(CustomFormRequest $request) {

    $date = new Date;

    $model = Service::loadModel('ProductDiscount');

    $today = $date->today();
    $todayTs = strtotime($today);

    $dateStartInput = $request->get('date_start');
    $dateEndInput = $request->get('date_end');

    $dateStartInputTs = strtotime($dateStartInput);
    $dateEndInputTs = strtotime($dateEndInput);

    if($dateStartInputTs < $todayTs) {
      return Redirect::back()->withErrors(['ไม่สามารถกำหนดวันที่เริ่มต้นโปรโมชั่นก่อนวันปัจจุบันได้']);
    }

    // Get Promotion dates
    // $promotionDates = Service::loadModel('ProductSalePromotion')
    // ->where('date_start','>=',$today)
    // ->where(function($query) use($dateEndInput) {
    //   $query->where('date_end','<=',$dateEndInput)
    //         ->orWhere('date_start','<=',$dateEndInput);
    // })
    // ->where('model','like',$model->modelName)
    // ->where('model_id','=',$model->id)
    // ->orderBy('date_start','ASC')
    // ->select('date_start','date_end')
    // ->get();

    // $hasError = false;
    // foreach ($promotionDates as $promotionDate) {

    //   $dateStartTs = strtotime($promotionDate->date_start);
    //   $dateEndTs = strtotime($promotionDate->date_end);

    //   if(($dateStartTs <= $dateStartInputTs) && ($dateEndTs >= $dateStartInputTs) || ($dateStartTs <= $dateEndInputTs) && ($dateEndTs >= $dateEndInputTs)) {
    //     $hasError = true;
    //   }

    //   if($hasError) {
    //     return Redirect::back()->withErrors(['ไม่สามารถกำหนดระยะเวลานี้ได้ ระยะเวลาโปรโมชั่นที่กำหนดนั้นถูกกำหนดแล้ว']);
    //   }

    // }

    $price = Service::loadModel('Product')
    ->select('price')
    ->find($this->param['product_id'])
    ->price;

    switch ($request->get('input_type')) {
        case 1:
            $reducedPrice = round($price - $request->get('reduced_price_input'),0);
            break;
        
        case 2:
            $reducedPrice = round($price - (($price * $request->get('reduced_percent_input')) / 100),0);
            break;

        default:
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

  public function edit() {

    $activePromotion = Service::loadModel('ProductSalePromotion')->getActivePromotion($this->param['product_id']);

    if(!empty($activePromotion) && ($activePromotion->model_id == $this->param['id'])) {
      Message::display('ไม่อนุญาตให้แก้ไขข้อมูลนี้ได้','error');
      return Redirect::to('shop/'.request()->shopSlug.'/product_sale_promotion/'.$this->param['product_id']);
    }

    $currency = new Currency;
    $date = new Date;

    $model = Service::loadModel('ProductDiscount')->find($this->param['id']);

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

    // Time
    $hours = array();
    $mins = array();

    for ($i=0; $i <= 23; $i++) { 

      if($i < 10) {
        $hours['0'.$i] = $i;
      }else{
        $hours[$i] = $i;
      }

    }

    for ($i=0; $i <= 59; $i++) {

      if($i < 10) {
        $mins['0'.$i] = '0'.$i;
      }else{
        $mins[$i] = $i;
      }
      
    }

    $productSalePromotion = $model->getRelatedData('ProductSalePromotion',
      array(
        'first' => true,
        'fields' => array('date_start','date_end')
      )
    );

    $dateStart = $date->explodeDateTime($productSalePromotion->date_start);
    $dateEnd = $date->explodeDateTime($productSalePromotion->date_end);

    $model->formHelper->setFormData('promotion_start_day',$dateStart['day']);
    $model->formHelper->setFormData('promotion_start_month',$dateStart['month']);
    $model->formHelper->setFormData('promotion_start_year',$dateStart['year']);
    $model->formHelper->setFormData('promotion_start_hour',$dateStart['hour']);
    $model->formHelper->setFormData('promotion_start_min',$dateStart['min']);

    $model->formHelper->setFormData('promotion_end_day',$dateEnd['day']);
    $model->formHelper->setFormData('promotion_end_month',$dateEnd['month']);
    $model->formHelper->setFormData('promotion_end_year',$dateEnd['year']);
    $model->formHelper->setFormData('promotion_end_hour',$dateEnd['hour']);
    $model->formHelper->setFormData('promotion_end_min',$dateEnd['min']);

    $this->data = $model->formHelper->build();

    $this->setData('productSalePromotion',$productSalePromotion->buildModelData());

    $this->setData('price',$price);
    $this->setData('priceWithFormat',$currency->format($price));
    $this->setData('reducedPriceWithFormat',$currency->format($model->reduced_price));

    $this->setData('day',$day);
    $this->setData('month',$month);
    $this->setData('year',$year);

    // $this->setData('currentDay',date('j'));
    // $this->setData('currentMonth',date('n'));
    // $this->setData('currentYear',$currentYear);

    $this->setData('hours',$hours);
    $this->setData('mins',$mins);

    return $this->view('pages.sale_promotion.form.product_discount_edit');

  }

  public function editingSubmit(CustomFormRequest $request) {

    $date = new Date;

    $model = Service::loadModel('ProductDiscount')->find($this->param['id']);

    $today = $date->today();
    $todayTs = strtotime($today);

    $dateStartInput = $request->get('date_start');
    $dateEndInput = $request->get('date_end');

    $dateStartInputTs = strtotime($dateStartInput);
    $dateEndInputTs = strtotime($dateEndInput);

    if($dateStartInputTs < $todayTs) {
      return Redirect::back()->withErrors(['ไม่สามารถกำหนดวันที่เริ่มต้นโปรโมชั่นก่อนวันปัจจุบันได้']);
    }

    if(filter_var($request->get('salePromotionPeriodChanged'), FILTER_VALIDATE_BOOLEAN)) {

      $promotionDates = Service::loadModel('ProductSalePromotion')
      ->where('date_start','>=',$today)
      ->where(function($query) use($dateEndInput) {
        $query->where('date_end','<=',$dateEndInput)
              ->orWhere('date_start','<=',$dateEndInput);
      })
      ->where('model','like',$model->modelName)
      ->where('model_id','=',$model->id)
      ->orderBy('date_start','ASC')
      ->select('date_start','date_end')
      ->get();

      $hasError = false;
      foreach ($promotionDates as $promotionDate) {

        $dateStartTs = strtotime($promotionDate->date_start);
        $dateEndTs = strtotime($promotionDate->date_end);

        if(($dateStartTs <= $dateStartInputTs) && ($dateEndTs >= $dateStartInputTs) || ($dateStartTs <= $dateEndInputTs) && ($dateEndTs >= $dateEndInputTs)) {
          $hasError = true;
        }

        if($hasError) {
          return Redirect::back()->withErrors(['ไม่สามารถกำหนดระยะเวลานี้ได้ ระยะเวลาโปรโมชั่นที่กำหนดนั้นถูกกำหนดแล้ว']);
        }

      }

    }

    $updateDiscount = true;
    switch ($request->get('input_type')) {
      case 1:

        if($request->get('reduced_price_input') == '') {
          $updateDiscount = false;
        }else{

          $price = Service::loadModel('Product')
          ->select('price')
          ->find($this->param['product_id'])
          ->price;

          $reducedPrice = round($price - $request->get('reduced_price_input'),0);

          $request->request->add(['reduced_price' => $reducedPrice]);
        }
        break;
      
      case 2:

        if($request->get('reduced_percent_input') == '') {
          $updateDiscount = false;
        }else{

          $price = Service::loadModel('Product')
          ->select('price')
          ->find($this->param['product_id'])
          ->price;

          $reducedPrice = round($price - (($price * $request->get('reduced_percent_input')) / 100),0);
          $request->request->add(['reduced_price' => $reducedPrice]);
        }
        break;

      default:
    }

    if(!$updateDiscount) {
      $model = $model->getRelatedData('ProductSalePromotion',array(
        'first' => true
      ));
    }

    if($model->fill($request->all())->save()) {
      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('shop/'.request()->shopSlug.'/product_sale_promotion/'.$this->param['product_id']);
    }else{
      return Redirect::back();
    }
    
  }

}
