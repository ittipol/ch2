<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\url;

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

//   public function lanAdd() {
// dd('ccc');
//       $model = new Language;

//       $languages = array(
//       'ภาษากรีก',
//       'ภาษากันนาดา',
//       'ภาษากาลิเชียน',
//       'ภาษากูจาราติ',
//       'ภาษาเกลิกในสก็อต',
//       'ภาษาเกาหลี',
//       'ภาษาเขมร',
//       'ภาษาคอร์สิกา',
//       'ภาษาคาซัค',
//       'ภาษาคาตาลัน',
//       'ภาษาคีร์กิซ',
//       'ภาษาเคิร์ด',
//       'ภาษาโครเอเชีย',
//       'ภาษาจอร์เจีย',
//       'ภาษาจีน',
//       'ภาษาชวา',
//       'ภาษาชิเชวา',
//       'ภาษาเช็ก',
//       'ภาษาโชนา',
//       'ภาษาซามัว',
//       'ภาษาซีบัวโน',
//       'ภาษาซุนดา',
//       'ภาษาซูลู',
//       'ภาษาเซโซโท',
//       'ภาษาเซอร์เบียน',
//       'ภาษาโซซา',
//       'ภาษาโซมาลี',
//       'ภาษาญี่ปุ่น',
//       'ภาษาดัตช์',
//       'ภาษาเดนมาร์ก',
//       'ภาษาตุรกี',
//       'ภาษาเตลูกู',
//       'ภาษาทมิฬ',
//       'ภาษาทาจิก',
//       'ภาษาไทย',
//       'ภาษานอร์เวย์',
//       'ภาษาเนปาล',
//       'ภาษาบอสเนีย',
//       'ภาษาบัลกาเรีย',
//       'ภาษาบาสก์',
//       'ภาษาเบงกาลี',
//       'ภาษาเบลารูเชียน',
//       'ภาษาปัญจาป',
//       'ภาษาเปอร์เซีย',
//       'ภาษาโปรตุเกส',
//       'ภาษาโปแลนด์',
//       'ภาษาฝรั่งเศส',
//       'ภาษาพาชตู',
//       'ภาษาฟริเชียน',
//       'ภาษาฟินแลนด์',
//       'ภาษาฟิลิปปินส์',
//       'ภาษาม้ง',
//       'ภาษามองโกเลีย',
//       'ภาษามัลทีส',
//       'ภาษามาซีโดเนีย',
//       'ภาษามาราฐี',
//       'ภาษามาลากาซี',
//       'ภาษามาลายาลัม',
//       'ภาษามาเลย์',
//       'ภาษาเมารี',
//       'ภาษาเมียนมา (พม่า)', 
//       'ภาษายิดดิช',
//       'ภาษายูเครน',
//       'ภาษาเยอรมัน',
//       'ภาษาโยรูบา',
//       'ภาษารัสเซีย',
//       'ภาษาโรมาเนีย',
//       'ภาษาละติน',
//       'ภาษาลักเซมเบิร์ก',
//       'ภาษาลัทเวีย',
//       'ภาษาลาว',
//       'ภาษาลิทัวเนีย',
//       'ภาษาเวลส์',
//       'ภาษาเวียดนาม',
//       'ภาษาสเปน',
//       'ภาษาสโลวัก',
//       'ภาษาสโลเวเนีย',
//       'ภาษาสวาฮิลี',
//       'ภาษาสวีเดน',
//       'ภาษาสิงหล',
//       'ภาษาสินธุ',
//       'ภาษาอังกฤษ',
//       'ภาษาอัมฮาริก',
//       'ภาษาอัลบาเนีย',
//       'ภาษาอาร์เซอร์ไบจัน',
//       'ภาษาอาร์เมเนีย',
//       'ภาษาอาหรับ',
//       'ภาษาอิกโบ',
//       'ภาษาอิตาลี',
//       'ภาษาอินโดนีเซีย',
//       'ภาษาอุสเบกิสถาน',
//       'ภาษาอูรดูร์',
//       'ภาษาเอสโทเนีย',
//       'ภาษาเอสเปอแรนโต',
//       'ภาษาแอฟริกา',
//       'ภาษาไอซ์แลนดิก',
//       'ภาษาไอริช',
//       'ภาษาฮังการี',
//       'ภาษาฮัวซา',
//       'ภาษาฮาวาย',
//       'ภาษาฮินดี',
//       'ภาษาฮิบรู',
//       'ภาษาเฮติครีโอล',
//       );

//       foreach ($languages as $value) {
//           $model->newInstance()->fill(array(
//               'name' => $value
//           ))->save();
//       }

//       dd('dddd');

//   }

//   public function addCareer() {
// exit;
//           $model = Service::loadModel('CareerType');

