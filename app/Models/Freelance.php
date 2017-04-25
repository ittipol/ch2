<?php

namespace App\Models;

use App\library\currency;
use App\library\string;
use App\library\cache;
use App\library\url;

class Freelance extends Model
{
  public $table = 'freelances';
  protected $fillable = ['freelance_type_id','name','default_wage','description','person_id'];
  protected $modelRelations = array('Image','Tagging');
  protected $directory = true;

  public $formHelper = true;
  public $modelData = true;
  public $paginator = true;

  public $imageTypes = array(
    'photo' => array(
      'limit' => 10
    )
  );

  protected $behavior = array(
    'Lookup' => array(
      'format' =>  array(
        'name' => '{{name}}',
        'keyword_1' => '{{__getFreelanceType}}'
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Person',
      'defaultAccessLevel' => 99
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'default_wage' => 'required|regex:/^[0-9,]*(\.[0-9]{1,2})?$/|max:255',
      'freelance_type_id' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่องานฟรีแลนซ์ที่รับทำห้ามว่าง',
      'price.required' => 'อัตราค่าจ้างเริ่มต้นห้ามว่าง',
      'price.regex' => 'รูปแบบอัตราค่าจ้างเริ่มต้นไม่ถูกต้อง',
      'freelance_type_id.required' => 'ประเภทงานฟรีแลนซ์ห้ามว่าง',
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

  public function freelanceType() {
    return $this->hasOne('App\Models\FreelanceType','id','freelance_type_id');
  }

  public function getFreelanceType() {
    return $this->freelanceType->name;
  }

  public function buildPaginationData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,45),
    );
    
  }

  public function buildModelData() {

    $string = new String;
    $currency = new Currency;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      'defaultWage' => $currency->format($this->default_wage),
      '_short_name' => $string->truncString($this->name,60),
      '_freelanceType' => FreelanceType::select(array('name'))->find($this->freelance_type_id)->name
    );
    
  }

  public function buildLookupData() {

    $string = new String;
    $url = new url;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'data' => array(
        'freelanceType' => array(
          'title' => 'ประเภทงานฟรีแลนซ์',
          'value' => $this->freelanceType->name
        )
      ),
      'detailUrl' => $url->setAndParseUrl('freelance/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'งานฟรีแลนซ์'
    );

  }

}
