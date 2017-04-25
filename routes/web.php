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

  Route::get('account/profile/edit', 'AccountController@profileEdit')->name('account.profile.edit');
  Route::patch('account/profile/edit','AccountController@profileEditingSubmit')->name('account.profile.edit');

  Route::get('account/theme', 'AccountController@theme')->name('account.theme.edit');
  // Route::patch('account/theme','PersonExperienceController@themeEditingSubmit')->name('account.theme.edit');

  Route::get('account/item', 'AccountController@item')->name('account.item');
  Route::get('account/real_estate', 'AccountController@realEstate')->name('account.real_estate');
  Route::get('account/shop', 'AccountController@shop')->name('account.shop');
  Route::get('account/order', 'AccountController@order')->name('account.order');
  Route::get('account/notification', 'AccountController@notification')->name('account.notification');

  Route::get('account/job_applying', 'AccountController@jobApplying');
  Route::get('account/job_applying/{id}', 'JobController@accountJobApplyingDetail');

  Route::post('account/job_applying/job_position_accept/{id}', 'JobController@jobPositionAccept');
  Route::post('account/job_applying/job_position_decline/{id}', 'JobController@jobPositionDecline');

  Route::post('account/job_applying/new_message/{id}','JobController@jobApplyingMessageSend');

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
  Route::get('experience/working/edit/{id}','PersonWorkingExperienceController@edit');
  Route::patch('experience/working/edit/{id}','PersonWorkingExperienceController@editingSubmit');

  Route::get('experience/internship_add','PersonInternshipController@add')->name('person_experience.internship.add');
  Route::post('experience/internship_add','PersonInternshipController@addingSubmit')->name('person_experience.internship.add');
  Route::get('experience/internship/edit/{id}','PersonInternshipController@edit')->name('person_experience.internship.edit');
  Route::patch('experience/internship/edit/{id}','PersonInternshipController@editingSubmit')->name('person_experience.internship.edit');

  Route::get('experience/education_add','PersonEducationController@add');
  Route::post('experience/education_add','PersonEducationController@addingSubmit');
  Route::get('experience/education/edit/{id}','PersonEducationController@edit');
  Route::patch('experience/education/edit/{id}','PersonEducationController@editingSubmit');

  Route::get('experience/project_add','PersonProjectController@add');
  Route::post('experience/project_add','PersonProjectController@addingSubmit');
  Route::get('experience/project/edit/{id}','PersonProjectController@edit');
  Route::patch('experience/project/edit/{id}','PersonProjectController@editingSubmit');

  Route::get('experience/certificate_add','PersonCertificateController@add');
  Route::post('experience/certificate_add','PersonCertificateController@addingSubmit');
  Route::get('experience/certificate/edit/{id}','PersonCertificateController@edit');
  Route::patch('experience/certificate/edit/{id}','PersonCertificateController@editingSubmit');

  Route::get('experience/skill_add','PersonSkillController@add');
  Route::post('experience/skill_add','PersonSkillController@addingSubmit');
  Route::get('experience/skill/edit/{id}','PersonSkillController@edit');
  Route::patch('experience/skill/edit/{id}','PersonSkillController@editingSubmit');
  
  Route::get('experience/language_skill_add','PersonLanguageSkillController@add');
  Route::post('experience/language_skill_add','PersonLanguageSkillController@addingSubmit');
  Route::get('experience/language_skill/edit/{id}','PersonLanguageSkillController@edit');
  Route::patch('experience/language_skill/edit/{id}','PersonLanguageSkillController@editingSubmit');

});

// Freelance
Route::get('freelance/board','FreelanceController@board');
Route::get('freelance/board/{freelance_type_id}','FreelanceController@listView');
Route::get('freelance/detail/{id}','FreelanceController@detail')->name('freelance.detail');

Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('person/freelance','FreelanceController@manage')->name('person.freelance.manage');

  Route::get('person/freelance/queue/manage','FreelanceController@queueManage')->name('person.freelance.queue.manage');
  Route::get('person/freelance/queue/add','FreelanceController@queueAdd')->name('person.freelance.queue.add');

  Route::get('person/freelance/post','FreelanceController@add')->name('freelance.add');
  Route::post('person/freelance/post','FreelanceController@addingSubmit')->name('freelance.add');

  Route::get('person/freelance/edit/{id}','FreelanceController@edit')->name('freelance.edit')->middleware('editing.permission');;
  Route::patch('person/freelance/edit/{id}','FreelanceController@editingSubmit')->name('freelance.edit')->middleware('editing.permission');;

  Route::get('person/freelance/delete/{id}','FreelanceController@delete')->name('freelance.delete');

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
Route::get('community/shop','ShopController@listView');
// Route::get('community/shop_feature','ShopController@feature');
Route::group(['middleware' => ['auth','shop']], function () {
  Route::get('shop/{shopSlug}','ShopController@index')->name('shop.index');
});

