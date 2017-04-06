<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
// use App\Models\Profile;
use App\Models\Person;
// use App\Models\PersonInterest;
// use App\Models\Word;
// use App\library\date;
use App\library\message;
use App\library\service;
use App\library\url;
use App\library\token;
use Auth;
use Session;
use Redirect;

class UserController extends Controller
{

  // public function account(){


  // }

  // public function profile(){
      
  // }

  public function login() {

    $this->data = array(
      'header' => false,
      'footer' => false,
    );

    if(Auth::check()){
      return redirect('/');
    }else{
      return $this->view('pages.user.login');
    }

  }

  public function auth() { 

    $data = [
      'email' =>  request()->input('email'),
      'password'  =>  request()->input('password')
    ];

    if(Auth::attempt($data)){

      // Ger person
      $person = Person::select(array('id','name','profile_image_id','theme'))->find(Auth::user()->id);

      // Update Token
      // User for pushing notification
      $person->token = Token::generate();
      $person->save();

      // Store data
      Session::put('Person.id',$person->id);
      Session::put('Person.name',$person->name);
      Session::put('Person.theme',$person->theme);
      Session::put('Person.profile_image_xs',$person->getProfileImageUrl('xs'));
      Session::put('Person.profile_image',$person->getProfileImageUrl('xsm'));
      Session::put('Person.token',$person->token);
      // Session::put('Person.pageAccessLevel',{1-4});

      // Update cart
      $cartModel = Service::loadModel('Cart');
      $products = session()->get('cart');
      session()->forget('cart');

      if(!empty($products)) {

        foreach ($products as $product) {
  
          $cart = $cartModel->where([
            ['person_id','=',$person->id],
            ['product_id','=',$product['productId']]
          ])->first();

          if(!empty($cart)) {
            $cart->increment('quantity', $product['quantity']);
          }else{
            $value = array(
              'person_id' => $person->id,
              'shop_id' => $product['shopId'],
              'product_id' => $product['productId'],
              'quantity' => $product['quantity']
            );

            $cartModel->newInstance()->fill($value)->save();
          }

        }

      }

      $message = new Message;
      $message->loginSuccess();
      return Redirect::intended('/');
    }else{

      $message = 'อีเมล หรือ รหัสผ่านไม่ถูก';

      if(empty(request()->input('email')) && empty(request()->input('password'))) {
        $message = 'กรุณใส่อีเมล และ รหัสผ่าน';
      }

      return Redirect::back()->withErrors([$message]);
    }

  }

  public function registerForm() {

    if(Auth::check()){
      return redirect('/');
    }

    return $this->view('pages.user.register');

  }

  public function register(RegisterRequest $request) {   

    $user = new User;
  	$user->fill($request->all());

    if($user->save()){
      $message = new Message;
      $message->registerSuccess();
    }

    return Redirect::to('login');
  }
  
}
