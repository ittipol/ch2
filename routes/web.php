<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//

Route::get('cp','HomeController@catPath');

Route::get('ac','HomeController@addXxx');
Route::post('ac','HomeController@addXxxSub');

Route::get('cat','HomeController@addCat');
// Route::get('lan','HomeController@lanAdd');

Route::get('/debug',function(){
  // session()->forget('cart');
  dd(session()->all());

  // dd(\Cookie::get('carts'));

  // foreach (session()->get('Shop') as $key => $value) {
  //   dd($value);
  // }

  // return view('pages.announcement.create');

});

Route::get('/debug_notification',function(){
  $notification = new App\Models\Notification;
  $notification->getUnreadNotification();
});

// 

// 

// 

// 

// 

// 

// 
Route::get('/','HomeController@index');

Route::get('logout',function(){
  Auth::logout();
  Session::flush();
  return redirect('/');
});

// Login
Route::group(['middleware' => 'guest'], function () {
  Route::get('login','UserController@login');
  Route::post('login','UserController@auth');
});

// Register
Route::get('register','UserController@registerForm')->middleware('guest');
Route::post('register','UserController@register')->middleware('guest');

Route::get('safe_image/{file}', 'StaticFileController@serveImages');
Route::get('redirect', 'UrlController@redirect');

// Search
Route::get('search','SearchController@index')->name('search');

// Account
Route::group(['middleware' => 'auth'], function () {

  Route::get('account', 'AccountController@account')->name('account');

  Route::get('account/profile_edit', 'AccountController@profileEdit')->name('account.profile.edit');
  Route::patch('account/profile_edit','AccountController@profileEditingSubmit')->name('account.profile.edit');

  Route::get('account/theme', 'AccountController@theme')->name('account.theme.edit');
  // Route::patch('account/theme','PersonExperienceController@themeEditingSubmit')->name('account.theme.edit');

  Route::get('account/item', 'AccountController@item')->name('account.item');
  Route::get('account/real_estate', 'AccountController@realEstate')->name('account.real_estate');
  Route::get('account/shop', 'AccountController@shop')->name('account.shop');

  Route::get('account/order', 'AccountController@order')->name('account.order');

  Route::get('account/job_applying', 'AccountController@jobApplying')->name('account.job');
  Route::get('account/job_applying/{id}', 'JobController@accountJobApplyingDetail')->name('account.job.detail');

  Route::get('account/job_applying/message_reply/{id}','JobController@jobApplyingMessageReply');
  Route::post('account/job_applying/message_reply/{id}','JobController@jobApplyingMessageReplySend');

  Route::get('get_file_attachment/{id}', 'StaticFileController@attachedFile');

});

// Announcement
// Route::get('announcement/create','AnnouncementController@create');

// Experience
Route::get('experience/profile/list','PersonExperienceController@listView')->name('person_experience.list');
Route::get('experience/profile/{id}','PersonExperienceController@detail')->name('person_experience.detail')->middleware('data.access.permission');

