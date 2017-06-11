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

// Route::get('aa','HomeController@addC');
// Route::get('co','HomeController@co');

// Route::get('imm', function() {
//   $model = new App\Models\Image;

//   foreach ($model->get() as $value) {

//     $path = $value->getImagePath();

//     $xxx = $model->getOrientation($path);

//     if(empty($xxx)) {
//       continue;
//     }

//     $value->orientation = $xxx;
//     $value->save();
//   }

//   dd('DONE');

// });


// Route::get('cp','HomeController@catPath');

// Route::get('ac','HomeController@addXxx');
// Route::post('ac','HomeController@addXxxSub');

// Route::get('cat','HomeController@addCat');

// Route::get('/clear-cache', function() {
//     $exitCode = Artisan::call('cache:clear');
// });

// Route::get('/debug',function(){
//   dd(session()->all());
// });

// Route::get('/clear',function(){
//   Session::flush();
// });

// Route::get('/debug_notification',function(){
//   $notification = new App\Models\Notification;
//   $notification->getUnreadNotification();
// });

// Route::get('/cc',function(){
//   $cart = new App\Models\Cart;
//   $cart->productCount();
// });

// 

// 

// 

// 

// 

// 

// 
Route::get('/','HomeController@index');
Route::get('/home','HomeController@index');

Route::get('logout',function(){
  Auth::logout();
  Session::flush();
  return redirect('/');
});

// Login & Register
Route::group(['middleware' => 'guest'], function () {
  Route::get('login','UserController@login');
  Route::post('login','UserController@auth');

  Route::get('register','UserController@registerForm');
  Route::post('register','UserController@register');
});

Route::get('safe_image/{file}', 'StaticFileController@serveImages');
Route::get('redirect', 'UrlController@redirect');

// Search
Route::get('search','SearchController@index')->name('search');

// Manual
Route::get('manual/{pageSlug?}','ManualController@index');

