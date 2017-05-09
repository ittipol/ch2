<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\library\service;

class HomeController extends Controller
{
  // public function addC() {
  //   $cartModel = Service::loadModel('Cart');
  //   // $model->addProduct(26,20,1);
  //   // $model->updateQuantity(26,44,3);
  //   // $model->deleteProduct(26,3);

  //   $products = session()->get('cart');

  //   if(!empty($products)) {

  //     // foreach ($products as $product) {
  //     //   foreach ($product['items'] as $value) {

  //     //     $cart = $cartModel->where([
  //     //       ['product_id','=',$product['productId']],
  //     //       ['product_option_value_id','=',$value['productOptionValueId']],
  //     //       ['created_by','=',$person],
  //     //     ])->first();


  //     //     if(!empty($cart)) {
  //     //       $cart->increment('quantity', $value['quantity']);
  //     //     }else{
  //     //       $_value = array(
  //     //         'created_by' => $person,
  //     //         'shop_id' => $product['shopId'],
  //     //         'product_id' => $product['productId'],
  //     //         'product_option_value_id' => $value['productOptionValueId'],
  //     //         'quantity' => $value['quantity']
  //     //       );

  //     //       $cartModel->newInstance()->fill($_value)->save();
  //     //     }

  //     //   }
  //     // }

  //   }

  // }

  // public function co() {
  //   $cartModel = Service::loadModel('Cart');
  //   $cartModel->productCount();
  // }

  public function catPath() {
exit('Error!');
    ini_set('max_execution_time', 200000);

    $page = 1;
    $perPage = 100;
    $total = Service::loadModel('Category')->count();

    // $count = 1;

    do {

      $offset = ($page - 1)  * $perPage;

      $records = Service::loadModel('Category')
      ->take($perPage)
      ->skip($offset)
      ->get();

      foreach ($records as $record) {
        $categoryId = $record->id;

        $ids = array();
        $ids[] = $categoryId;

        $data = Service::loadModel('Category')->find($categoryId);

        while (!empty($data->parent_id)) {
          $ids[] = $data->parent_id;
          $data = Service::loadModel('Category')->find($data->parent_id);
        }

        $level = count($ids)-1;

        for ($i=0; $i < count($ids); $i++) { 
          $value = array(
            'category_id' => $categoryId,
            'path_id' => $ids[$i],
            'level' => $level--
          );

          $model = Service::loadModel('CategoryPath')->newInstance();
          $model->fill($value)->save();
        }

      }

      $page++;

      // if($count++ > 10) {
      //   break;
      // }

    } while (($offset + $perPage) < $total);

    var_dump($page);

    dd('done');

  }

  public function addXxx() {
exit('Error!');
    return $this->view('addCat');

  }

  public function addXxxSub() {
exit('Error!');
    $parentId = request()->get('pid');
    $str = request()->get('description');

    $str = preg_replace('/(\t)+/', ' ', $str);
    // $str = preg_replace('/(\v|\s)+/', ' ', $str);

    $str = str_replace('o ', '', $str);
    $str = str_replace('• ', '', $str);
    $str = str_replace(' ', '', $str);



    $str = preg_replace('/( \d{1,3})|( -> \d{1,3})/', '', $str);
    $str = preg_replace('/(\n)+/', '$$$', $str);
    $str = preg_replace('/(\r)+/', '', $str);

    $strs = explode('$$$', $str);

    if(!is_array($strs)) {
      $temp = $strs;
      $strs = array();
      $strs[] = $temp;
    }

    echo '<div style="width: 50%; margin: 0 auto;">';

    foreach ($strs as $str) {
      $_str =  trim($str);
    
      if(!empty($_str)) {

        $_value = array(
          'name' => $_str
        );

        if(!empty($parentId)) {
          $_value = array(
            'parent_id' => $parentId,
            'name' => $_str
          );
        }

        $model = Service::loadModel('Category')->newInstance();

        $model->fill($_value)->save();

        echo '<h3 style="font-size:22px;">['. $_str . '] --> Saved || PID: [<span style="color:blue;">'. $parentId .'</span>] || ID: [<span style="color:red;">' . $model->id . '</span>]</h3><br/>';
      }

    }

    echo '<br/><a style="font-size:46px;" href="/ac">BACK TO INPUT FORM</a><br/>';

    echo '</div>';

dd('end');
    // $re = '/[\S]{3,}/';

    // preg_match_all($re, $str, $matches);

    // Print the entire match result
    dd($matches[0]);

  }

  public function addCat() {
    exit('!!!');
        $data = array(
          'กระโปรงทำงาน',
          'กระโปรงยีนส์',
          'กระโปรงสั่น',
          'กระโปรงยาว',
          'กระโปรพลีท',
          'กระโปรเอวสูง',
          'กระโปรทรงเอ',
          'กระโปรแฟชั่น',
        );

        $parentId = 80;

        foreach ($data as $value) {

          $_value = array(
            'name' => $value
          );

          if(!empty($parentId)) {
            $_value = array(
              'parent_id' => $parentId,
              'name' => $value
            );
          }

          Service::loadModel('Category')->newInstance()->fill($_value)->save();
        }
        dd('saved');

  }

