<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;
use Session;

class Shop extends Model
{
  protected $table = 'shops';
  protected $fillable = ['name','description','brand_story','profile_image_id','cover_image_id','person_id'];
  protected $modelRelations = array('Image','Address','Contact','OfficeHour');
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
      'active' => 1
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'Contact.phone_number' => 'required|regex:/^[0-9+][0-9\-]{3,}[0-9]$/|max:255',
    ),
    'messages' => array(
      'name.required' => 'ชื่อบริษัท ร้านค้าหรือธุรกิจห้ามว่าง',
      'Contact.phone_number.regex' => 'หมายเลขโทรศัพท์ไม่ถูกต้อง',
      'Contact.phone_number.required' => 'หมายเลขโทรศัพท์ห้ามว่าง',
    )
  ); 

  public function __construct() {  
    parent::__construct();
  }

  public static function boot() {

    parent::boot();

    Shop::saving(function($model){

      if(!$model->exists){

        if($model->where([
            ['name','like',$model->name],
            ['person_id','=',Session::get('Person.id')]
          ])
          ->exists()) {
          $model->errorType = 1;
          return false;
        }elseif($model->where([
            ['name','like',$model->name],
          ])
          ->exists()) {
          $model->errorType = 2;
          return false;
        }

      }

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
          'person_id' => Session::get('Person.id'),
          'role_id' => $role->getIdByalias('admin')
        ));

      }

    });
  
  }

  public function fill(array $attributes) {

    if(!empty($attributes)) {

      if((!$this->exists) && (!empty($attributes['Contact']['phone_number']))) {

        $tel = $attributes['Contact']['phone_number'];
        unset($attributes['Contact']['phone_number']);
        $attributes['Contact']['phone_number'][0] = $tel;

      }

    }

    return parent::fill($attributes);

  }

  public function getShopName($model) {
    $shop = $model->getModelRelationData('ShopRelateTo',array(
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
      ['person_id','=',session()->get('Person.id')],
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

  public function buildModelData() {

    // $string = new String;

    return array(
      'name' => $this->name,
      // 'description' => $this->description,
      // '_short_description' => $string->subString($this->description,250,true),
      // 'profileImage' => $this->getProfileImageUrl(),
      // 'cover' => $this->getCoverUrl()
    );
  }

  public function buildPaginationData() {

    $cache = new Cache;

    $profileImage = $this->getProfileImageUrl();
    if(empty($profileImage)) {
      $profileImage = '/images/common/no-img.png';
    }

    $cover = Image::select('id','model','model_id','filename','image_type_id')->find($this->cover_image_id);

    if(!empty($cover)) {
      $cover = $cache->getCacheImageUrl($cover,'list');
    }else{
      $cover = '/images/common/no-img.png';
    }

    return array(
      'name' => $this->name,
      'profileImage' => $profileImage,
      'cover' => $cover
    );

  }

  public function buildLookupData() {

    $string = new String;
    $cache = new Cache;
    $url = new url;

    $image = $this->getModelRelationData('Image',array(
      'first' => true
    ));

    $_imageUrl = '/images/common/no-img.png';
    if(!empty($image)) {
      $_imageUrl = $cache->getCacheImageUrl($image,'list');
    }

    $slug = $this->getModelRelationData('Slug',array(
      'fields' => array('slug'),
      'first' => true
    ))->slug;

    return array(
      '_short_name' => $string->subString($this->name,90),
      '_short_description' => $string->subString($this->description,250),
      '_imageUrl' => $_imageUrl,
      '_detailUrl' => $url->url('shop/'.$slug)
    );

  }

}
