  <div class="space-top-50">
    <h3>
      <img src="/images/icons/message-blue.png">
      รีวิวจากลูกค้า
    </h3>
    <!-- <div class="line"></div> -->

    <div class="row">
      <div class="col-md-6 col-xs-12">

        <div class="form-section">

          <div class="form-row">
            <?php
              echo Form::label('score', 'คะแนนสินค้าชิ้นนี้', array(
                'class' => 'required'
              ));
            ?>
            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">1</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">2</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">3</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">4</div>
            </label>

            <label class="choice-box review-score-box">
              <?php
                echo Form::radio('review_score', null);
              ?>
              <div class="inner">5</div>
            </label>
          </div>

          <div class="form-row">
            <?php 
              echo Form::label('title', 'หัวข้อรีวิว');
              echo Form::text('title', null, array(
                'placeholder' => 'ชื่อสินค้า',
                'autocomplete' => 'off'
              ));
            ?>
          </div>

          <div class="form-row">
            <?php 
              echo Form::label('description', 'รายละเอียดรีวิว', array(
                'class' => 'required'
              ));
              echo Form::textarea('description');
            ?>
          </div>

          <?php
            echo Form::submit('ส่งรีวิว', array(
              'class' => 'button'
            ));
          ?>

        </div>

      </div>
    </div>

    <div class="row space-top-50">

      <div class="col-md-4 col-xs-12">

        <div class="rating-wrapper">
          <h4>คะแนนเฉลี่ย</h4>
          <div class="space-bottom-20">
            <span class="avg-review-score">4.9</span>
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
