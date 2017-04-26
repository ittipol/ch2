<?php

namespace App\Models;

class TimelineEvent extends Model
{
  protected $table = 'timeline_events';
  protected $fillable = ['model','related_model','event','title_format','message_format'];

  // public function getTimelineTitleFormatByAlias() {

  //   return $this->getData(array(
  //     'conditions' => array(
  //       ['alias','like',$alias]
  //     ),
  //     'first' => true
  //   ));

  // }

}
