<div class="rating-wrapper">

  <div class="space-bottom-20">
    <span id="avg_score" class="avg-review-score">{{$avgScore}}</span>
    <span> / 5 คะแนน</span>
  </div>

  @foreach($scoreList as $score => $value)
    <div id="score_bar_{{$score}}" class="rating-bar-list">
      <span class="rating-bar-label">{{$score}} คะแนน</span>
      <span class="rating-bar-item">
        <div class="rating-bar-box color-{{$score}}-score" style="width:{{$value['percent']}}%">
          <div class="rating-count-text">{{$value['count']}}</div>
        </div>
      </span>
    </div>
  @endforeach

</div>