Route::group(['middleware' => 'auth'], function () {
  Route::get('person/experience','PersonExperienceController@manage')->name('person_experience.manage');
  Route::post('person/experience','PersonExperienceController@start')->name('person_experience.start');
});
Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('experience/profile/edit','PersonExperienceController@profile')->name('person_experience.profile');
  Route::patch('experience/profile/edit','PersonExperienceController@profileEditingSubmit')->name('person_experience.profile.edit');

  Route::get('person/private_website/list','PersonPrivateWebsiteController@listView');
  Route::get('person/private_website/add','PersonPrivateWebsiteController@add');
  Route::post('person/private_website/add','PersonPrivateWebsiteController@addingSubmit');
  Route::get('person/private_website/edit/{id}','PersonPrivateWebsiteController@edit');
  Route::patch('person/private_website/edit/{id}','PersonPrivateWebsiteController@editingSubmit');
  // Route::patch('person/private_website/delete/{id}','PersonPrivateWebsiteController@delete');

  Route::get('experience/career_objective','PersonExperienceController@careerObjectiveEdit');
  Route::patch('experience/career_objective','PersonExperienceController@careerObjectiveEditingSubmit');

  Route::get('experience/working_add','PersonWorkingExperienceController@add');
  Route::post('experience/working_add','PersonWorkingExperienceController@addingSubmit');
  Route::get('experience/working_edit/{id}','PersonWorkingExperienceController@edit');
  Route::patch('experience/working_edit/{id}','PersonWorkingExperienceController@editingSubmit');

  Route::get('experience/internship_add','PersonInternshipController@add')->name('person_experience.internship.add');
  Route::post('experience/internship_add','PersonInternshipController@addingSubmit')->name('person_experience.internship.add');
  Route::get('experience/internship_edit/{id}','PersonInternshipController@edit')->name('person_experience.internship.edit');
  Route::patch('experience/internship_edit/{id}','PersonInternshipController@editingSubmit')->name('person_experience.internship.edit');

  Route::get('experience/education_add','PersonEducationController@add');
  Route::post('experience/education_add','PersonEducationController@addingSubmit');
  Route::get('experience/education_edit/{id}','PersonEducationController@edit');
  Route::patch('experience/education_edit/{id}','PersonEducationController@editingSubmit');

  Route::get('experience/project_add','PersonProjectController@add');
  Route::post('experience/project_add','PersonProjectController@addingSubmit');
  Route::get('experience/project_edit/{id}','PersonProjectController@edit');
  Route::patch('experience/project_edit/{id}','PersonProjectController@editingSubmit');

  Route::get('experience/certificate_add','PersonCertificateController@add');
  Route::post('experience/certificate_add','PersonCertificateController@addingSubmit');
  Route::get('experience/certificate_edit/{id}','PersonCertificateController@edit');
  Route::patch('experience/certificate_edit/{id}','PersonCertificateController@editingSubmit');

  Route::get('experience/skill_add','PersonSkillController@add');
  Route::post('experience/skill_add','PersonSkillController@addingSubmit');
  Route::get('experience/skill_edit/{id}','PersonSkillController@edit');
  Route::patch('experience/skill_edit/{id}','PersonSkillController@editingSubmit');
  
  Route::get('experience/language_skill_add','PersonLanguageSkillController@add');
  Route::post('experience/language_skill_add','PersonLanguageSkillController@addingSubmit');
  Route::get('experience/language_skill_edit/{id}','PersonLanguageSkillController@edit');
  Route::patch('experience/language_skill_edit/{id}','PersonLanguageSkillController@editingSubmit');

});

// Freelance
Route::get('freelance/detail/{id}','FreelanceController@detail')->name('freelance.detail');

Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('person/freelance','FreelanceController@manage')->name('person.freelance.manage');

  Route::get('person/freelance_post','FreelanceController@add')->name('freelance.add');
  Route::post('person/freelance_post','FreelanceController@addingSubmit')->name('freelance.add');

  Route::get('person/freelance_edit/{id}','FreelanceController@edit')->name('freelance.edit')->middleware('editing.permission');;
  Route::patch('person/freelance_edit/{id}','FreelanceController@editingSubmit')->name('freelance.edit')->middleware('editing.permission');;

  Route::get('person/freelance_delete/{id}','FreelanceController@delete')->name('freelance.delete');

});

// Cart
Route::get('cart','CartController@index')->name('cart');

// Checkout
Route::group(['middleware' => 'auth'], function () {
  Route::get('checkout','CheckoutController@checkout')->name('checkout');
  Route::post('checkout','CheckoutController@checkoutSubmit')->name('checkout');

  Route::get('checkout/success','CheckoutController@success')->name('checkout.success');
});