// Account
Route::group(['middleware' => 'auth'], function () {

  Route::get('account', 'AccountController@account')->name('account');

  Route::get('account/profile/edit', 'AccountController@profileEdit')->name('account.profile.edit');
  Route::patch('account/profile/edit','AccountController@profileEditingSubmit')->name('account.profile.edit');

  // Route::get('account/theme', 'AccountController@theme')->name('account.theme.edit');
  // Route::patch('account/theme','PersonExperienceController@themeEditingSubmit')->name('account.theme.edit');

  // Route::get('account/item', 'AccountController@item')->name('account.item');

  // Route::get('account/real-estate', 'AccountController@realEstate')->name('account.real_estate');

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

// Experience
Route::get('experience/profile/list','PersonExperienceController@listView')->name('person_experience.list');
Route::get('experience/profile/{id}','PersonExperienceController@detail')->name('person_experience.detail')->middleware('data.access.permission');

Route::group(['middleware' => 'auth'], function () {
  Route::get('resume','PersonExperienceController@manage')->name('person_experience.manage');
  Route::post('resume','PersonExperienceController@start')->name('person_experience.start');
});

Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('resume/profile/edit', 'PersonExperienceController@profileEdit');
  Route::patch('resume/profile/edit','PersonExperienceController@profileEditingSubmit');

  Route::get('resume/edit','PersonExperienceController@resume')->name('person_experience.profile');
  Route::patch('resume/edit','PersonExperienceController@resumeEditingSubmit')->name('person_experience.profile.edit');

  Route::get('person/private_website/list','PersonPrivateWebsiteController@listView');
  Route::get('person/private_website/add','PersonPrivateWebsiteController@add');
  Route::post('person/private_website/add','PersonPrivateWebsiteController@addingSubmit');
  Route::get('person/private_website/edit/{id}','PersonPrivateWebsiteController@edit')->name('private_website.edit')->middleware('data.owner');
  Route::patch('person/private_website/edit/{id}','PersonPrivateWebsiteController@editingSubmit')->name('private_website.edit')->middleware('data.owner');
  Route::get('person/private_website/delete/{id}','PersonPrivateWebsiteController@delete')->name('private_website.delete')->middleware('data.owner');

  Route::get('experience/career_objective','PersonExperienceController@careerObjectiveEdit');
  Route::patch('experience/career_objective','PersonExperienceController@careerObjectiveEditingSubmit');

  Route::get('experience/working/add','PersonWorkingExperienceController@add');
  Route::post('experience/working/add','PersonWorkingExperienceController@addingSubmit');
  Route::get('experience/working/edit/{id}','PersonWorkingExperienceController@edit')->name('person_experience.working.edit')->middleware('data.owner');
  Route::patch('experience/working/edit/{id}','PersonWorkingExperienceController@editingSubmit')->name('person_experience.working.edit')->middleware('data.owner');
  Route::get('experience/working/delete/{id}','PersonWorkingExperienceController@delete')->name('person_experience.working.delete')->middleware('data.owner');

  Route::get('experience/internship/add','PersonInternshipController@add');
  Route::post('experience/internship/add','PersonInternshipController@addingSubmit');
  Route::get('experience/internship/edit/{id}','PersonInternshipController@edit')->name('person_experience.internship.edit')->middleware('data.owner');
  Route::patch('experience/internship/edit/{id}','PersonInternshipController@editingSubmit')->name('person_experience.internship.edit')->middleware('data.owner');
  Route::get('experience/internship/delete/{id}','PersonInternshipController@delete')->name('person_experience.internship.delete')->middleware('data.owner');

  Route::get('experience/education/add','PersonEducationController@add');
  Route::post('experience/education/add','PersonEducationController@addingSubmit');
  Route::get('experience/education/edit/{id}','PersonEducationController@edit')->name('person_experience.education.edit')->middleware('data.owner');
  Route::patch('experience/education/edit/{id}','PersonEducationController@editingSubmit')->name('person_experience.education.edit')->middleware('data.owner');
  Route::get('experience/education/delete/{id}','PersonEducationController@delete')->name('person_experience.education.delete')->middleware('data.owner');

  Route::get('experience/project/add','PersonProjectController@add');
  Route::post('experience/project/add','PersonProjectController@addingSubmit');
  Route::get('experience/project/edit/{id}','PersonProjectController@edit')->name('person_experience.project.edit')->middleware('data.owner');
  Route::patch('experience/project/edit/{id}','PersonProjectController@editingSubmit')->name('person_experience.project.edit')->middleware('data.owner');
  Route::get('experience/project/delete/{id}','PersonProjectController@delete')->name('person_experience.project.delete')->middleware('data.owner');

  Route::get('experience/certificate/add','PersonCertificateController@add');
  Route::post('experience/certificate/add','PersonCertificateController@addingSubmit');
  Route::get('experience/certificate/edit/{id}','PersonCertificateController@edit')->name('person_experience.certificate.edit')->middleware('data.owner');
  Route::patch('experience/certificate/edit/{id}','PersonCertificateController@editingSubmit')->name('person_experience.certificate.edit')->middleware('data.owner');
  Route::get('experience/certificate/delete/{id}','PersonCertificateController@delete')->name('person_experience.certificate.delete')->middleware('data.owner');

  Route::get('experience/skill/add','PersonSkillController@add');
  Route::post('experience/skill/add','PersonSkillController@addingSubmit');
  Route::get('experience/skill/edit/{id}','PersonSkillController@edit')->name('person_experience.skill.edit')->middleware('data.owner');
  Route::patch('experience/skill/edit/{id}','PersonSkillController@editingSubmit')->name('person_experience.skill.edit')->middleware('data.owner');
  Route::get('experience/skill/delete/{id}','PersonSkillController@delete')->name('person_experience.skill.delete')->middleware('data.owner');

  Route::get('experience/language_skill/add','PersonLanguageSkillController@add');
  Route::post('experience/language_skill/add','PersonLanguageSkillController@addingSubmit');
  Route::get('experience/language_skill/edit/{id}','PersonLanguageSkillController@edit')->name('person_experience.language_skill.edit')->middleware('data.owner');
  Route::patch('experience/language_skill/edit/{id}','PersonLanguageSkillController@editingSubmit')->name('person_experience.language_skill.edit')->middleware('data.owner');
  Route::get('experience/language_skill/delete/{id}','PersonLanguageSkillController@delete')->name('person_experience.language_skill.delete')->middleware('data.owner');

});

