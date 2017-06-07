  <div class="space-top-50">

    <h3>
      <img src="/images/icons/message-blue.png">
      รีวิวจากผู้ที่ซื้อสินค้านี้
    </h3>

    @if(Auth::check())

      @if($productBought)

      <div class="list-empty-message text-center space-top-20">
        <img class="not-found-image" src="/images/icons/message-blue.png">
        <div>
          <h4>ยังไม่มีรีวิวจากคุณ</h4>
          <a class="button" data-right-side-panel="1" data-right-side-panel-target="#review_panel">
            แสดงความคิดเห็นต่อสินค้านี้
          </a>
        </div>
      </div>

      <div id="review_panel" class="right-size-panel form">
        <div class="right-size-panel-inner">

          <h4>รีวิวสินค้า</h4>
          <div class="line"></div>
          
          <?php 
            echo Form::open(['url' => $reviewUrl, 'method' => 'post', 'enctype' => 'multipart/form-data']);
          ?>

          <div class="form-section">

            <div class="form-row">
              <?php
                echo Form::label('score', 'คะแนนสินค้าชิ้นนี้', array(
                  'class' => 'required'
                ));
              ?>

              <div class="review-score-box">

                <label class="review-score-box-item one-score-color">
                  <?php
                    echo Form::radio('review_score', 1);
                  ?>
                  <div class="inner">
                    <div class="score-label">1</div>
                  </div>
                </label>

                <label class="review-score-box-item two-score-color">
                  <?php
                    echo Form::radio('review_score', 2);
                  ?>
                  <div class="inner">
                    <div class="score-label">2</div>
                  </div>
                </label>

                <label class="review-score-box-item three-score-color">
                  <?php
                    echo Form::radio('review_score', 3);
                  ?>
                  <div class="inner">
                    <div class="score-label">3</div>
                  </div>
                </label>

                <label class="review-score-box-item four-score-color">
                  <?php
                    echo Form::radio('review_score', 4);
                  ?>
                  <div class="inner">
                    <div class="score-label">4</div>
                  </div>
                </label>

                <label class="review-score-box-item five-score-color">
                  <?php
                    echo Form::radio('review_score', 5, true);
                  ?>
                  <div class="inner">
                    <div class="score-label">5</div>
                  </div>
                </label>

              </div>
      
            </div>

            <div class="form-row">
              <?php 
                echo Form::label('title', 'หัวข้อรีวิว');
                echo Form::text('title', null, array(
                  'placeholder' => 'หัวข้อรีวิว',
                  'autocomplete' => 'off'
                ));
              ?>
            </div>

            <div class="form-row">
              <?php 
                echo Form::label('message', 'รายละเอียดรีวิว', array(
                  'class' => 'required'
                ));
                echo Form::textarea('message');
              ?>
            </div>

            <?php
              echo Form::submit('รีวิว', array(
                'class' => 'button'
              ));
            ?>

          </div>

          <?php
            echo Form::close();
          ?>

          <div class="right-size-panel-close-button"></div>
        </div>
      </div>

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
          <h4>คะแนนเฉลี่ย</h4>
          <div class="space-bottom-20">
            <span class="avg-review-score">{{$avgScore}}</span>
            <span> / 5 คะแนน</span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">5 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-5">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">4 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-4">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">3 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-3">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">2 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-2">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

          <div class="rating-bar-list">
            <span class="rating-bar-label">1 คะแนน</span>
            <span class="rating-bar-item">
              <div class="rating-bar-box score-1">
                <div class="rating-count-text">10</div>
              </div>
            </span>
          </div>

        </div>

      </div>

      <div class="col-md-6 col-xs-12">

        <div id="review_wrapper" class="review-wrapper space-top-50">

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