// Order
Route::group(['middleware' => 'auth'], function () {
  Route::get('account/order/{id}', 'OrderController@detail')->name('account.order.detail');
  
  Route::get('order/payment/inform/{id}', 'orderController@paymentInform')->name('account.order.payment_inform');
  Route::post('order/payment/inform/{id}', 'orderController@paymentInformSubmit')->name('account.order.payment_inform');

});

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/order','OrderController@shopOrder')->name('shop.order');
  Route::get('shop/{shopSlug}/order/{id}','OrderController@shopOrderDetail')->name('shop.order.detail');

  Route::get('shop/{shopSlug}/order/confirm/{id}','OrderController@shopOrderConfirm')->name('shop.order.confirm');
  Route::post('shop/{shopSlug}/order/confirm/{id}','OrderController@shopOrderConfirmSubmit')->name('shop.order.confirm');

  Route::patch('shop/{shopSlug}/order/payment/confirm/{id}', 'OrderController@paymentConfirm')->name('shop.order.payment.confirm');

  Route::get('shop/{shopSlug}/order/payment/detail/{id}', 'OrderController@paymentDetail')->name('shop.order.payment.detail');

  Route::patch('shop/{shopSlug}/order/status/update/{id}', 'OrderController@updateOrderStatus')->name('shop.order.status.update');

});

// community / Shop
// Route::get('community/shop_feature','ShopController@feature');
Route::group(['middleware' => ['auth','shop']], function () {
  Route::get('shop/{shopSlug}','ShopController@index')->name('shop.index');
});

Route::group(['middleware' => 'auth'], function () {
  Route::get('community/shop/create','ShopController@create')->name('shop.create');
  Route::post('community/shop/create','ShopController@creatingSubmit')->name('shop.create');
});
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/manage','ShopController@manage')->name('shop.manage');
  
  Route::get('shop/{shopSlug}/setting','ShopController@setting')->name('shop.setting');

  Route::get('shop/{shopSlug}/description','ShopController@description')->name('shop.edit.description');
  Route::patch('shop/{shopSlug}/description','ShopController@descriptionSubmit')->name('shop.edit.description');
  
  Route::get('shop/{shopSlug}/address','ShopController@address')->name('shop.edit.address');
  Route::patch('shop/{shopSlug}/address','ShopController@addressSubmit')->name('shop.edit.address');

  Route::get('shop/{shopSlug}/contact','ShopController@contact')->name('shop.edit.contact');
  Route::patch('shop/{shopSlug}/contact','ShopController@contactSubmit')->name('shop.edit.contact');

  Route::get('shop/{shopSlug}/opening_hours','ShopController@openingHours')->name('shop.edit.opening_hours');
  Route::patch('shop/{shopSlug}/opening_hours','ShopController@openingHoursSubmit')->name('shop.edit.opening_hours');
});