// Freelance
// Route::get('freelance/board','FreelanceController@board');
// Route::get('freelance/board/{freelance_type_id}','FreelanceController@listView');
// Route::get('freelance/detail/{id}','FreelanceController@detail')->name('freelance.detail');

// Route::group(['middleware' => ['auth','person.experience']], function () {

//   Route::get('person/freelance','FreelanceController@manage')->name('person.freelance.manage');

//   Route::get('person/freelance/queue/manage','FreelanceController@queueManage')->name('person.freelance.queue.manage');
//   Route::get('person/freelance/queue/add','FreelanceController@queueAdd')->name('person.freelance.queue.add');

//   Route::get('person/freelance/post','FreelanceController@add')->name('freelance.add');
//   Route::post('person/freelance/post','FreelanceController@addingSubmit')->name('freelance.add');

//   Route::get('person/freelance/edit/{id}','FreelanceController@edit')->name('freelance.edit')->middleware('data.owner');;
//   Route::patch('person/freelance/edit/{id}','FreelanceController@editingSubmit')->name('freelance.edit')->middleware('data.owner');

//   Route::get('person/freelance/delete/{id}','FreelanceController@delete')->name('freelance.delete')->middleware('data.owner');
// });

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
  
  Route::get('order/payment/inform/{id}', 'OrderController@paymentInform');
  Route::post('order/payment/inform/{id}', 'OrderController@paymentInformSubmit');

  Route::get('order/payment/inform/{id}/method:{payment_method_id}', 'OrderController@paymentInform');
  Route::post('order/payment/inform/{id}/method:{payment_method_id}', 'OrderController@paymentInformSubmit');

});

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/order','OrderController@shopOrder')->name('shop.order');
  Route::get('shop/{shopSlug}/order/{id}','OrderController@shopOrderDetail')->name('shop.order.detail');

  Route::get('shop/{shopSlug}/order/confirm/{id}','OrderController@shopOrderConfirm')->name('shop.order.confirm');
  Route::post('shop/{shopSlug}/order/confirm/{id}','OrderController@shopOrderConfirmSubmit')->name('shop.order.confirm');

  Route::patch('shop/{shopSlug}/order/cancel/{id}','OrderController@shopOrderCancel')->name('shop.order.cancel');

  Route::get('shop/{shopSlug}/order/payment/seller_confirm/{id}', 'OrderController@sellerPaymentConfirm')->name('shop.order.payment.seller_confirm');
  Route::post('shop/{shopSlug}/order/payment/seller_confirm/{id}', 'OrderController@sellerPaymentConfirmSubmit')->name('shop.order.payment.seller_confirm');

  Route::patch('shop/{shopSlug}/order/payment/confirm/{id}', 'OrderController@paymentConfirm')->name('shop.order.payment.confirm');

  Route::get('shop/{shopSlug}/order/payment/detail/{id}', 'OrderController@paymentDetail')->name('shop.order.payment.detail');

  Route::patch('shop/{shopSlug}/order/status/update/{id}', 'OrderController@updateOrderStatus')->name('shop.order.status.update');

  Route::get('shop/{shopSlug}/order/delete/{id}','OrderController@delete')->name('shop.order.delete');
});

// community / Shop
Route::get('shop/','ShopController@listView');

