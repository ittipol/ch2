<?php

namespace App\Models;

use App\library\service;
use App\library\date;

class Timeline extends Model
{
  protected $table = 'timelines';
  protected $fillable = ['model','model_id','message','pinned','timeline_post_type_id','related','related_id','created_by'];

  public function getPinnedMessage($modelName,$modelId) {

    // Service::loadModel('TimelinePostType')->getIdByalias('text')

    $pinnedMessages = $this
    ->where([
      ['model','like',$modelName],
      ['model_id','=',$modelId],
      ['pinned','=',1],
    ])
    ->orderBy('created_at','desc');

    if(!$pinnedMessages->exists()) {
      return null;
    }

    return $pinnedMessages->get();

  }

  public function getOwnerName() {
    
    $name = '';
    switch ($this->model) {
      case 'Person':
        $name = Person::select('name')->find($this->model_id)->name;
        break;
      
      case 'Shop':
        $name = Shop::select('name')->find($this->model_id)->name;
        break;
    }

    return $name;

  }

  public function buildModelData() {

    $date = new Date;

    $relatedData = null;
    if(!empty($this->related) && !empty($this->related)) {

      $model = Service::loadModel($this->related)->find($this->related_id);

      if(!empty($model) && method_exists($model,'buildTimelineData')) {
        $relatedData = $model->buildTimelineData();
      }
      
    }

    return array(
      'id' => $this->id,
      'owner' => $this->getOwnerName(),
      'title' => $this->title,
      'message' => nl2br($this->message),
      'createdDate' => $date->calPassedDate($this->created_at->format('Y-m-d H:i:s')),
      'relatedData' => $relatedData 
    );

  }
}