// PRODUCT
Route::get('product/shelf','ProductController@shelf')->name('product.shelf');
Route::get('product/shelf/{category_id}','ProductController@listView')->name('product.shelf');
Route::get('product/category/{category_id?}','ProductController@category')->name('product.category');
Route::get('product/detail/{id}','ProductController@detail')->name('product.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/product','ShopController@product')->name('shop.product');

  Route::get('shop/{shopSlug}/product/{id}','ProductController@menu')->name('shop.product.menu');

  Route::get('shop/{shopSlug}/product_post','ProductController@add')->name('shop.product.add');
  Route::post('shop/{shopSlug}/product_post','ProductController@addingSubmit')->name('shop.product.add');

  Route::get('shop/{shopSlug}/product_edit/{id}','ProductController@edit')->name('shop.product.edit');
  Route::patch('shop/{shopSlug}/product_edit/{id}','ProductController@editingSubmit')->name('shop.product.edit');

  Route::get('shop/{shopSlug}/product_status_edit/{id}','ProductController@statusEdit')->name('shop.product_status.edit');
  Route::patch('shop/{shopSlug}/product_status_edit/{id}','ProductController@statusEditingSubmit')->name('shop.product_status.edit');

  Route::get('shop/{shopSlug}/product_specification_edit/{id}','ProductController@specificationEdit')->name('shop.product_specification.edit');
  Route::patch('shop/{shopSlug}/product_specification_edit/{id}','ProductController@specificationEditingSubmit')->name('shop.product_specification.edit');

  Route::get('shop/{shopSlug}/product_category_edit/{id}','ProductController@categoryEdit')->name('shop.product_category.edit');
  Route::patch('shop/{shopSlug}/product_category_edit/{id}','ProductController@categoryEditingSubmit')->name('shop.product_category.edit');

  Route::get('shop/{shopSlug}/product_minimum_edit/{id}','ProductController@minimumEdit')->name('shop.product_minimum.edit');
  Route::patch('shop/{shopSlug}/product_minimum_edit/{id}','ProductController@minimumEditingSubmit')->name('shop.product_minimum.edit');

  Route::get('shop/{shopSlug}/product_stock_edit/{id}','ProductController@stockEdit')->name('shop.product_stock.edit');
  Route::patch('shop/{shopSlug}/product_stock_edit/{id}','ProductController@stockEditingSubmit')->name('shop.product_stock.edit');

  Route::get('shop/{shopSlug}/product_price_edit/{id}','ProductController@priceEdit')->name('shop.product_price.edit');
  Route::patch('shop/{shopSlug}/product_price_edit/{id}','ProductController@priceEditingSubmit')->name('shop.product_price.edit');

  Route::get('shop/{shopSlug}/product_shipping_edit/{id}','ProductController@shippingEdit')->name('shop.product_shipping.edit');
  Route::patch('shop/{shopSlug}/product_shipping_edit/{id}','ProductController@shippingEditingSubmit')->name('shop.product_shipping.edit');

  Route::get('shop/{shopSlug}/product_notification_edit/{id}','ProductController@notificationEdit')->name('shop.product_notification.edit');
  Route::patch('shop/{shopSlug}/product_notification_edit/{id}','ProductController@notificationEditingSubmit')->name('shop.product_notification.edit');

  Route::get('shop/{shopSlug}/product_sale_promotion/{id}','ProductController@salePromotion')->name('shop.product_sale_promotion');

  Route::get('shop/{shopSlug}/product_discount/add/product_id:{product_id}','ProductDiscountController@add')->name('shop.product_discount.add');
  Route::post('shop/{shopSlug}/product_discount/add/product_id:{product_id}','ProductDiscountController@addingSubmit')->name('shop.product_discount.add');

  Route::get('shop/{shopSlug}/product_discount/edit/{id}/product_id:{product_id}','ProductDiscountController@edit')->name('shop.product_discount.edit');
  Route::patch('shop/{shopSlug}/product_discount/edit/{id}/product_id:{product_id}','ProductDiscountController@editingSubmit')->name('shop.product_discount.edit');

  Route::get('shop/{shopSlug}/product_branch/{id}','ProductController@branchEdit')->name('shop.product_branch.edit');
  Route::patch('shop/{shopSlug}/product_branch/{id}','ProductController@branchEditingSubmit')->name('shop.product_branch.edit');

});

// Payment Method
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/payment_method','ShopController@paymentMethod')->name('shop.payment_method');

  // Route::get('shop/{shopSlug}/payment_method/{id}','PaymentMethodController@detail')->name('shop.payment_method.detail');

  Route::get('shop/{shopSlug}/payment_method/add','PaymentMethodController@add')->name('shop.payment_method.add');
  Route::post('shop/{shopSlug}/payment_method/add','PaymentMethodController@addingSubmit')->name('shop.payment_method.add');

  Route::get('shop/{shopSlug}/payment_method/edit/{id}','PaymentMethodController@edit')->name('shop.payment_method.edit');
  Route::patch('shop/{shopSlug}/payment_method/edit/{id}','PaymentMethodController@editingSubmit')->name('shop.payment_method.edit');

  Route::get('shop/{shopSlug}/payment_method/delete/{id}','PaymentMethodController@delete')->name('shop.payment_method.delete');

});

