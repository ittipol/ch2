@foreach($reviews as $review)
<div class="review-item">
  <div class="review-user-avatar" style="background-image:url({{$review['personProfileImage']}});"></div>
  <div class="review-content">
    <div class="review-by"><strong>{{$review['personName']}}</strong> {{$review['updatedDate']}}</div>
    <div class="review-rating-score">{{$review['score']}} คะแนน</div>
    <div class="review-description">
      @if(!empty($review['title']))
      <span class="review-title">{{$review['title']}}</span>
      @endif
      {!!$review['message']!!}
    </div>
  </div>
</div>
<div class="line space-bottom-20"></div>
@endforeach

@if($next)
  <div>
    <a id="more_review_btn" class="button wide-button">แสดงเพิ่ม</a>
  </div>
@endif