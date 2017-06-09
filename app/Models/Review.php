<?php

namespace App\Models;

use App\library\date;

class Review extends Model
{
  public $table = 'reviews';
  protected $fillable = ['model','model_id','title','message','score','created_by'];
  protected $modelRelations = array('Product');

  private $fullScore = 5;

  protected $validation = array(
    'rules' => array(
      'title' => 'max:255',
      'message' => 'required',
      'score' => 'required|numeric',
    ),
    'messages' => array(
      'title.max' => 'หัวข้อรีวิวจำนวนตัวอักษรมากกว่าที่รองรับ',
      'message.required' => 'รายละเอียดรีวิวห้ามว่าง',
      'score.required' => 'คะแนนห้ามว่าง',
      'score.numeric' => 'รูปแบบคะแนนไม่ถูกต้อง',
    )
  );

  public function hasUserReview($model,$personId) {
    return $this
    ->select('id')
    ->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id],
      ['created_by','=',$personId]
    ])->exists();
  }

  public function getUserReview($model,$personId) {

    $userReview = $this
    ->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id],
      ['created_by','=',$personId]
    ]);

    if($userReview->exists()) {
      return $userReview->first();
    }

    return null;

  }

  public function getAvgScore($model) {
    return $this->scoreFormat(number_format($this->select('score')->where([['model','like',$model->modelName],['model_id','=',$model->id]])->avg('score'),1));
  }

  public function getScoreList($model) {

    $reviews = $this->select('score')
    ->where([
      ['model','like',$model->modelName],
      ['model_id','=',$model->id]
    ]);

    $scoreList = array(
      5 => array(
        'percent' => 0,
        'count' => 0
      ),
      4 => array(
        'percent' => 0,
        'count' => 0
      ),
      3 => array(
        'percent' => 0,
        'count' => 0
      ),
      2 => array(
        'percent' => 0,
        'count' => 0
      ),
      1 => array(
        'percent' => 0,
        'count' => 0
      )
    );

    if(!$reviews->exists()) {
      return $scoreList;
    }

    $total = $reviews->count();

    foreach ($reviews->get() as $review) {
      list($integer,$point) = explode('.', $review->score);
      $scoreList[$integer]['count']++;
    }

    foreach ($scoreList as $key => $score) {

      $scoreList[$key]['percent'] = ($score['count'] * 100) / $total;

      $scoreList[$key] = array(
        'percent' => ($score['count'] * 100) / $total,
        'count' => number_format($score['count'], 0, '.', ',')
      );

    }

    return $scoreList;

  }

  public function scoreFormat($score = null) {

    if(empty($score) && !empty($this->score)) {
      $score = $this->score;
    }

    if(!strpos($score, '.')) {
      return $score;
    }

    list($integer,$point) = explode('.', $score);

    if((int)$point == 0) {
      return $integer;
    }

    return $score;

  }

  public function buildModelData() {

    $date = new Date;

    return array(
      'title' => $this->title,
      'message' => !empty($this->message) ? nl2br($this->message) : null,
      'score' => $this->scoreFormat(),
      // 'createdDate' => $date->covertDateTimeToSting($this->created_at->format('Y-m-d H:i:s')),
      'updatedDate' => $date->covertDateTimeToSting($this->updated_at->format('Y-m-d H:i:s'))
    );

  }

  public function buildPaginationData() {

    $date = new Date;

    // Get Person name & avatar
    $person = Person::find($this->created_by);

    $personName = null;
    if(!empty($person->name)) {
      $personName = $person->name;
    }

    $personProfileImage = $person->getProfileImageUrl('xs');
    if(empty($personProfileImage)) {
      $personProfileImage = '/images/common/avatar.png';
    }

    return array(
      'title' => $this->title,
      'message' => !empty($this->message) ? nl2br($this->message) : null,
      'score' => $this->scoreFormat(),
      'personName' => $personName,
      'personProfileImage' => $personProfileImage,
      'updatedDate' => $date->calPassedDate($this->updated_at->format('Y-m-d H:i:s'))
    );

  }

}