Route::group(['middleware' => 'auth'], function () {
  Route::get('shop/create','ShopController@create')->name('shop.create');
  Route::post('shop/create','ShopController@creatingSubmit')->name('shop.create');
});

Route::group(['middleware' => ['shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}','ShopController@index')->name('shop.index');

  Route::get('shop/{shopSlug}/about','ShopController@about')->name('shop.about');

  Route::get('shop/{shopSlug}/product','ProductController@shopProductlistView')->name('shop.product.list');
  Route::get('shop/{shopSlug}/product/{id}','ProductController@detail')->name('shop.product.detail');

  Route::get('shop/{shopSlug}/product_catalog','ProductCatalogController@listView')->name('shop.product_catalog');
  Route::get('shop/{shopSlug}/product_catalog/{id}','ProductCatalogController@productListView')->name('shop.product_catalog.list');

  Route::get('shop/{shopSlug}/job','JobController@shopJoblistView')->name('shop.job.list');
  Route::get('shop/{shopSlug}/job/{id}','JobController@detail')->name('shop.job.detail');

  // Route::get('shop/{shopSlug}/advertising','AdvertisingController@shopAdvertisinglistView')->name('shop.advertising.list');
  // Route::get('shop/{shopSlug}/advertising/{id}','AdvertisingController@shopAdvertisingDetail')->name('shop.advertising.detail');

  // Route::get('shop/{shopSlug}/branch','BranchController@listView')->name('shop.branch.list');
  // Route::get('shop/{shopSlug}/branch/{id}','BranchController@detail')->name('shop.branch.detail');
});
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/overview','ShopController@overview')->name('shop.overview');
  
  Route::get('shop/{shopSlug}/setting','ShopController@setting')->name('shop.setting');

  Route::get('shop/{shopSlug}/description','ShopController@description')->name('shop.edit.description');
  Route::patch('shop/{shopSlug}/description','ShopController@descriptionSubmit')->name('shop.edit.description');
  
  Route::get('shop/{shopSlug}/address','ShopController@address')->name('shop.edit.address');
  Route::patch('shop/{shopSlug}/address','ShopController@addressSubmit')->name('shop.edit.address');

  Route::get('shop/{shopSlug}/contact','ShopController@contact')->name('shop.edit.contact');
  Route::patch('shop/{shopSlug}/contact','ShopController@contactSubmit')->name('shop.edit.contact');

  Route::get('shop/{shopSlug}/opening_hours','ShopController@openingHours')->name('shop.edit.opening_hours');
  Route::patch('shop/{shopSlug}/opening_hours','ShopController@openingHoursSubmit')->name('shop.edit.opening_hours');

  Route::get('shop/{shopSlug}/delete','ShopController@delete')->name('shop.delete');
});