// Shipping Method
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/shipping_method','ShopController@ShippingMethod')->name('shop.shipping_method');

  // Route::get('shop/{shopSlug}/shipping_method/{id}','ShippingMethodController@detail')->name('shop.shipping_method.detail');

  Route::get('shop/{shopSlug}/shipping_method/add','ShippingMethodController@add')->name('shop.shipping_method.add');
  Route::post('shop/{shopSlug}/shipping_method/add','ShippingMethodController@addingSubmit')->name('shop.shipping_method.add');

  Route::get('shop/{shopSlug}/shipping_method/edit/{id}','ShippingMethodController@edit')->name('shop.shipping_method.edit');
  Route::patch('shop/{shopSlug}/shipping_method/edit/{id}','ShippingMethodController@editingSubmit')->name('shop.shipping_method.edit');

  Route::get('shop/{shopSlug}/shipping_method/delete/{id}','ShippingMethodController@delete')->name('shop.shipping_method.delete');

  Route::post('shop/{shopSlug}/pickingup_item','ShippingMethodController@pickingUpItem')->name('shop.shipping_method.pickingup_item');

});

// Job
Route::get('job/list','JobController@listView')->name('job.list');
Route::get('job/detail/{id}','JobController@detail')->name('job.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/job','ShopController@job')->name('shop.job');

  Route::get('shop/{shopSlug}/job_applying','JobController@jobApplyingList')->name('shop.job.applying_list');
  Route::get('shop/{shopSlug}/job_applying_detail/{id}','JobController@jobApplyingDetail')->name('shop.job.applying_detail');
  
  // Route::get('shop/{shopSlug}/job_applying/new_message/{id}','JobController@jobApplyingNewMessage')->name('shop.job.applying.new_message');
  Route::post('shop/{shopSlug}/job_applying/new_message/{id}','JobController@jobApplyingMessageSend')->name('shop.job.applying.new_message');
  
  // Route::get('shop/{shopSlug}/job_applying/message_reply/{id}','JobController@jobApplyingMessageReply')->name('shop.job.applying.message_reply');
  Route::post('shop/{shopSlug}/job_applying/message_reply','JobController@jobApplyingMessageReplySend')->name('shop.job.applying.message_reply');

  Route::post('shop/{shopSlug}/job_applying/cancel/{id}','JobController@jobApplyingCancel')->name('shop.job.applying.cancel');

  Route::get('shop/{shopSlug}/job/post','JobController@add')->name('shop.job.add');
  Route::post('shop/{shopSlug}/job/post','JobController@addingSubmit')->name('shop.job.add');

  Route::get('shop/{shopSlug}/job/edit/{id}','JobController@edit')->name('shop.job.edit');
  Route::patch('shop/{shopSlug}/job/edit/{id}','JobController@editingSubmit')->name('shop.job.edit');

});

Route::group(['middleware' => ['auth','person.experience']], function () {
  Route::get('job/apply/{id}','JobController@apply');
  Route::post('job/apply/{id}','JobController@applyingSubmit');
});

// Branch
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/branch/manage','ShopController@branch')->name('shop.branch.manage');

  Route::get('shop/{shopSlug}/branch','BranchController@listView')->name('shop.branch.list');
  Route::get('shop/{shopSlug}/branch/{id}','BranchController@detail')->name('shop.branch.detail');

  Route::get('shop/{shopSlug}/branch/add','BranchController@add')->name('shop.branch.add');
  Route::post('shop/{shopSlug}/branch/add','BranchController@addingSubmit')->name('shop.branch.add');

  Route::get('shop/{shopSlug}/branch/edit/{id}','BranchController@edit')->name('shop.branch.edit');
  Route::patch('shop/{shopSlug}/branch/edit/{id}','BranchController@editingSubmit')->name('shop.branch.edit');

  Route::get('shop/{shopSlug}/branch/delete/{id}','BranchController@edit')->name('shop.branch.delete');
});

