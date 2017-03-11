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
      // Store data
      $person = Person::select(array('id','name','profile_image_id','theme'))->find(Auth::user()->id);
      Session::put('Person.id',$person->id);
      Session::put('Person.name',$person->name);
      Session::put('Person.theme',$person->theme);
      Session::put('Person.profile_image',$person->getProfileImageUrl());
      // Session::put('Person.pageAccessLevel',{1-4});

      // update cart
      $cartModel = Service::loadModel('Cart');
      $products = session()->get('cart');
      session()->forget('cart');

      if(!empty($products)) {

        foreach ($products as $product) {
  
          $cart = $cartModel->where([
            ['person_id','=',$person->id],
            ['product_id','=',$product['productId']]
          ])
          ->select('id')
          ->first();

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

   //  $dateModel = new Date;

  	// $thaiLatestYear = date('Y') + 543;
  	
  	// $day = array();
  	// $month = array();
  	// $year = array();

  	// for ($i=1; $i <= 31; $i++) { 
  	// 	$day[$i] = $i;
  	// }

  	// for ($i=1; $i <= 12; $i++) { 
  	// 	$month[$i] = $dateModel->getMonthName($i);
  	// }

  	// for ($i=2500; $i <= $thaiLatestYear; $i++) { 
  	// 	$year[$i] = $i;
  	// }

  	// $this->data = array(
  	// 	'day' => $day,
  	// 	'month' => $month,
  	// 	'year' => $year
  	// );

    return $this->view('pages.user.register');

  }

  public function register(RegisterRequest $request) {   

    $user = new User;
  	$user->fill($request->all());

    if($user->save()){

      // // create folder
      // $user->createUserFolder();

      // // add avatar
      // $user->avatar($request->file('avatar'));

      // // create profile
      // $profile = new Profile;
      // $profile->fill($request->all());
      // $profile->save();

      // // create person
      // $person = new Person;
      // $person->fill($request->all());
      // $person->user_id = $user->id;
      // $person->profile_id = $profile->id;
      // $person->save();

      // $tags = array();
      // if(!empty($request->input('interests'))){
      //   $word = new Word;
      //   $words = $word->saveSpecial($request->input('interests'));
      // }

      // foreach ($words as $wordId => $word) {
      //   $personInterest = new PersonInterest;
      //   $personInterest->checkAndSave($person->id,$wordId);
      // }

      $message = new Message;
      $message->registerSuccess();

    }

    return Redirect::to('login');
  }
  
}
