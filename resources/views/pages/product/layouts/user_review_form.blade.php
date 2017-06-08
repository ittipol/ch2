<div id="review_panel" class="right-size-panel form">
  <div class="right-size-panel-inner">

    <h4>รีวิวสินค้า</h4>
    <div class="line"></div>

    <div id="user_review_error"></div>
    
    <?php 
      if($hasUserReview) {
        echo Form::model($userReviewFormData, [
          'id' => 'user_review_form',
          'url' => $reviewUrl,
          'method' => 'PATCH',
          'enctype' => 'multipart/form-data'
        ]);
      }else{
        echo Form::open(['id' => 'user_review_form', 'url' => $reviewUrl, 'method' => 'post', 'enctype' => 'multipart/form-data']);
      }
    ?>

    <?php
      echo Form::hidden('_model', 'Review');
      echo Form::hidden('review_model', 'Product');
      echo Form::hidden('review_model_id', $_modelData['id']);
    ?>

    <div class="form-section">

      <div class="form-row">
        <?php
          echo Form::label('score', 'คะแนนสินค้าชิ้นนี้', array(
            'class' => 'required'
          ));
        ?>

        <div class="review-score-box">

          <label class="review-score-box-item">
            <?php
              echo Form::radio('score', 1);
            ?>
            <div class="inner color-1-score">
              <div class="score-label">1</div>
            </div>
          </label>

          <label class="review-score-box-item">
            <?php
              echo Form::radio('score', 2);
            ?>
            <div class="inner color-2-score">
              <div class="score-label">2</div>
            </div>
          </label>

          <label class="review-score-box-item">
            <?php
              echo Form::radio('score', 3);
            ?>
            <div class="inner color-3-score">
              <div class="score-label">3</div>
            </div>
          </label>

          <label class="review-score-box-item">
            <?php
              echo Form::radio('score', 4);
            ?>
            <div class="inner color-4-score">
              <div class="score-label">4</div>
            </div>
          </label>

          <label class="review-score-box-item">
            <?php
              echo Form::radio('score', 5, true);
            ?>
            <div class="inner color-5-score">
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