// PRODUCT
Route::get('product','ProductController@shelf')->name('product.shelf.all');
Route::get('product/{category_id}','ProductController@listView')->name('product.shelf');
Route::get('product/category/{category_id?}','ProductController@category')->name('product.category');
Route::get('product/detail/{id}','ProductController@detail')->name('product.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/manage/product','ShopController@product')->name('shop.product.manage');
  Route::get('shop/{shopSlug}/manage/product/{id}','ProductController@menu')->name('shop.product.manage.menu');

  Route::get('shop/{shopSlug}/product/add','ProductController@add')->name('shop.product.add');
  Route::post('shop/{shopSlug}/product/add','ProductController@addingSubmit')->name('shop.product.add');

  Route::get('shop/{shopSlug}/product/edit/{id}','ProductController@edit')->name('shop.product.edit');
  Route::patch('shop/{shopSlug}/product/edit/{id}','ProductController@editingSubmit')->name('shop.product.edit');

  Route::get('shop/{shopSlug}/product/delete/{id}','ProductController@delete')->name('shop.product.delete');

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

  Route::get('shop/{shopSlug}/product/option/{id}','ProductController@productOption')->name('shop.product_option');

  Route::get('shop/{shopSlug}/product/option/add/product_id:{product_id}','ProductOptionController@add')->name('shop.product_option.add');
  Route::post('shop/{shopSlug}/product/option/add/product_id:{product_id}','ProductOptionController@addingSubmit')->name('shop.product_option.add');

  Route::get('shop/{shopSlug}/product/option/edit/{id}/product_id:{product_id}','ProductOptionController@edit')->name('shop.product_option.edit');
  Route::patch('shop/{shopSlug}/product/option/edit/{id}/product_id:{product_id}','ProductOptionController@editingSubmit')->name('shop.product_option.edit');

  Route::get('shop/{shopSlug}/product/option/delete/{id}/product_id:{product_id}','ProductOptionController@delete')->name('shop.product_option.delete');

  Route::get('shop/{shopSlug}/product/option_value/add/product_option_id:{product_option_id}/product_id:{product_id}','ProductOptionController@optionValueAdd')->name('shop.product_option.value.add');
  Route::post('shop/{shopSlug}/product/option_value/add/product_option_id:{product_option_id}/product_id:{product_id}','ProductOptionController@optionValueAddingSubmit')->name('shop.product_option.value.add');

  Route::get('shop/{shopSlug}/product/option_value/edit/{id}/product_option_id:{product_option_id}/product_id:{product_id}','ProductOptionController@optionValueEdit')->name('shop.product_option.value.edit');
  Route::patch('shop/{shopSlug}/product/option_value/edit/{id}/product_option_id:{product_option_id}/product_id:{product_id}','ProductOptionController@optionValueEditingSubmit')->name('shop.product_option.value.edit');

  Route::get('shop/{shopSlug}/product/option_value/delete/{id}/product_option_id:{product_option_id}/product_id:{product_id}','ProductOptionController@optionValueDelete')->name('shop.product_option.value.delete');

  Route::get('shop/{shopSlug}/product/sale_promotion/{id}','ProductController@salePromotion')->name('shop.product_sale_promotion');

  Route::get('shop/{shopSlug}/product/discount/add/product_id:{product_id}','ProductDiscountController@add')->name('shop.product_discount.add');
  Route::post('shop/{shopSlug}/product/discount/add/product_id:{product_id}','ProductDiscountController@addingSubmit')->name('shop.product_discount.add');

  Route::get('shop/{shopSlug}/product/discount/edit/{id}/product_id:{product_id}','ProductDiscountController@edit')->name('shop.product_discount.edit');
  Route::patch('shop/{shopSlug}/product/discount/edit/{id}/product_id:{product_id}','ProductDiscountController@editingSubmit')->name('shop.product_discount.edit');

  Route::get('shop/{shopSlug}/product/discount/delete/{id}/product_id:{product_id}','ProductDiscountController@delete')->name('shop.product_discount.delete');

  // Route::get('shop/{shopSlug}/product/branch/edit/{id}','ProductController@branchEdit')->name('shop.product_branch.edit');
  // Route::patch('shop/{shopSlug}/product/branch/edit/{id}','ProductController@branchEditingSubmit')->name('shop.product_branch.edit');

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

  Route::post('shop/{shopSlug}/timeline/post','ShopController@postMessage')->name('shop.timeline.post');
  Route::get('shop/{shopSlug}/timeline/pinned/cancel/{id}','ShopController@cancelMessage')->name('shop.timeline.pinned.cancel');
  Route::get('shop/{shopSlug}/timeline/delete/{id}','ShopController@deleteMessage')->name('shop.timeline.delete');

});

