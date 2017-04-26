<?php

namespace App\library;

use Route;
use Schema;

class TimelineHelper {

	private $model;
	private $relatedModel = null;

	public function setModel($model) {
	  $this->model = $model;
	}

	public function setRelatedModel($model) {
	  $this->relatedModel = $model;
	}

	public function create($event,$options = array()) {

		// if(empty($this->model)) {
		//   return false;
		// }

		$timelineModel = Service::loadModel('Timeline');
		$timelineEventModel = Service::loadModel('TimelineEvent');

		$relatedModelName = null;
		if(!empty($this->relatedModel)) {
			$relatedModelName = $this->relatedModel->modelName;
		}

		$event = $timelineEventModel->where([
		  ['model','like',$this->model->modelName],
		  ['event','like',$event],
		  ['related_model','=',null]
		]);

		if(!$event->exists()) {
		  return false;
		}

		$event = $event->first();

		// check related model
		if(!empty($event->related_model) && empty($relatedModel)) {
			return false;
		}

		dd($event);
		$value = array(
		  'model' => $this->model->modelName,
		  'model_id' => $this->model->id,
		  // 'timeline_event_id' => $event->id,
		);
	}

}

?>