  public function lanAdd() {
dd('ccc');
      $model = new Language;

      $languages = array(
      'ภาษากรีก',
      'ภาษากันนาดา',
      'ภาษากาลิเชียน',
      'ภาษากูจาราติ',
      'ภาษาเกลิกในสก็อต',
      'ภาษาเกาหลี',
      'ภาษาเขมร',
      'ภาษาคอร์สิกา',
      'ภาษาคาซัค',
      'ภาษาคาตาลัน',
      'ภาษาคีร์กิซ',
      'ภาษาเคิร์ด',
      'ภาษาโครเอเชีย',
      'ภาษาจอร์เจีย',
      'ภาษาจีน',
      'ภาษาชวา',
      'ภาษาชิเชวา',
      'ภาษาเช็ก',
      'ภาษาโชนา',
      'ภาษาซามัว',
      'ภาษาซีบัวโน',
      'ภาษาซุนดา',
      'ภาษาซูลู',
      'ภาษาเซโซโท',
      'ภาษาเซอร์เบียน',
      'ภาษาโซซา',
      'ภาษาโซมาลี',
      'ภาษาญี่ปุ่น',
      'ภาษาดัตช์',
      'ภาษาเดนมาร์ก',
      'ภาษาตุรกี',
      'ภาษาเตลูกู',
      'ภาษาทมิฬ',
      'ภาษาทาจิก',
      'ภาษาไทย',
      'ภาษานอร์เวย์',
      'ภาษาเนปาล',
      'ภาษาบอสเนีย',
      'ภาษาบัลกาเรีย',
      'ภาษาบาสก์',
      'ภาษาเบงกาลี',
      'ภาษาเบลารูเชียน',
      'ภาษาปัญจาป',
      'ภาษาเปอร์เซีย',
      'ภาษาโปรตุเกส',
      'ภาษาโปแลนด์',
      'ภาษาฝรั่งเศส',
      'ภาษาพาชตู',
      'ภาษาฟริเชียน',
      'ภาษาฟินแลนด์',
      'ภาษาฟิลิปปินส์',
      'ภาษาม้ง',
      'ภาษามองโกเลีย',
      'ภาษามัลทีส',
      'ภาษามาซีโดเนีย',
      'ภาษามาราฐี',
      'ภาษามาลากาซี',
      'ภาษามาลายาลัม',
      'ภาษามาเลย์',
      'ภาษาเมารี',
      'ภาษาเมียนมา (พม่า)', 
      'ภาษายิดดิช',
      'ภาษายูเครน',
      'ภาษาเยอรมัน',
      'ภาษาโยรูบา',
      'ภาษารัสเซีย',
      'ภาษาโรมาเนีย',
      'ภาษาละติน',
      'ภาษาลักเซมเบิร์ก',
      'ภาษาลัทเวีย',
      'ภาษาลาว',
      'ภาษาลิทัวเนีย',
      'ภาษาเวลส์',
      'ภาษาเวียดนาม',
      'ภาษาสเปน',
      'ภาษาสโลวัก',
      'ภาษาสโลเวเนีย',
      'ภาษาสวาฮิลี',
      'ภาษาสวีเดน',
      'ภาษาสิงหล',
      'ภาษาสินธุ',
      'ภาษาอังกฤษ',
      'ภาษาอัมฮาริก',
      'ภาษาอัลบาเนีย',
      'ภาษาอาร์เซอร์ไบจัน',
      'ภาษาอาร์เมเนีย',
      'ภาษาอาหรับ',
      'ภาษาอิกโบ',
      'ภาษาอิตาลี',
      'ภาษาอินโดนีเซีย',
      'ภาษาอุสเบกิสถาน',
      'ภาษาอูรดูร์',
      'ภาษาเอสโทเนีย',
      'ภาษาเอสเปอแรนโต',
      'ภาษาแอฟริกา',
      'ภาษาไอซ์แลนดิก',
      'ภาษาไอริช',
      'ภาษาฮังการี',
      'ภาษาฮัวซา',
      'ภาษาฮาวาย',
      'ภาษาฮินดี',
      'ภาษาฮิบรู',
      'ภาษาเฮติครีโอล',
      );

      foreach ($languages as $value) {
          $model->newInstance()->fill(array(
              'name' => $value
          ))->save();
      }

      dd('dddd');

  }

  public function index() {

    $product = Service::loadModel('Product');
    $product->paginator->setPerPage(4);
    $product->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $product->paginator->setUrl('product/detail/{id}','detailUrl');

    $job = Service::loadModel('Job');
    $job->paginator->setPerPage(4);
    $job->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $job->paginator->setUrl('job/detail/{id}','detailUrl');

    $advertising = Service::loadModel('Advertising');
    $advertising->paginator->setPerPage(4);
    $advertising->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $advertising->paginator->setUrl('advertising/detail/{id}','detailUrl');

    $item = Service::loadModel('Item');
    $item->paginator->setPerPage(4);
    $item->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $item->paginator->setUrl('item/detail/{id}','detailUrl');

    $realEstate = Service::loadModel('RealEstate');
    $realEstate->paginator->setPerPage(4);
    $realEstate->paginator->criteria(array(
      'order' => array('created_at','DESC')
    ));
    $realEstate->paginator->setUrl('real-estate/detail/{id}','detailUrl');

    $this->setData('products',$product->paginator->getPaginationData());
    $this->setData('jobs',$job->paginator->getPaginationData());
    $this->setData('advertisings',$advertising->paginator->getPaginationData());
    $this->setData('items',$item->paginator->getPaginationData());
    $this->setData('realEstates',$realEstate->paginator->getPaginationData());

    return $this->view('pages.home.index');
  }

}