// Payment Method
Route::group(['middleware' => ['shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/payment_method','PaymentMethodController@listView')->name('shop.payment_method.list');
});

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/manage/payment_method','ShopController@paymentMethod')->name('shop.payment_method.manage');

  Route::get('shop/{shopSlug}/payment_method/add/{type}','PaymentMethodController@add')->name('shop.payment_method.add');
  Route::post('shop/{shopSlug}/payment_method/add/{type}','PaymentMethodController@addingSubmit')->name('shop.payment_method.add');

  Route::get('shop/{shopSlug}/payment_method/edit/{id}','PaymentMethodController@edit')->name('shop.payment_method.edit');
  Route::patch('shop/{shopSlug}/payment_method/edit/{id}','PaymentMethodController@editingSubmit')->name('shop.payment_method.edit');

  Route::get('shop/{shopSlug}/payment_method/delete/{id}','PaymentMethodController@delete')->name('shop.payment_method.delete');
});

// Shipping Method
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/manage/shipping_method','ShopController@ShippingMethod')->name('shop.shipping_method.manage');

  Route::get('shop/{shopSlug}/shipping_method/add','ShippingMethodController@add')->name('shop.shipping_method.add');
  Route::post('shop/{shopSlug}/shipping_method/add','ShippingMethodController@addingSubmit')->name('shop.shipping_method.add');

  Route::get('shop/{shopSlug}/shipping_method/edit/{id}','ShippingMethodController@edit')->name('shop.shipping_method.edit');
  Route::patch('shop/{shopSlug}/shipping_method/edit/{id}','ShippingMethodController@editingSubmit')->name('shop.shipping_method.edit');

  Route::get('shop/{shopSlug}/shipping_method/delete/{id}','ShippingMethodController@delete')->name('shop.shipping_method.delete');

  Route::post('shop/{shopSlug}/pickingup_item','ShippingMethodController@pickingUpItem')->name('shop.shipping_method.pickingup_item');
});

// Job
Route::get('job','JobController@board');
Route::get('job/{employment_type_id}','JobController@listView');
Route::get('job/detail/{id}','JobController@detail')->name('job.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
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

  Route::get('shop/{shopSlug}/job/delete/{id}','JobController@delete')->name('shop.job.delete');
});

Route::group(['middleware' => 'auth'], function () {
Route::get('job/apply/{id}','JobController@apply');
  Route::post('job/apply/{id}','JobController@applyingSubmit');
  Route::patch('job/apply/{id}','JobController@applyingSubmit');
});

// Advertising
// Route::get('advertising/board','AdvertisingController@board');
// Route::get('advertising/board/{advertising_type_id}','AdvertisingController@listView')->name('advertising.list');
// Route::get('advertising/detail/{id}','AdvertisingController@detail')->name('advertising.detail');

// Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
//   Route::get('shop/{shopSlug}/manage/advertising','ShopController@advertising')->name('shop.advertising.manage');
  
//   Route::get('shop/{shopSlug}/advertising/add','AdvertisingController@add')->name('shop.advertising.add');
//   Route::post('shop/{shopSlug}/advertising/add','AdvertisingController@addingSubmit')->name('shop.advertising.add');

//   Route::get('shop/{shopSlug}/advertising/edit/{id}','AdvertisingController@edit')->name('shop.advertising.edit');
//   Route::patch('shop/{shopSlug}/advertising/edit/{id}','AdvertisingController@editingSubmit')->name('shop.advertising.edit');

//   Route::get('shop/{shopSlug}/advertising/delete/{id}','AdvertisingController@delete')->name('shop.advertising.delete');
// });

// Branch
// Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
//   Route::get('shop/{shopSlug}/manage/branch','ShopController@branch')->name('shop.branch.manage');

//   Route::get('shop/{shopSlug}/branch/add','BranchController@add')->name('shop.branch.add');
//   Route::post('shop/{shopSlug}/branch/add','BranchController@addingSubmit')->name('shop.branch.add');

//   Route::get('shop/{shopSlug}/branch/edit/{id}','BranchController@edit')->name('shop.branch.edit');
//   Route::patch('shop/{shopSlug}/branch/edit/{id}','BranchController@editingSubmit')->name('shop.branch.edit');