Route::group(['middleware' => 'auth'], function () {
  Route::get('community/shop/create','ShopController@create')->name('shop.create');
  Route::post('community/shop/create','ShopController@creatingSubmit')->name('shop.create');
});
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}','ShopController@index')->name('shop.index');

  Route::get('shop/{shopSlug}/about','ShopController@about')->name('shop.about');

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
Route::get('product/shelf','ProductController@shelf')->name('product.shelf.all');
Route::get('product/shelf/{category_id}','ProductController@listView')->name('product.shelf');
Route::get('product/category/{category_id?}','ProductController@category')->name('product.category');
Route::get('product/detail/{id}','ProductController@detail')->name('product.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {


  Route::get('shop/{shopSlug}/product','ProductController@shopProductlistView')->name('shop.product.list');
  Route::get('shop/{shopSlug}/product/{id}','ProductController@shopProductDetail')->name('shop.product.detail');

  Route::get('shop/{shopSlug}/manage/product','ShopController@product')->name('shop.product.manage');
  Route::get('shop/{shopSlug}/manage/product/{id}','ProductController@menu')->name('shop.product.manage.menu');

  Route::get('shop/{shopSlug}/product/add','ProductController@add')->name('shop.product.add');
  Route::post('shop/{shopSlug}/product/add','ProductController@addingSubmit')->name('shop.product.add');

  Route::get('shop/{shopSlug}/product/edit/{id}','ProductController@edit')->name('shop.product.edit');
  Route::patch('shop/{shopSlug}/product/edit/{id}','ProductController@editingSubmit')->name('shop.product.edit');

  Route::get('shop/{shopSlug}/product/status/edit/{id}','ProductController@statusEdit')->name('shop.product_status.edit');
  Route::patch('shop/{shopSlug}/product/status/edit/{id}','ProductController@statusEditingSubmit')->name('shop.product_status.edit');

  Route::get('shop/{shopSlug}/product/specification/edit/{id}','ProductController@specificationEdit')->name('shop.product_specification.edit');
  Route::patch('shop/{shopSlug}/product/specification/edit/{id}','ProductController@specificationEditingSubmit')->name('shop.product_specification.edit');

  Route::get('shop/{shopSlug}/product/category/edit/{id}','ProductController@categoryEdit')->name('shop.product_category.edit');
  Route::patch('shop/{shopSlug}/product/category/edit/{id}','ProductController@categoryEditingSubmit')->name('shop.product_category.edit');

  Route::get('shop/{shopSlug}/product/minimum/edit/{id}','ProductController@minimumEdit')->name('shop.product_minimum.edit');
  Route::patch('shop/{shopSlug}/product/minimum/edit/{id}','ProductController@minimumEditingSubmit')->name('shop.product_minimum.edit');

  Route::get('shop/{shopSlug}/product/stock/edit/{id}','ProductController@stockEdit')->name('shop.product_stock.edit');
  Route::patch('shop/{shopSlug}/product/stock/edit/{id}','ProductController@stockEditingSubmit')->name('shop.product_stock.edit');

  Route::get('shop/{shopSlug}/product/price/edit/{id}','ProductController@priceEdit')->name('shop.product_price.edit');
  Route::patch('shop/{shopSlug}/product/price/edit/{id}','ProductController@priceEditingSubmit')->name('shop.product_price.edit');

  Route::get('shop/{shopSlug}/product/shipping/edit/{id}','ProductController@shippingEdit')->name('shop.product_shipping.edit');
  Route::patch('shop/{shopSlug}/product/shipping/edit/{id}','ProductController@shippingEditingSubmit')->name('shop.product_shipping.edit');

  Route::get('shop/{shopSlug}/product/notification/edit/{id}','ProductController@notificationEdit')->name('shop.product_notification.edit');
  Route::patch('shop/{shopSlug}/product/notification/edit/{id}','ProductController@notificationEditingSubmit')->name('shop.product_notification.edit');

  Route::get('shop/{shopSlug}/product/sale_promotion/{id}','ProductController@salePromotion')->name('shop.product_sale_promotion');

  Route::get('shop/{shopSlug}/product/discount/add/product_id:{product_id}','ProductDiscountController@add')->name('shop.product_discount.add');
  Route::post('shop/{shopSlug}/product/discount/add/product_id:{product_id}','ProductDiscountController@addingSubmit')->name('shop.product_discount.add');

  Route::get('shop/{shopSlug}/product/discount/edit/{id}/product_id:{product_id}','ProductDiscountController@edit')->name('shop.product_discount.edit');
  Route::patch('shop/{shopSlug}/product/discount/edit/{id}/product_id:{product_id}','ProductDiscountController@editingSubmit')->name('shop.product_discount.edit');

  Route::get('shop/{shopSlug}/product/branch/edit/{id}','ProductController@branchEdit')->name('shop.product_branch.edit');
  Route::patch('shop/{shopSlug}/product/branch/edit/{id}','ProductController@branchEditingSubmit')->name('shop.product_branch.edit');

  Route::get('shop/{shopSlug}/product_catalog','ProductCatalogController@listView')->name('shop.product_catalog');
  Route::get('shop/{shopSlug}/product_catalog/{id}','ProductCatalogController@productListView')->name('shop.product_catalog.list');

  Route::get('shop/{shopSlug}/manage/product_catalog','ShopController@productCatalog')->name('shop.product_catalog.manage');
  Route::get('shop/{shopSlug}/manage/product_catalog/{id}','ProductCatalogController@menu')->name('shop.product_catalog.menu');

  Route::get('shop/{shopSlug}/product_catalog/add','ProductCatalogController@add')->name('shop.product_catalog.add');
  Route::post('shop/{shopSlug}/product_catalog/add','ProductCatalogController@addingSubmit')->name('shop.product_catalog.add');

  Route::get('shop/{shopSlug}/product_catalog/edit/{id}','ProductCatalogController@edit')->name('shop.product_catalog.edit');
  Route::patch('shop/{shopSlug}/product_catalog/edit/{id}','ProductCatalogController@editingSubmit')->name('shop.product_catalog.edit');

  Route::get('shop/{shopSlug}/product_catalog/product_list/edit/{id}','ProductCatalogController@productCatalogEdit')->name('shop.product_catalog.product_list.edit');
  Route::patch('shop/{shopSlug}/product_catalog/product_list/edit/{id}','ProductCatalogController@productCatalogEditingSubmit')->name('shop.product_catalog.product_list.edit');

  Route::get('shop/{shopSlug}/product_catalog/delete/{id}','ProductCatalogController@delete')->name('shop.product_catalog.delete');

  Route::get('shop/{shopSlug}/product_catalog/delete/product/{id}/product_id:{product_id}','ProductCatalogController@deleteProduct')->name('shop.product_catalog.delete.product');

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
Route::get('job/board','JobController@board');
Route::get('job/board/{employment_type_id}','JobController@listView');
Route::get('job/detail/{id}','JobController@detail')->name('job.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/job','JobController@shopJoblistView')->name('shop.job.list');
  Route::get('shop/{shopSlug}/job/{id}','JobController@shopJobDetail')->name('shop.job.detail');

  Route::get('shop/{shopSlug}/manage/job','ShopController@job')->name('shop.job.manage');

  Route::get('shop/{shopSlug}/job_applying','JobController@jobApplyingList')->name('shop.job.applying_list');
  Route::get('shop/{shopSlug}/job_applying/detail/{id}','JobController@jobApplyingDetail')->name('shop.job.applying_detail');

  Route::post('shop/{shopSlug}/job_applying/new_message/{id}','JobController@jobApplyingMessageSend')->name('shop.job.applying.new_message');
  
  Route::post('shop/{shopSlug}/job_applying/message_reply','JobController@jobApplyingMessageReplySend')->name('shop.job.applying.message_reply');

  Route::post('shop/{shopSlug}/job_applying/accept/{id}','JobController@jobApplyingAccept')->name('shop.job.applying.accept');
  Route::post('shop/{shopSlug}/job_applying/passed/{id}','JobController@jobApplyingPassed')->name('shop.job.applying.passed');
  Route::post('shop/{shopSlug}/job_applying/not_pass/{id}','JobController@jobApplyingNotPass')->name('shop.job.applying.not_pass');
  Route::post('shop/{shopSlug}/job_applying/canceled/{id}','JobController@jobApplyingCancel')->name('shop.job.applying.canceled');

  Route::get('shop/{shopSlug}/job/add','JobController@add')->name('shop.job.add');
  Route::post('shop/{shopSlug}/job/add','JobController@addingSubmit')->name('shop.job.add');

  Route::get('shop/{shopSlug}/job/edit/{id}','JobController@edit')->name('shop.job.edit');
  Route::patch('shop/{shopSlug}/job/edit/{id}','JobController@editingSubmit')->name('shop.job.edit');

});

