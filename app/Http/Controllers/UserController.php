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
use Auth;
use Session;
use Redirect;

class UserController extends Controller
{
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
      $person = Person::select(array('id','name','profile_image_id'))->where('user_id','=',Auth::user()->id)->first();

      // Update Token
      // User for pushing notification
      $person->token = Token::generate();
      $person->save();
      
      // Store data
      Session::put('Person.id',$person->id);
      Session::put('Person.name',$person->name);
      // Session::put('Person.theme',$person->theme);
      Session::put('Person.token',$person->token);

      if(empty($person->profile_image_id)) {
        Session::put('Person.profile_image_xs','/images/common/avatar.png');
        Session::put('Person.profile_image','/images/common/avatar.png');
      }else{
        Session::put('Person.profile_image_xs',$person->getProfileImageUrl('xs'));
        Session::put('Person.profile_image',$person->getProfileImageUrl('xsm'));
      }

      // Session::put('Person.pageAccessLevel',{1-4});

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

      if(Auth::check()) {
        Auth::logout();
        Session::flush();
      }
      
      $message = new MessageHelper;
      $message->registerSuccess();
    }

    return Redirect::to('login');
  }
  
}