//           $careers = array(
//             'สถาปัตยกรรม / ออกแบบ',
//             'การศึกษา / ติวเตอร์',
//             'เจ้าหน้าที่ฝ่ายทรัพยากรมนุษย์',
//             'ที่ปรึกษา',
//             'สื่อสารมวลชนวิทยุ / โทรทัศน์',
//             'โฆษณา / ประชาสัมพันธ์ / CRM',
//             'วิศวกร',
//             'หัวหน้าคนงาน',
//             'การเงิน / การธนาคาร',
//             'บัญชี',
//             'ผู้ตรวจสอบทั่วไป / ผู้สอบบัญชี',
//             'ประกันภัย/ประกันชีวิต',
//             'คอมพิวเตอร์ / IT / โปรแกรมเมอร์',
//             'วิทยาศาสตร์ / R&D',
//             'การแพทย์',
//             'สุขภาพความงาม / สปา / ฟิตเนส',
//             'การจัดการ',
//             'การตลาด / สื่อออนไลน์',
//             'การผลิต',
//             'ควบคุมการผลิต / QA / QC',
//             'การจัดซื้อ',
//             'การขนส่ง / โลจิสติกส์',
//             'การขาย / ส่งเสริมการขาย',
//             'เกษตร / สัตวบาล / ประมง / ชลประทาน',
//             'ผู้บริหาร / ผู้จัดการ / ผู้อำนวยการ',
//             'เลขานุการ / พนักงานต้อนรับ',
//             'ธุรการ / ประสานงานทั่วไป / คีย์ข้อมูล',
//             'งานด้านภาษา / อักษรศาสตร์ / นักเขียน / บรรณาธิการ',
//             'นักแปล / ล่าม',
//             'พนักงานเสิร์ฟ',
//             'เจ้าหน้าที่รักษาความปลอดภัย / แม่บ้าน / พนักงานขับรถ',
//             'บริการลูกค้า / ประชาสัมพันธ์ / รับโทรศัพท์',
//             'อื่นๆ',
//           );
  
//           foreach ($careers as $value) {
//               $model->newInstance()->fill(array(
//                   'name' => $value
//               ))->save();
//           }
// dd('dene');
//   }

  public function index() {

    $url = new url;




    // Shop
    $shop = Service::loadModel('Shop')->find(5);
    $slug = Service::loadModel('Slug')->where(array(
      array('model','like','Shop'),
      array('model_id','=',5)
    ))->first()->slug;

    $this->setData('shopName1',$shop->name);
    $this->setData('shopUrl1',$url->url('shop/'.$slug));
    $this->setData('shopProductUrl1',$url->url('shop/'.$slug.'/product'));

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where([
      ['shop_relate_to.model','like','Product'],
      ['shop_relate_to.shop_id','=',5]
    ])
    ->take(4)
    ->get();

    $_products = array();
    foreach ($products as $product) {
      $_products[] = array_merge($product->buildPaginationData(),array(
        '_imageUrl' => $product->getImage('list'),
        'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id'=>$product->id))
      ));
    }
    $this->setData('products1',$_products);





    $shop = Service::loadModel('Shop')->find(1);
    $slug = Service::loadModel('Slug')->where(array(
      array('model','like','Shop'),
      array('model_id','=',1)
    ))->first()->slug;

    $this->setData('shopName2',$shop->name);
    $this->setData('shopUrl2',$url->url('shop/'.$slug));
    $this->setData('shopProductUrl2',$url->url('shop/'.$slug.'/product'));

    $products = Service::loadModel('Product')
    ->join('shop_relate_to', 'shop_relate_to.model_id', '=', 'products.id')
    ->where([
      ['shop_relate_to.model','like','Product'],
      ['shop_relate_to.shop_id','=',1]
    ])
    ->take(4)
    ->get();

    $_products = array();
    foreach ($products as $product) {
      $_products[] = array_merge($product->buildPaginationData(),array(
        '_imageUrl' => $product->getImage('list'),
        'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id'=>$product->id))
      ));
    }
    $this->setData('products2',$_products);



    
    $this->setData('shirts',$this->getProductData(80));
    $this->setData('dresses',$this->getProductData(108));
    $this->setData('bags',$this->getProductData(171));
    $this->setData('shoes',$this->getProductData(176));
    $this->setData('bedSheets',$this->getProductData(1260));

    $this->setData('moreShirtUrl',$url->url('product/80'));
    $this->setData('moreDressUrl',$url->url('product/108'));
    $this->setData('moreBagUrl',$url->url('product/171'));
    $this->setData('moreShoeUrl',$url->url('product/176'));
    $this->setData('moreBedSheetUrl',$url->url('product/1260'));

    return $this->view('pages.home.index');
  }

  private function getProductData($c) {

    $url = new url;
    $product = Service::loadModel('Product');

    $categoryPaths = Service::loadModel('CategoryPath')->where('path_id','=',$c)->get();

    $ids = array();
    foreach ($categoryPaths as $categoryPath) {
      $ids[] = $categoryPath->category_id;
    }

    $products = $product
    ->join('product_to_categories', 'product_to_categories.product_id', '=', 'products.id')
    ->whereIn('product_to_categories.category_id',$ids)
    ->select('products.*')
    ->orderBy('products.created_at','desc')
    ->take(4);

    $data = array();
    if($products->exists()) {

      foreach ($products->get() as $product) {

        $data[] = array_merge($product->buildPaginationData(),array(
          '_imageUrl' => $product->getImage('list'),
          'detailUrl' => $url->setAndParseUrl('product/detail/{id}',array('id'=>$product->id))
        ));
        
      }

    }

    return $data;

  }

}
