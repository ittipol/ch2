@if(!empty($errors->all()))
  <div class="form-error-messages">
    <div class="form-error-messages-inner">
      <h3>พบข้อผิดพลาด!!!</h3>
        <ul>
        <?php foreach ($errors->all() as $message) { ?>
          <li class="error-messages"><?php echo $message; ?></li>
        <?php } ?>
      </ul>
    </div>
  </div>
@endif