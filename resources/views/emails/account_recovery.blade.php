<style type="text/css">
  .line {
    height: 1px;
    width: 100%;
    background-color: rgb(204,204,204)
  }

  .wrapper {
    /*background-color: rgb(228,231,237);*/
    /*padding: 80px 0;*/
    padding: 20px 0 100px;
  }

  .container {
    width: 800px;
    margin: 0 auto;
    background-color: #fff;
    /*border-top: 4px solid rgb(204,204,204);*/
    border-top: 4px solid rgb(29,66,133);
  }

  .content {
    padding: 0 20px 20px;
  }

  .content h4 {
    font-size: 14px
  }

  .button {
    color: #fff;
    text-decoration: none !important;
    background-color: #2979FF;
    padding: 8px 10px;
    border: 0;
    font-size: 12px;
    display: inline-block;
    cursor: pointer;
  }

</style>

<div class="wrapper">
  <div class="container">
    <img src="<?php echo $message->embed('http://sundaysquare.com/images/logo3.png'); ?>">
    <div class="content">
      <p>เราได้รับการร้องขอการรีเซ็ตรหัสผ่าน</p>
      <p>หากต้องการรีเซ็ตรหัสผ่าน โปรดคลิกลิงก์ต่อไปนี้:</p>
      <br>
      <a href="{{URL::to('verify')}}?key={{$key}}" class="button">รีเซ็ตรหัสผ่าน</a>
      <br>
      <br>
      <p>หากคุณไม่ได้ร้องขอคำขอนี้คุณสามารถเพิกเฉยต่อข้อความนี้ได้อย่างปลอดภัย คำขอจะหมดอายุในไม่ช้า</p>
      <br>
      <div>
        ขอบคุณ<br>
        Sunday Square
      </div>
    </div>
  </div>
</div>

