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

    <div class="col-md-4 col-xs-12">

      <div class="rating-wrapper">

        <div class="space-bottom-20">
          <span class="avg-review-score">{{$avgScore}}</span>
          <span> / 5 คะแนน</span>
        </div>

        @foreach($scoreList as $score => $value)
          <div class="rating-bar-list">
            <span class="rating-bar-label">{{$score}} คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box color-{{$score}}-score" style="width:{{$value['percent']}}%">
                <div class="rating-count-text">{{$value['count']}}</div>
              </div>
            </span>
          </div>
        @endforeach

      </div>

    </div>

    <div class="col-md-6 col-xs-12">

      <div id="review_wrapper" class="review-wrapper space-top-20">

        <div class="review-content">
          <div class="review-by">xxx yyy</div>
          <div class="review-rating-score">5 คะแนน</div>
          <div class="review-description">
            <span class="review-title">Title</span>
            Lorem Ipsum คือ เนื้อหาจำลองแบบเรียบๆ ที่ใช้กันในธุรกิจงานพิมพ์หรืองานเรียงพิมพ์ มันได้กลายมาเป็นเนื้อหาจำลองมาตรฐานของธุรกิจดังกล่าวมาตั้งแต่ศตวรรษที่ 16 เมื่อเครื่องพิมพ์โนเนมเครื่องหนึ่งนำรางตัวพิมพ์มาสลับสับตำแหน่งตัวอักษรเพื่อทำหนังสือตัวอย่าง Lorem Ipsum อยู่ยงคงกระพันมาไม่ใช่แค่เพียงห้าศตวรรษ แต่อยู่มาจนถึงยุคที่พลิกโฉมเข้าสู่งานเรียงพิมพ์ด้วยวิธีทางอิเล็กทรอนิกส์ และยังคงสภาพเดิมไว้อย่างไม่มีการเปลี่ยนแปลง มันได้รับความนิยมมากขึ้นในยุค ค.ศ. 1960 เมื่อแผ่น Letraset วางจำหน่ายโดยมีข้อความบนนั้นเป็น Lorem Ipsum และล่าสุดกว่านั้น คือเมื่อซอฟท์แวร์การทำสื่อสิ่งพิมพ์ (Desktop Publishing) อย่าง Aldus PageMaker ได้รวมเอา Lorem Ipsum เวอร์ชั่นต่างๆ เข้าไว้ในซอฟท์แวร์ด้วย
          </div>
        </div>

      </div>

    </div>

  </div>

</div>