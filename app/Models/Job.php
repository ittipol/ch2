<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;

class Job extends Model
{
  public $table = 'jobs';
  protected $fillable = ['employment_type_id','name','description','qualification','benefit','salary','recruitment','recruitment_custom_detail','person_id'];
  protected $modelRelations = array('Image','Tagging','RelateToBranch','ShopRelateTo');
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
        'keyword_1' => '{{__Shop|getShopName}}',
        'keyword_2' => '{{EmploymentType.name|Job.employment_type_id=>EmploymentType.id}}',
        // 'keyword_3' => '{{__getRelatedBranch}}',
        'keyword_4' => '{{salary}}',
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
      'salary' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่อห้ามว่าง',
      'salary.required' => 'เงินเดือนห้ามว่าง',
    )
  ); 

  public function __construct() {  
    parent::__construct();
  }

  public function employmentType() {
    return $this->hasOne('App\Models\EmploymentType','id','employment_type_id');
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

    $salary = $this->salary;
    $salary = trim($salary);
    $salary = str_replace(',', '', $salary);

    preg_match_all('/[0-9]+/', $salary, $matches);

    $numbers = array();
    foreach ($matches[0] as $key => $match) {
      $salary = str_replace($match, number_format($match, 0, '.', ','), $salary);
    }

    $_salary = substr($salary, -3);
    $addBaht = true;
    for ($i=0; $i < 3; $i++) { 
      
      if((ord($_salary[$i]) < 48) || (ord($_salary[$i]) > 57)) {
        $addBaht = false;
        break;
      }

    }

    if($addBaht) {
      $salary .= ' บาท';
    }

    return $salary;

  }

  public function getRelatedBranch() {

    $branchIds = $this->getModelRelationData('RelateToBranch',array(
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

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_short_name' => $string->subString($this->name,60),
      '_salary' => $this->getSalary()
    );

  }

  public function buildModelData() {

    $this->salary = trim($this->salary);
    $this->salary = str_replace(',', '', $this->salary);

    preg_match_all('/[0-9]+/', $this->salary, $matches);

    $numbers = array();
    foreach ($matches[0] as $key => $match) {
      $this->salary = str_replace($match, number_format($match, 0, '.', ','), $this->salary);
    }

    $_salary = substr($this->salary, -3);
    $addBaht = true;
    for ($i=0; $i < 3; $i++) { 
      
      if((ord($_salary[$i]) < 48) || (ord($_salary[$i]) > 57)) {
        $addBaht = false;
        break;
      }

    }

    if($addBaht) {
      $this->salary .= ' บาท';
    }

    $recruitment = json_decode($this->recruitment,true);

    return array(
      'id' => $this->id,
      'name' => $this->name,
      '_salary' => $this->salary,
      'description' => !empty($this->description) ? $this->description : '-',
      'qualification' => !empty($this->qualification) ? $this->qualification : '-',
      'benefit' => !empty($this->benefit) ? $this->benefit : '-',
      '_recruitment_custom' => $recruitment['c'],
      'recruitment_custom_detail' => $this->recruitment_custom_detail,
      '_employmentTypeName' => $this->employmentType->name,
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

    return array(
      '_short_name' => $string->subString($this->name,90),
      '_short_description' => $string->subString($this->description,250),
      '_salary' => $this->getSalary(),
      '_detailUrl' => $url->setAndParseUrl('job/detail/{id}',array('id' => $this->id)),
      '_imageUrl' => $_imageUrl,
    );

  }

}
