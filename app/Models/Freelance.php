<?php

namespace App\Models;

use App\library\string;
use App\library\cache;
use App\library\url;

class Freelance extends Model
{
  public $table = 'freelances';
  protected $fillable = ['freelance_type_id','name','description','person_id'];
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
    'dataAccessPermission' => true
  );

  protected $validation = array(
    'rules' => array(
      'name' => 'required|max:255',
      'freelance_type_id' => 'required',
    ),
    'messages' => array(
      'name.required' => 'ชื่องานฟรีแลนซ์ที่รับทำห้ามว่าง',
      'freelance_type_id.required' => 'ประเภทงานฟรีแลนซ์ห้ามว่าง',
    )
  );

  public function freelanceType() {
    return $this->hasOne('App\Models\FreelanceType','id','freelance_type_id');
  }

  public function getFreelanceType() {
    return $this->freelanceType->name;
  }

  public function buildModelData() {

    $string = new String;

    return array(
      'id' => $this->id,
      'name' => $this->name,
      'description' => !empty($this->description) ? $this->description : '-',
      '_short_name' => $string->subString($this->name,60),
      '_freelanceType' => FreelanceType::select(array('name'))->find($this->freelance_type_id)->name
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
      // 'name' => $this->name,
      '_short_name' => $string->subString($this->name,90),
      '_short_description' => $string->subString($this->description,250),
      '_imageUrl' => $_imageUrl,
      '_detailUrl' => $url->setAndParseUrl('freelance/detail/{id}',array('id' => $this->id))
    );


  }

}