//   Route::get('shop/{shopSlug}/branch/delete/{id}','BranchController@delete')->name('shop.branch.delete');
// });

// Person Post Item
// Route::get('item/board','ItemController@board');
// Route::get('item/board/{category_id}','ItemController@listView')->name('item.board');
// Route::get('item/list','ItemController@listView')->name('item.list');
// Route::get('item/detail/{id}','ItemController@detail')->name('item.detail');

// Route::group(['middleware' => 'auth'], function () {
//   Route::get('item/post','ItemController@add')->name('item.post');
//   Route::post('item/post','ItemController@addingSubmit')->name('item.post');

//   Route::get('account/item/edit/{id}','ItemController@edit')->name('item.edit')->middleware('data.owner');
//   Route::patch('account/item/edit/{id}','ItemController@editingSubmit')->name('item.edit')->middleware('data.owner');

//   Route::get('account/item/delete/{id}','ItemController@delete')->name('item.delete')->middleware('data.owner');
// });

// Real Estate
// Route::get('real-estate/board','RealEstateController@board');
// Route::get('real-estate/board/{real_estate_type_id}','RealEstateController@listView');
// Route::get('real-estate/detail/{id}','RealEstateController@detail')->name('real_estate.detail');

// Route::group(['middleware' => 'auth'], function () {
//   Route::get('real-estate/post','RealEstateController@add')->name('real_estate.post');
//   Route::post('real-estate/post','RealEstateController@addingSubmit')->name('real_estate.post');

//   Route::get('account/real-estate/edit/{id}','RealEstateController@edit')->name('real_estate.edit')->middleware('data.owner');
//   Route::patch('account/real-estate/edit/{id}','RealEstateController@editingSubmit')->name('real_estate.edit')->middleware('data.owner');

//   Route::get('account/real-estate/delete/{id}','RealEstateController@delete')->name('real_estate.delete')->middleware('data.owner');;
// });

Route::group(['prefix' => 'api/v1', 'middleware' => 'api'], function () {
  Route::get('get_district/{provinceId}', 'ApiController@getDistrict');
  Route::get('get_sub_district/{districtId}', 'ApiController@getSubDistrict');
});

Route::group(['prefix' => 'api/v1', 'middleware' => ['api','auth']], function () {
  Route::get('get_shipping_method/{shippingMethodId}', 'ApiController@getShippingMethodId');
  Route::get('get_category/{parentId?}', 'ApiController@getCategory');
});

Route::group(['middleware' => ['api','auth']], function () {
  Route::post('upload_image', 'ApiController@uploadImage');
  Route::post('upload_profile_image', 'ApiController@uploadProfileImage')->name('api.upload.profile_image');
  Route::post('delete_profile_image', 'ApiController@deleteProfileImage')->name('api.delete.profile_image');

  Route::post('upload_file_attachment', 'ApiController@uploadAttachedFile');
  Route::post('clear_file_attachment', 'ApiController@clearAttachedFile');

  Route::post('user_review', 'ApiController@userReview');
  Route::post('user_review_delete', 'ApiController@userReviewDelete');

  Route::get('notification_update', 'ApiController@notificationUpdate');
  Route::get('notification_read', 'ApiController@notificationRead');
});

Route::group(['middleware' => 'api'], function () {
  Route::post('cart_add', 'ApiController@cartAdd');
  Route::post('cart_delete', 'CartController@cartDelete');
  Route::post('cart_update', 'ApiController@cartUpdate');
  Route::post('update_quantity', 'CartController@cartUpdateQuantity');
  Route::get('product_count', 'ApiController@productCount');

  Route::get('review_list', 'ApiController@reviewList');
});

// Route::group(['namespace' => 'Admin'], function () {
//     // Controllers Within The "App\Http\Controllers\Admin" Namespace
// });