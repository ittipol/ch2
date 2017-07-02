<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EmailValidationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\Person;
use App\library\messageHelper;
use App\library\service;
use App\library\url;
use App\library\token;
use App\Mail\AccountVarify;
use App\Mail\AccountRecovery;
use Auth;
use Session;
use Redirect;
use Hash;
use Mail;
use Cookie;

class UserController extends Controller
{
  public function login() {

    if(Auth::check()){
      return redirect('/');
    }

    $bannerImages = array('img1.png','img2.png','img3.png','img4.png','img5.png');

    $this->setData('bannerImage',$bannerImages[rand(0,count($bannerImages)-1)]);

    $this->setPageTitle('เข้าสู่ระบบ');
    $this->setMetaKeywords('สร้างร้านค้า,สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,ขายของออนไลน์,เข้าสู่ระบบ');

    $this->botAllowed();

    return $this->view('pages.user.login');

  }

  public function auth() { 

    // $remember = !empty(request()->input('remember')) ? true : false;

    if(Auth::attempt([
      'email' =>  request()->input('email'),
      'password'  =>  request()->input('password')
    ],!empty(request()->input('remember')) ? true : false)){

      // check account verified
      if(!Auth::user()->verified) {

        $cookie = Cookie::forget(Auth::getRecallerName());

        Auth::logout();
        Session::flush();

        MessageHelper::display('โปรดยืนยันบัญชีของคุณเพื่อยืนยันว่านี่เป็นบัญชีที่ถูกต้อง','error');

        return Redirect::to('login')->withCookie($cookie);
      }

      // Ger person
      // $person = Person::select('id','name','profile_image_id')->where('user_id','=',Auth::user()->id)->first();
      $person = Person::select('id')->where('user_id','=',Auth::user()->id)->first();

      // Update Token
      // Use for pushing notification
      $person->token = Token::generateSecureKey();
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
      
      // $message = new MessageHelper;
      // $message->registerSuccess();

      // Send varify email
      $template = new AccountVarify;
      $template->email = $user->email;
      $template->key = $user->verification_token;
      Mail::to($user->email)->send($template);

      session()->flash('register-success',true);

      return Redirect::to('register/success');

    }else{
      MessageHelper::display('ไม่สามารถสมัครสมาชิกได้','error');
    }

    return Redirect::to('login');

  }

  public function registerSuccess() {
    if(!session()->has('register-success')) {
      return Redirect::to('login');
    }

    return $this->view('pages.user.register-success');
  }

  public function identify() {

    if(session()->has('identify-sent')) {
      return $this->view('pages.user.identify-sent');
    }

    return $this->view('pages.user.identify');
  }

  public function identifySubmit(EmailValidationRequest $request) {

    $userModel = new User;

    $email = request()->get('email');

    $user = $userModel->where('email','like',$email);

    if($user->exists()) {

      // check dup key
      do {
        $key = Token::generateSecureKey();
      } while ($userModel->where('identify_token','like',$key)->exists());

      // save token and expire
      $user = $user->select('id')->first();
      $user->identify_token = $key;
      $user->identify_expire = date('Y-m-d H:i:s',time() + 2400);

      if($user->save()) {
        $template = new AccountRecovery;
        $template->email = $email;
        $template->key = $key;
        Mail::to($email)->send($template);
      }

    }

    session()->flash('identify-sent',true);

    return Redirect::to('user/identify');
  }

  public function recover() {

    if(!request()->has('user') || !request()->has('key')) {
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
      return redirect('login');
    }

    $email = request()->user;
    $key = request()->key;

    $user = User::where([
      ['email','like',$email],
      ['identify_token','like',$key],
      ['identify_expire','>',date('Y-m-d H:i:s')]
    ]);

    if(!$user->exists()) {
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
      return redirect('login');
    }

    $this->setData('email',$email);
    $this->setData('key',$key);

    return $this->view('pages.user.recover');
  }

  public function recoverSubmit(ResetPasswordRequest $request) {

    if(!$request->has('user') || !$request->has('key')) {
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
      return redirect('login');
    }

    $email = request()->user;
    $key = request()->key;

    $user = User::where([
      ['email','like',$email],
      ['identify_token','like',$key],
      ['identify_expire','>',date('Y-m-d H:i:s')]
    ]);

    if($user->exists()) {

      $user = $user->select('id')->first();
      $user->password = Hash::make(request()->password);
      $user->identify_token = null;
      $user->identify_expire = null;
      $user->save();

      MessageHelper::display('รหัสผ่านใหม่ของคุณถูกบันทึกแล้ว คุณสามารถเข้าสู่ระบบด้วยรหัสผ่านใหม่ได้แล้ว','success');
    }else{
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
    }
    
    return redirect('login');
  }

  public function verify() {

    if(!request()->has('user') || !request()->has('key')) {
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
      return redirect('login');
    }

    $email = request()->user;
    $key = request()->key;

    // $user = User::where([
    //   ['email','like',$email],
    //   ['verification_token','like',$key]
    // ]);

    $user = User::where('email','like',$email);

    if(!$user->exists()) {
      MessageHelper::display('ไม่พบคำขอหรือคำขออาจหมดอายุแล้ว','error');
      return redirect('login');
    }

    $user = $user->select('id','verification_token','verified')->first();

    if($user->verified) {
      MessageHelper::display('บัญชีของคุณถูกยืนยันแล้ว','info');
    }elseif($key === $user->verification_token) {
      $user->verification_token = null;
      $user->verified = 1;
      $user->save();

      // MessageHelper::display('การยืนยันบัญชีของคุณเรียบร้อยแล้ว บัญชีของคุณสามารถใช้งานได้แล้ว','success');
      return $this->view('pages.user.verification-success');
    }else {
      MessageHelper::display('ไม่สามารถยืนยันบัญชีของคุณได้','error');
    }

    return redirect('login');

  }

  public function logout() {
    Auth::logout();
    Session::flush();
    return redirect('/');
  }

  public function xxx() {

    $user = User::find(1);

    $user->verification_token = Token::generateSecureKey();
    $user->save();

    $template = new AccountVarify;
    $template->email = $user->email;
    $template->key = $user->verification_token;
    Mail::to($user->email)->send($template);

  }
  
}