Route::group(['middleware' => ['auth','person.experience']], function () {
  Route::get('job/apply/{id}','JobController@apply');
  Route::post('job/apply/{id}','JobController@applyingSubmit');
  Route::patch('job/apply/{id}','JobController@applyingSubmit');
});

// Branch
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/manage/branch','ShopController@branch')->name('shop.branch.manage');

  Route::get('shop/{shopSlug}/branch','BranchController@listView')->name('shop.branch.list');
  Route::get('shop/{shopSlug}/branch/{id}','BranchController@detail')->name('shop.branch.detail');

  Route::get('shop/{shopSlug}/branch/add','BranchController@add')->name('shop.branch.add');
  Route::post('shop/{shopSlug}/branch/add','BranchController@addingSubmit')->name('shop.branch.add');

  Route::get('shop/{shopSlug}/branch/edit/{id}','BranchController@edit')->name('shop.branch.edit');
  Route::patch('shop/{shopSlug}/branch/edit/{id}','BranchController@editingSubmit')->name('shop.branch.edit');

  Route::get('shop/{shopSlug}/branch/delete/{id}','BranchController@edit')->name('shop.branch.delete');
});

// Advertising
Route::get('advertising/board','AdvertisingController@board');
Route::get('advertising/board/{advertising_type_id}','AdvertisingController@listView')->name('advertising.list');
Route::get('advertising/detail/{id}','AdvertisingController@detail')->name('advertising.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/advertising','AdvertisingController@shopAdvertisinglistView')->name('shop.advertising.list');
  Route::get('shop/{shopSlug}/advertising/{id}','AdvertisingController@shopAdvertisingDetail')->name('shop.advertising.detail');

  Route::get('shop/{shopSlug}/manage/advertising','ShopController@advertising')->name('shop.advertising.manage');
  
  Route::get('shop/{shopSlug}/advertising/add','AdvertisingController@add')->name('shop.advertising.add');
  Route::post('shop/{shopSlug}/advertising/add','AdvertisingController@addingSubmit')->name('shop.advertising.add');

  Route::get('shop/{shopSlug}/advertising/edit/{id}','AdvertisingController@edit')->name('shop.advertising.edit');
  Route::patch('shop/{shopSlug}/advertising/edit/{id}','AdvertisingController@editingSubmit')->name('shop.advertising.edit');

  Route::get('shop/{shopSlug}/advertising/delete/{id}','AdvertisingController@delete')->name('shop.advertising.delete');

});

