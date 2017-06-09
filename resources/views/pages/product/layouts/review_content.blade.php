<div class="space-top-50">

  <h3>
    <img src="/images/icons/message-blue.png">
    รีวิวจากผู้ที่ซื้อสินค้านี้
  </h3>

  @if(Auth::check())

    @if($productBought)

      <div id="user_review" class="user-review">

        @if($hasUserReview)

          @include('pages.product.layouts.user_review')

        @else

          <div class="list-empty-message text-center space-top-20">
            <img class="not-found-image" src="/images/icons/message-blue.png">
            <div>
              <h4>ยังไม่มีรีวิวจากคุณ</h4>
              <a class="button" data-right-side-panel="1" data-right-side-panel-target="#review_panel">
                แสดงความคิดเห็นต่อสินค้านี้
              </a>
            </div>
          </div>

        @endif

      </div>

      @include('pages.product.layouts.user_review_form')

    @else

      <div class="list-empty-message text-center space-top-20">
        <img class="not-found-image" src="/images/icons/message-blue.png">
        <div>
          <h4>ยังไม่สามารถรีวิวสินค้านี้ได้จนกว่าคุณจะซื้อสินค้านี้</h4>
        </div>
      </div>

    @endif

  @endif

  <div class="row space-top-50">

    <div class="col-md-5 col-xs-12">

      <div id="review_score_list">
        @include('pages.product.layouts.review_score_list')
      </div>

    </div>

    <div class="col-md-7 col-xs-12">

      <div id="review_comment_wrapper" class="review-list space-top-20">

        

      </div>

    </div>

  </div>

</div>