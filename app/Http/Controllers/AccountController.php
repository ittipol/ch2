<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomFormRequest;
use App\library\service;
use App\library\message;
use App\library\date;
use App\library\url;
use Redirect;
use Session;

class AccountController extends Controller
{
  public function account() {

    $profile = Service::loadModel('Person')->find(Session::get('Person.id'));

    $profile->modelData->loadData(array(
      'models' => array('Address','Contact')
    ));

    $this->setData('profile',$profile->modelData->build(true));
    $this->setData('profileImageUrl',$profile->getProfileImageUrl());

    return $this->view('pages.account.manage');
  }

  public function profileEdit() {

    $model = Service::loadModel('Person')->find(Session::get('Person.id'));

    $model->formHelper->loadFieldData('Province',array(
      'key' =>'id',
      'field' => 'name',
      'index' => 'provinces',
      'order' => array(
        array('top','ASC'),
        array('id','ASC')
      )
    ));

    $date = new Date;

    $latestYear = date('Y');
    
    $day = array();
    $month = array();
    $year = array();

    for ($i=1; $i <= 31; $i++) { 
      $day[$i] = $i;
    }

    for ($i=1; $i <= 12; $i++) { 
      $month[$i] = $date->getMonthName($i);
    }

    for ($i=1957; $i <= $latestYear; $i++) { 
      $year[$i] = $i+543;
    }

    $model->formHelper->loadData(array(
      'model' => array(
        'Address','Contact'
      )
    ));

    $this->data = $model->formHelper->build();
    $this->setData('profileImage',json_encode($model->getProfileImage()));
    $this->setData('day',$day);
    $this->setData('month',$month);
    $this->setData('year',$year);

    return $this->view('pages.account.form.profile_edit');

  }

  public function profileEditingSubmit(CustomFormRequest $request) {

    $model = Service::loadModel('Person')->find(Session::get('Person.id'));

    if($model->fill($request->all())->save()) {

      Session::put('Person.name',$model->name);
      Session::put('Person.theme',$model->theme);
      Session::put('Person.profile_image',$model->getProfileImageUrl());

      Message::display('ข้อมูลถูกบันทึกแล้ว','success');
      return Redirect::to('account');
    }else{
      return Redirect::back();
    }

  }

  public function theme() {

    // Get Theme List
    $themes = array(
      'red' => '#E53935',
      'pink' => '#D81B60',
      'purple' => '#8E24AA',
      'deep-purple' => '#5E35B1',
      'indigo' => '#3949AB',
      'blue' => '#1E88E5',
      'light-blue' => '#039BE5',
      'cyan' => '#00ACC1',
      'teal' => '#00897B',
      'green' => '#43A047',
      'light-green' => '#7CB342',
      'yellow' => '#FDD835',
      'amber' => '#FFB300',
      'orange' => '#FB8C00',
      'deep-orange' => '#F4511E',
      'brown' => '#6D4C41',
      'grey' => '#757575',
      'blue-grey' => '#546E7A'
    );

    $this->setData('themes',$themes);

    return $this->view('pages.account.theme');

  }

  public function item() {

    $model = Service::loadModel('Item');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->myData();
    $model->paginator->criteria(array(
      'order' => array(
        // array('name','ASC'),
        array('created_at','DESC')
      )
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('account/item');
    $model->paginator->setUrl('item/detail/{id}','detailUrl');
    $model->paginator->setUrl('account/item_edit/{id}','editUrl');
    $model->paginator->setUrl('account/item_delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.account.item');

  }

  public function realEstate() {

    $model = Service::loadModel('RealEstate');

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model->paginator->myData();
    $model->paginator->criteria(array(
      'order' => array(
        array('id','DESC')
      )
    ));
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('account/real_estate');
    $model->paginator->setUrl('real-estate/detail/{id}','detailUrl');
    $model->paginator->setUrl('account/real_estate_edit/{id}','editUrl');
    $model->paginator->setUrl('account/real_estate_delete/{id}','deleteUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.account.real_estate');

  }

  public function shop() {

    $personToShop = $model = Service::loadModel('PersonToShop')
    ->getData(array(
      'conditions' => array(
        array('Person_id','=',session()->get('Person.id'))
      ),
      'fields' => array('shop_id'),
      'list' => 'shop_id'
    ));

    $page = 1;
    if(!empty($this->query['page'])) {
      $page = $this->query['page'];
    }

    $model = Service::loadModel('Shop');

    $model->paginator->criteria(array(
      'conditions' => array(
        'in' => array(
          array('id',$personToShop)
        )
      )
    ));
    $model->paginator->disableGetImage();
    $model->paginator->setPage($page);
    $model->paginator->setPagingUrl('account/shop');
    $model->paginator->setUrl('shop/{slug}','shopUrl');
    $model->paginator->setUrl('shop/{slug}/manage','shopManageUrl');
    $model->paginator->setUrl('shop/{slug}/setting','shopSettingUrl');

    $this->data = $model->paginator->build();

    return $this->view('pages.account.shop');

  }

}