// Person Post Item
Route::get('item/board','ItemController@board');
Route::get('item/board/{category_id}','ItemController@listView')->name('item.board');
Route::get('item/list','ItemController@listView')->name('item.list');
Route::get('item/detail/{id}','ItemController@detail')->name('item.detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('item/post','ItemController@add')->name('item.post');
  Route::post('item/post','ItemController@addingSubmit')->name('item.post');

  Route::get('account/item/edit/{id}','ItemController@edit')->name('item.edit')->middleware('editing.permission');
  Route::patch('account/item/edit/{id}','ItemController@editingSubmit')->name('item.edit')->middleware('editing.permission');

  Route::get('account/item_delete/{id}','ItemController@delete')->name('item.delete');
});

// Real Estate
Route::get('real-estate/board','RealEstateController@board');
Route::get('real-estate/board/{real_estate_type_id}','RealEstateController@listView');
Route::get('real-estate/detail/{id}','RealEstateController@detail')->name('real_estate.detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('real-estate/post','RealEstateController@add')->name('real_estate.post');
  Route::post('real-estate/post','RealEstateController@addingSubmit')->name('real_estate.post');

  Route::get('account/real_estate/edit/{id}','RealEstateController@edit')->name('real_estate.edit')->middleware('editing.permission');
  Route::patch('account/real_estate/edit/{id}','RealEstateController@editingSubmit')->name('real_estate.edit')->middleware('editing.permission');

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