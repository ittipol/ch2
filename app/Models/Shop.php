<?php

namespace App\Models;

use App\library\stringHelper;
use App\library\cache;
use App\library\url;
use App\library\date;
use Session;

class Shop extends Model
{
  protected $table = 'shops';
  protected $fillable = ['name','description','brand_story','mission','vision','profile_image_id','cover_image_id','rating','created_by'];
  protected $modelRelations = array('Image','Address','Contact','OpenHour','Order','ShopRelateTo');
  public $errorType;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'profile-image' => array(
      'limit' => 1
    ),
    'cover' => array(
      'limit' => 1
    )
  );

  protected $behavior = array(
    'Slug' => array(
      'field' => 'name'
    ),
    'Lookup' => array(
      'format' =>  array(
        'name' => '{{name}}'
      ),
      // 'address' => true,
      'active' => 1
    ),
    'DataAccessPermission' => array(
      'owner' => 'Person',
      'defaultAccessLevel' => 99
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'Contact.phone_number' => 'required|regex:/^[0-9+][0-9\-]{3,}[0-9]$/|max:15',
    ),
    'messages' => array(
      'name.required' => 'ชื่อบริษัท ร้านค้าหรือธุรกิจห้ามว่าง',
      'Contact.phone_number.regex' => 'หมายเลขโทรศัพท์ไม่ถูกต้อง',
      'Contact.phone_number.max' => 'หมายเลขโทรศัพท์ไม่ถูกต้อง',
      'Contact.phone_number.required' => 'หมายเลขโทรศัพท์ห้ามว่าง',
    ),
    'excepts' => array(
      'shop.edit.shop_name' => array('Contact.phone_number')
    )
  );

  protected $sortingFields = array(
    'title' => 'จัดเรียงตาม',
    'options' => array(
      array(
        'name' => 'ตัวอักษร A - Z ก - ฮ',
        'value' => 'name:asc'
      ),
      array(
        'name' => 'ตัวอักษร Z - A ฮ - ก',
        'value' => 'name:desc'
      ),
      array(
        'name' => 'วันที่เก่าที่สุดไปหาใหม่ที่สุด',
        'value' => 'created_at:asc'
      ),
      array(
        'name' => 'วันที่ใหม่ที่สุดไปหาเก่าที่สุด',
        'value' => 'created_at:desc'
      ),
    ),
    'default' => 'created_at:desc'
  );

  public function __construct() {  
    parent::__construct();
  }

  public static function boot() {

    parent::boot();

    Shop::saving(function($model){

      // if($model->state == 'create') {
      //   $model->rating = 0;
      // }

      $image = new Image;

      if(!empty($model->modelRelationData['Image']['profile-image']['delete'])) {
        $image->deleteImages($model,$model->modelRelationData['Image']['profile-image']['delete']);
        $model->profile_image_id = null;
      }

      if(!empty($model->modelRelationData['Image']['profile-image']['image'])) {
 
        $imageId = $image->addImage($model,$model->modelRelationData['Image']['profile-image']['image'],array(
          'type' => 'profile-image',
          'token' => $model->modelRelationData['Image']['profile-image']['token']
        ));

        if(!empty($imageId)) {
          $model->profile_image_id = $imageId;
        }
        
        unset($model->modelRelationData['Image']['profile-image']);
      }

      if(!empty($model->modelRelationData['Image']['cover']['delete'])) {
        $image->deleteImages($model,$model->modelRelationData['Image']['cover']['delete']);
        $model->cover_image_id = null;
      }

      if(!empty($model->modelRelationData['Image']['cover']['image'])) {
        
        $imageId = $image->addImage($model,$model->modelRelationData['Image']['cover']['image'],array(
          'type' => 'cover',
          'token' => $model->modelRelationData['Image']['cover']['token']
        ));

        if(!empty($imageId)) {
          $model->cover_image_id = $imageId;
        }
        
        unset($model->modelRelationData['Image']['cover']);
      }

    });

    Shop::saved(function($model){

      if($model->state == 'create') {

        $role = new Role();

        $personToShop = new PersonToShop;
        $personToShop->saveSpecial(array(
          'shop_id' => $model->id,
          'created_by' => Session::get('Person.id'),
          'role_id' => $role->getIdByalias('admin')
        ));

      }

    });
  
  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      if(!$this->exists && (!empty($attributes['Contact']['phone_number']))) {

        $tel = $attributes['Contact']['phone_number'];
        unset($attributes['Contact']['phone_number']);
        $attributes['Contact']['phone_number'][0] = array(
          'value' => $tel
        );

      }

    }

    return parent::fill($attributes);

  }

  public function getShopName($model) {
    $shop = $model->getRelatedData('ShopRelateTo',array(
      'first' => true,
      'fields' => array('shop_id')
    ))->shop;

    return $shop->name;
  }

  public function checkPersonHasShopPermission($id = null) {

    if(empty($id)) {
      $id = $this->id;
    }

    $personToShop = new PersonToShop;

    return $personToShop->where(array(
      ['created_by','=',session()->get('Person.id')],
      ['shop_id','=',$id]
    ))->exists();

  }

  public function getRelatedShopData($modelData) {

    $shopTo = new ShopRelateTo;

    $records = $shopTo->getData(array(
      'conditions' => array(
        ['shop_id','=',$this->id],
        ['model','like',$modelData]
      ),
      'fields' => array('model_id')
    ));

    $index = lcfirst($modelData);

    $data = array();
    if(!empty($records)) {
      foreach ($records as $record) {
        $data[$record->{$index}->id] = $record->{$index}->name;
      }
    }

    return $data;
  }

  public function getRecordForParseUrl() {

    $slugModel = new Slug;
    $slug = $slugModel->where(array(
      array('model','like','Shop'),
      array('model_id','=',$this->id)
    ))->first()->slug;

    return array(
      'slug' => $slug
    );

  }

  public function getProfileImage() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    if(empty($image)) {
      return null;
    }

    return array(
      'id' => $image->id,
      '_url' => $image->getImageUrl()
    );
  }

  public function getProfileImageUrl() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    if(empty($image)) {
      return '';
    }

    return $image->getImageUrl();
  }

  public function getCover() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->cover_image_id);

    if(empty($image)) {
      return null;
    }

    return array(
      'id' => $image->id,
      '_url' => $image->getImageUrl()
    );
  }

  public function getCoverUrl() {

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->cover_image_id);

    if(empty($image)) {
      return '';
    }

    return $image->getImageUrl();
  }

  public function getOpenHours() {

    $openHours = $this->getRelatedData('OpenHour',array(
      'conditions' => array(
        array('active','=',1)
      ),
      'fields' => array('time'),
      'first' => true
    ));

    if(empty($openHours)) {
      return null;
    }

    $date = new Date;

    $openHours = json_decode($openHours->time,true);

    $today = date('N');
    $_openHours = array(
      'text' => 'วันนี้ปิด',
      'status' => 'shop-closed', 
      'timeTable' => array()
    );

    foreach ($openHours as $key => $time) {

      $startTime = explode(':', $time['start_time']);
      $endTime = explode(':', $time['end_time']);

      $_time = 'ปิด';
      if($time['open']){
        $_time = $startTime[0].':'.$startTime[1].' - '.$endTime[0].':'.$endTime[1];
      }

      if(($today == $key) && $time['open']) {
        $_openHours['text'] = 'วันนี้เปิดเวลา '.$_time;
        $_openHours['status'] = 'shop-open';
      }

      $_openHours['timeTable'][$key] = array(
        'day' => $date->getDayName($key),
        'openHour' => $_time
      );

    }

    return $_openHours;

  }

  public function getShopAbout() {
    return array(
      'description' => nl2br($this->description),
      'brand_story' => nl2br($this->brand_story),
      'mission' => nl2br($this->mission),
      'vision' => nl2br($this->vision),
    );
  }

  public function buildModelData() {

    $string = new stringHelper;

    return array(
      'name' => $this->name,
      '_short_description' => $string->truncString($this->description,250,true),
    );
  }

  public function getProductCatalogs() {
    $productCatalogs = ProductCatalog::join('shop_relate_to', 'shop_relate_to.model_id', '=', 'product_catalogs.id')
    ->where('shop_relate_to.model','like','ProductCatalog')
    ->where('shop_relate_to.shop_id','=',$this->id)
    ->take(4)
    ->orderBy('product_catalogs.created_at','desc');

    if(!$productCatalogs->exists()) {
      return null;
    }

    return $productCatalogs->get();

  }

  public function buildPaginationData() {

    $cache = new Cache;
    $string = new stringHelper;

    $profileImage = $this->getProfileImageUrl();
    if(empty($profileImage)) {
      $profileImage = null;
    }

    // $cover = Image::select('id','model','model_id','filename','image_type_id')->find($this->cover_image_id);

    // if(!empty($cover)) {
    //   $cover = $cache->getCacheImageUrl($cover,'list');
    // }else{
    //   $cover = null;
    // }

    return array(
      'name' => $this->name,
      'description' => $string->truncString($this->description,80),
      'profileImage' => $profileImage,
      // 'cover' => $cover,
      'openHours' => $this->getOpenHours()
    );

  }

  public function buildLookupData() {

    $string = new stringHelper;
    $cache = new Cache;
    $url = new url;

    $image = Image::select('id','model','model_id','filename','image_type_id')->find($this->profile_image_id);

    $_imageUrl = null;
    if(!empty($image)) {
      $_imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $slug = $this->getRelatedData('Slug',array(
      'fields' => array('slug'),
      'first' => true
    ))->slug;

    $address = $this->getRelatedData('Address',
      array(
        'first' => true,
        'fields' => array('address','province_id','district_id','sub_district_id','description','latitude','longitude'),
        'order' => array('id','DESC')
      )
    );

    $fullAddress = null;
    if(!empty($address)) {
      $fullAddress = $address->getAddress();
    }

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'data' => array(
        'address' => array(
          'value' => $fullAddress
        ),
        'openHours' => array(
          'value' => $this->getOpenHours()
        )
      ),
      'detailUrl' => $url->url('shop/'.$slug),
      'image' => $_imageUrl,
      'isDataTitle' => 'บริษัทหรือร้านค้า'
    );

  }

}
