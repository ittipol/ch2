<?php

namespace App\Models;

use App\library\stringHelper;
use App\library\cache;
use App\library\url;

class Job extends Model
{
  public $table = 'jobs';
  protected $fillable = ['employment_type_id','name','description','qualification','benefit','wage','career_type_id','recruitment','recruitment_custom_detail','created_by'];
  protected $modelRelations = array('Image','Tagging','ShopRelateTo','RelateToBranch','TargetArea');
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
        'keyword_1' => '{{EmploymentType.name|Job.employment_type_id=>EmploymentType.id}}',
        // 'keyword_3' => '{{__getRelatedBranch}}',
        'keyword_4' => '{{wage}}',
      )
    ),
    'DataAccessPermission' => array(
      'owner' => 'Shop',
      'defaultAccessLevel' => 99
    )
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'wage' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่อห้ามว่าง',
      'wage.required' => 'อัตราค่าจ้างห้ามว่าง',
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

    Job::saving(function($job){

      if($job->state == 'create') {
        $job->active = 1;
      }

    });

  }

  public function employmentType() {
    return $this->hasOne('App\Models\EmploymentType','id','employment_type_id');
  }

  public function careerType() {
    return $this->hasOne('App\Models\CareerType','id','career_type_id');
  }


  public function fill(array $attributes) {
    
    if(!empty($attributes)) {

      $attributes['recruitment'] = json_encode(array(
        's' => '1',
        'c' => !empty($attributes['recruitment_custom']) ? '1' : '0'
      ));

      if(empty($attributes['recruitment_custom'])) {
        $attributes['recruitment_custom_detail'] = null;
      }

      unset($attributes['recruitment_custom']);

    }

    return parent::fill($attributes);
  }

  public function getSalary() {

    $wage = $this->wage;
    $wage = trim($wage);
    $wage = str_replace(',', '', $wage);

    preg_match_all('/[0-9]+/', $wage, $matches);

    $numbers = array();
    foreach ($matches[0] as $key => $match) {
      $wage = str_replace($match, number_format($match, 0, '.', ','), $wage);
    }

    $addBaht = true;
    if(strlen($wage) > 3) {

      $_wage = substr($wage, -3);
      for ($i=0; $i < 3; $i++) { 
        
        if((ord($_wage[$i]) < 48) || (ord($_wage[$i]) > 57)) {
          $addBaht = false;
          break;
        }

      }

    }

    if($addBaht) {
      $wage .= ' บาท';
    }

    return $wage;

  }

  public function getRelatedBranch() {

    $branchIds = $this->getRelatedData('RelateToBranch',array(
      'list' => 'branch_id',
      'fields' => array('branch_id'),
    ));

    $branches = array();
    if(!empty($branchIds)){
      $branches = Branch::select(array('name'))
      ->whereIn('id',$branchIds)
      ->get();
    }

    $branchNames = array();
    foreach ($branches as $branch) {
      $branchNames[] = $branch->name;
    }

    return implode(' ', $branchNames);

  }

  public function buildPaginationData() {

    $string = new stringHelper;

    // get Company name
    $shopRelateTo = $this->getRelatedData('ShopRelateTo',array(
      'first' => true,
    ));

    $shopName = null;
    if(!empty($shopRelateTo)) {
      $shopName = $shopRelateTo->shop->name;
    }

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->truncString($this->name,60),
      '_wage' => $this->getSalary(),
      'shopName' => $shopName
    );

  }

  public function buildModelData() {

    $this->wage = $this->getSalary($this->wage);

    $recruitment = json_decode($this->recruitment,true);

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_wage' => $this->wage,
      'description' => !empty($this->description) ? nl2br($this->description) : '-',
      'qualification' => !empty($this->qualification) ? nl2br($this->qualification) : '-',
      'benefit' => !empty($this->benefit) ? nl2br($this->benefit) : '-',
      '_recruitment_custom' => $recruitment['c'],
      'recruitment_custom_detail' => nl2br($this->recruitment_custom_detail),
      '_employmentTypeName' => $this->employmentType->name,
      '_careerType' => $this->careerType->name,
      'active' => $this->active,
    );

  }

  public function buildLookupData() {

    $string = new stringHelper;
    $url = new url;

    return array(
      'title' => $string->truncString($this->name,90),
      'description' => $string->truncString($this->description,250),
      'data' => array(
        'wage' => array(
          'title' => 'อัตราค่าจ้าง',
          'value' => $this->getSalary()
        ),
        'employmentType' => array(
          'title' => 'รูปแบบการจ้างงาน',
          'value' => $this->employmentType->name
        )
      ),
      'detailUrl' => $url->setAndParseUrl('job/detail/{id}',array('id' => $this->id)),
      'image' => $this->getImage('list'),
      'isDataTitle' => 'ประกาศงาน'
    );

  }

}
