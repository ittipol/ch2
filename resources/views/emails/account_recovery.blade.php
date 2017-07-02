<style type="text/css">
  .wrapper {
    /*background-color: rgb(228,231,237);*/
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
      <p>เราได้รับคำขอการรีเซ็ตรหัสผ่านของคุณ<br>หากต้องการรีเซ็ตรหัสผ่าน โปรดคลิกลิงก์ต่อไปนี้:</p>
      <br>
      <a href="{{$link}}">{{$link}}</a>
      <br>
      <br>
      <p>หากคุณไม่ได้ร้องขอคำขอนี้คุณสามารถเพิกเฉยต่อข้อความนี้ได้อย่างปลอดภัย คำขอจะหมดอายุในไม่ช้า</p>
      <br>
      <div>ขอบคุณ<br>Sunday Square</div>
    </div>
  </div>
</div>