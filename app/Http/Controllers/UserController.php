<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Person;
use App\library\messageHelper;
use App\library\service;
use App\library\url;
use App\library\token;
// use App\library\mail;
use Auth;
use Session;
use Redirect;
use App\Mail\AccountRecovery;
use Mail;

class UserController extends Controller
{
  public function login() {

    if(Auth::check()){
      return redirect('/');
    }

    $this->data = array(
      'header' => false,
      'footer' => false,
    );

    $bannerImages = array('img1.png','img2.png','img3.png','img4.png','img5.png');

    $this->setData('bannerImage',$bannerImages[rand(0,count($bannerImages)-1)]);

    $this->setPageTitle('เข้าสู่ระบบ');
    $this->setMetaKeywords('สร้างร้านค้า,สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,ขายของออนไลน์,เข้าสู่ระบบ');

    $this->botAllowed();

    return $this->view('pages.user.login');

  }

  public function auth() { 

    // $data = [
    //   'email' =>  request()->input('email'),
    //   'password'  =>  request()->input('password')
    // ];

    $remember = !empty(request()->input('remember')) ? true : false;

    if(Auth::attempt([
      'email' =>  request()->input('email'),
      'password'  =>  request()->input('password')
    ],$remember)){

      // Ger person
      // $person = Person::select('id','name','profile_image_id')->where('user_id','=',Auth::user()->id)->first();
      $person = Person::select('id')->where('user_id','=',Auth::user()->id)->first();

      // Update Token
      // Use for pushing notification
      $person->token = Token::generate();
      $person->save();
      
      // Store data
      // Session::put('Person.id',$person->id);
      // Session::put('Person.name',$person->name);
      // Session::put('Person.token',$person->token);

      // if(empty($person->profile_image_id)) {
      //   Session::put('Person.profile_image_xs','/images/common/avatar.png');
      //   Session::put('Person.profile_image','/images/common/avatar.png');
      // }else{
      //   Session::put('Person.profile_image_xs',$person->getProfileImageUrl('xs'));
      //   Session::put('Person.profile_image',$person->getProfileImageUrl('xsm'));
      // }

      // Update cart
      $cartModel = Service::loadModel('Cart');
      $productModel = Service::loadModel('Product');
      $productOptionValueModel = Service::loadModel('ProductOptionValue');

      $products = session()->get('cart');
      session()->forget('cart');

      if(!empty($products)) {

        foreach ($products as $product) {
          foreach ($product['items'] as $value) {

            // check products exist
            $data = $productModel->select('id')->find($product['productId']);

            if(empty($data)) {
              continue;
            }

            // check product options exist
            $productOptionValueId = null;
            if(!empty($value['productOptionValueId'])) {

              $data = $productOptionValueModel->select('id')->find($value['productOptionValueId']);

              if(empty($data)) {
                continue;
              }

              $productOptionValueId = $value['productOptionValueId'];

            }
            
            $cart = $cartModel->where([
              ['product_id','=',$product['productId']],
              ['product_option_value_id','=',$productOptionValueId],
              ['created_by','=',$person->id]
            ])->first();

            if(!empty($cart)) {
              $cart->increment('quantity', $value['quantity']);
            }else{
              $_value = array(
                'shop_id' => $product['shopId'],
                'product_id' => $product['productId'],
                'product_option_value_id' => $productOptionValueId,
                'quantity' => $value['quantity'],
                'created_by' => $person->id
              );

              $cartModel->newInstance()->fill($_value)->save();
            }
            
          }
        }

      }

      $message = new MessageHelper;
      $message->loginSuccess();
      return Redirect::intended('/');
    }else{

      $message = 'อีเมล หรือ รหัสผ่านไม่ถูก';

      if(empty(request()->input('email')) && empty(request()->input('password'))) {
        $message = 'กรุณากรอกอีเมล และ รหัสผ่าน';
      }

      return Redirect::back()->withErrors([$message]);
    }

  }

  public function registerForm() {

    // if(Auth::check()){
    //   return redirect('/');
    // }

    $this->setPageTitle('สมัครสมาชิก');
    $this->setMetaKeywords('สร้างร้านค้า,สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,ขายของออนไลน์,สมัครสมาชิก');

    $this->botAllowed();

    return $this->view('pages.user.register');

  }

  public function register(RegisterRequest $request) {   

    $user = new User;
  	$user->fill($request->all());

    if($user->save()){

      if(Auth::check()) {
        Auth::logout();
        Session::flush();
      }
      
      $message = new MessageHelper;
      $message->registerSuccess();
    }

    return Redirect::to('login');
  }

  public function identify() {

    $user = User::where('email','like','red@mail.com');

    $port = 587;
    if(!empty($_GET['port'])) {
      $port = $_GET['port'];
    }

    // $mail = new Mail();
   
    // $mail->protocol = 'smtp';
    // $mail->smtp_hostname = 'tls://smtp.gmail.com';
    // $mail->smtp_username = 'sundaysquare.help@gmail.com';
    // $mail->smtp_password = 'ittipol1q2w3e';
    // $mail->smtp_port = $port;

    // $mail->setFrom('sundaysquare.help@gmail.com');
    // $mail->setTo('ittipol_master@hotmail.com');

    // $mail->setSender('Sunday Square Support');

    // $mail->setSubject('รหัสยืนยันการกู้คืนรหัสผ่าน');
    // $mail->setHtml('testing HTML');

    // $mail->send();

    Mail::to('ittipol_master@hotmail.com')->send(new AccountRecovery);

dd('sent');
    if($user->exists()) {

      // save token and expire
      $user = $user->select('id','email')->first();
      $user->identify_token = bin2hex(random_bytes(32));
      $user->identify_expire = date('Y-m-d H:i:s',time() + 1800);

      if($user->save()) {

      }
      // send email


    }

    return Redirect::to('identify');
  }

  public function identifySubmit() {

    // return always success message

  }
  
}
