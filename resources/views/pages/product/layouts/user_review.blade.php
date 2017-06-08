<div class="user-review">

  <h4>รีวิวของคุณ</h4>
  <div class="row">
    <div class="col-md-4 col-xs-12">
      <div class="text-center space-top-20">
        <div class="user-review-score color-{{$userReview['score']}}-score">{{$userReview['score']}}</div>
        <div class="space-top-20"><strong>รีวิวเมื่อ</strong> {{$userReview['createdDate']}}</div>
      </div>

      <div class="additional-option">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="additional-option-content">
          <a data-right-side-panel="1" data-right-side-panel-target="#review_panel">แก้ไข</a>
          <a href="" data-modal="1" data-modal-title="ต้องการลบรีวิวใช่หรือไม่">ลบ</a>
        </div>
      </div>

    </div>
    <div class="col-md-8 col-xs-12">
      <div class="review-description">
        <span class="review-title">{{$userReview['title']}}</span>
        {!!$userReview['message']!!}
      </div>
    </div>
  </div>

</div>

<div class="line space-top-50"></div>