// Advertising
Route::get('advertising/list','AdvertisingController@listView')->name('advertising.list');
Route::get('advertising/detail/{id}','AdvertisingController@detail')->name('advertising.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/advertising','ShopController@advertising')->name('shop.advertising');
  
  Route::get('shop/{shopSlug}/shop_ad_post','AdvertisingController@add')->name('shop.advertising.add');
  Route::post('shop/{shopSlug}/shop_ad_post','AdvertisingController@addingSubmit')->name('shop.advertising.add');

  Route::get('shop/{shopSlug}/shop_ad_edit/{id}','AdvertisingController@edit')->name('shop.advertising.edit');
  Route::patch('shop/{shopSlug}/shop_ad_edit/{id}','AdvertisingController@editingSubmit')->name('shop.advertising.edit');

});

// Person Post Item
Route::get('item/list','ItemController@listView')->name('item.list');
Route::get('item/detail/{id}','ItemController@detail')->name('item.detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('item/post','ItemController@add')->name('item.post');
  Route::post('item/post','ItemController@addingSubmit')->name('item.post');

  Route::get('account/item_edit/{id}','ItemController@edit')->name('item.edit')->middleware('editing.permission');
  Route::patch('account/item_edit/{id}','ItemController@editingSubmit')->name('item.edit')->middleware('editing.permission');

  Route::get('account/item_delete/{id}','ItemController@delete')->name('item.delete');
});

// Real Estate
Route::get('real-estate/list','RealEstateController@listView');
Route::get('real-estate/detail/{id}','RealEstateController@detail')->name('real_estate.detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('real-estate/post','RealEstateController@add')->name('real_estate.post');
  Route::post('real-estate/post','RealEstateController@addingSubmit')->name('real_estate.post');

  Route::get('account/real_estate_edit/{id}','RealEstateController@edit')->name('real_estate.edit')->middleware('editing.permission');
  Route::patch('account/real_estate_edit/{id}','RealEstateController@editingSubmit')->name('real_estate.edit')->middleware('editing.permission');

  Route::get('account/real_estate_delete/{id}','RealEstateController@delete');
});

Route::group(['prefix' => 'api/v1', 'middleware' => 'api'], function () {
  Route::get('get_district/{provinceId}', 'ApiController@GetDistrict');
  Route::get('get_sub_district/{districtId}', 'ApiController@GetSubDistrict');
});

Route::group(['prefix' => 'api/v1', 'middleware' => ['api','auth']], function () {
  Route::get('get_shipping_method/{shippingMethodId}', 'ApiController@GetShippingMethodId');
  Route::get('get_category/{parentId?}', 'ApiController@GetCategory');
});

Route::group(['middleware' => ['api','auth']], function () {
  Route::post('upload_image', 'ApiController@uploadImage');
  Route::post('upload_profile_image', 'ApiController@uploadProfileImage')->name('Api.upload.profile_image');

  Route::post('upload_file_attachment', 'ApiController@uploadAttachedFile');
  Route::post('clear_file_attachment', 'ApiController@clearAttachedFile');

  Route::get('notification_update', 'ApiController@notificationUpdate');
  Route::get('notification_read', 'ApiController@notificationRead');
});

Route::group(['middleware' => 'api'], function () {
  Route::post('cart_add', 'ApiController@cartAdd');
  Route::post('cart_delete', 'CartController@cartDelete');
  Route::get('cart_update', 'ApiController@cartUpdate');
  Route::get('product_count', 'ApiController@productCount');
  Route::post('update_quantity', 'CartController@cartUpdateQuantity');
});

// Route::group(['namespace' => 'Admin'], function () {
//     // Controllers Within The "App\Http\Controllers\Admin" Namespace
// });