<?php

namespace App\Models;

use App\library\date;

class Review extends Model
{
  public $table = 'reviews';
  protected $fillable = ['model','model_id','title','message','score','created_by'];
  protected $modelRelations = array('Product');

  private $fullScore = 5;

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
      1 => array(
        'percent' => 0,
        'count' => 0
      ),
      2 => array(
        'percent' => 0,
        'count' => 0
      ),
      3 => array(
        'percent' => 0,
        'count' => 0
      ),
      4 => array(
        'percent' => 0,
        'count' => 0
      ),
      5 => array(
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
      'description' => !empty($this->description) ? nl2br($this->description) : null,
      'score' => $this->scoreFormat(),
      'createdDate' => $date->covertDateTimeToSting($this->created_at->format('Y-m-d H:i:s'))
    );

  }

}
