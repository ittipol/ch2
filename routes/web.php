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

Route::get('ac','HomeController@addXxx');
Route::post('ac','HomeController@addXxxSub');

Route::get('cat','HomeController@addCat');
// Route::get('lan','HomeController@lanAdd');

Route::get('/',function(){
  // dd(session()->all());

  // foreach (session()->get('Shop') as $key => $value) {
  //   dd($value);
  // }

  return view('pages.announcement.create');

});

// 

Route::get('logout',function(){
  Auth::logout();
  Session::flush();
  return redirect('/');
});

// Login
Route::get('login','UserController@login');
Route::post('login','UserController@auth');

// Register
Route::get('register','UserController@registerForm')->middleware('guest');
Route::post('register','UserController@register')->middleware('guest');

Route::get('safe_image/{file}', 'StaticFileController@serveImages');
Route::group(['middleware' => 'auth'], function () {
  Route::get('avatar', 'StaticFileController@avatar');
});

// Announcement
Route::get('announcement/create','AnnouncementController@create');

// Experience
Route::group(['middleware' => 'auth'], function () {
  Route::get('experience','PersonExperienceController@manage')->name('person.experience.manage');
  Route::post('experience','PersonExperienceController@start')->name('person.experience.start');
});
Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('experience/profile','PersonExperienceController@profile')->name('person.experience.profile');

  Route::get('experience/profile_edit','PersonExperienceController@profileEdit');
  Route::patch('experience/profile_edit','PersonExperienceController@profileEditingSubmit');

  Route::get('experience/career_objective','PersonExperienceController@careerObjectiveEdit');
  Route::patch('experience/career_objective','PersonExperienceController@careerObjectiveEditingSubmit');

  Route::get('experience/working_add','PersonWorkingExperienceController@add');
  Route::post('experience/working_add','PersonWorkingExperienceController@addingSubmit');
  Route::get('experience/working_edit/{id}','PersonWorkingExperienceController@edit');
  Route::patch('experience/working_edit/{id}','PersonWorkingExperienceController@editingSubmit');

  Route::get('experience/internship_add','PersonInternshipController@add');
  Route::post('experience/internship_add','PersonInternshipController@addingSubmit');
  Route::get('experience/internship_edit/{id}','PersonInternshipController@edit');
  Route::patch('experience/internship_edit/{id}','PersonInternshipController@editingSubmit');

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
// Route::get('freelance','FreelanceController@index')->name('freelance.index');
// Route::get('freelance/list','FreelanceController@listView')->name('freelance.list');
Route::get('freelance/detail/{id}','FreelanceController@detail')->name('freelance.detail');

Route::group(['middleware' => ['auth','person.experience']], function () {

  Route::get('person/freelance','FreelanceController@manage')->name('person.freelance.manage');

  Route::get('person/freelance_post','FreelanceController@add')->name('person.freelance.add');
  Route::post('person/freelance_post','FreelanceController@addingSubmit')->name('person.freelance.add');

  Route::get('person/freelance_edit/{id}','FreelanceController@edit')->name('person.freelance.edit');
  Route::patch('person/freelance_edit/{id}','FreelanceController@editingSubmit')->name('person.freelance.edit');

  Route::get('person/freelance_delete/{id}','FreelanceController@delete')->name('person.freelance.delete');

});

// community / Shop

// Route::get('community/shop_feature','ShopController@feature');

Route::group(['middleware' => ['auth','shop']], function () {
  Route::get('shop/{shopSlug}','ShopController@index')->name('shop.index');
});

Route::group(['middleware' => 'auth'], function () {
  Route::get('community/shop_create','ShopController@create');
  Route::post('community/shop_create','ShopController@creatingSubmit');
});
Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {
  Route::get('shop/{shopSlug}/manage','ShopController@manage')->name('shop.manage');

  Route::get('shop/{shopSlug}/setting','ShopController@setting')->name('shop.setting');

  Route::get('shop/{shopSlug}/profile_image','ShopController@profileImage')->name('shop.edit.profile_image');
  Route::patch('shop/{shopSlug}/profile_image','ShopController@profileImageSubmit')->name('shop.edit.profile_image');

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
Route::group(['middleware' => 'auth'], function () {

  Route::get('shop/{shopSlug}/product','ShopController@product');

  Route::get('shop/{shopSlug}/product_post','ProductController@add')->name('shop.product.add');
  Route::post('shop/{shopSlug}/product_post','ProductController@addingSubmit')->name('shop.product.add');

  Route::get('shop/{shopSlug}/product_edit/{id}','ProductController@edit')->name('shop.product.edit');
  Route::patch('shop/{shopSlug}/product_edit/{id}','ProductController@editingSubmit')->name('shop.product.edit');

});
Route::get('product','ProductController@index');
Route::get('product/{product_slug}','ProductController@detail');

// Job
Route::get('job/list','JobController@listView')->name('job.list');;
Route::get('job/detail/{id}','JobController@detail')->name('job.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/job','ShopController@job')->name('shop.job');

  Route::get('shop/{shopSlug}/job_apply_list','JobController@jobApplyList')->name('shop.job.apply_list');
  Route::get('shop/{shopSlug}/job_apply_detail/{id}','JobController@jobApplyDetail')->name('shop.job.apply_detail');
  
  Route::get('shop/{shopSlug}/job_post','JobController@add')->name('shop.job.add');
  Route::post('shop/{shopSlug}/job_post','JobController@addingSubmit')->name('shop.job.add');

  Route::get('shop/{shopSlug}/job_edit/{id}','JobController@edit')->name('shop.job.edit');
  Route::patch('shop/{shopSlug}/job_edit/{id}','JobController@editingSubmit')->name('shop.job.edit');

});

Route::group(['middleware' => ['auth','person.experience']], function () {
  Route::get('job/apply/{id}','JobController@apply');
  Route::post('job/apply/{id}','JobController@applyingSubmit');
});

// Branch
Route::get('branch/list','BranchController@listView')->name('branch.list');
Route::get('branch/detail/{id}','BranchController@detail')->name('branch.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/branch','ShopController@branch')->name('shop.branch');

  // Route::get('shop/{shopSlug}/branch_detail/{id}','BranchController@detail')->name('shop.branch.detail');

  Route::get('shop/{shopSlug}/branch_add','BranchController@add')->name('shop.branch.add');
  Route::post('shop/{shopSlug}/branch_add','BranchController@addingSubmit')->name('shop.branch.add');

  Route::get('shop/{shopSlug}/branch_edit/{id}','BranchController@edit')->name('shop.branch.edit');
  Route::patch('shop/{shopSlug}/branch_edit/{id}','BranchController@editingSubmit')->name('shop.branch.edit');
});

// Advertising
Route::get('advertising/detail/{id}','AdvertisingController@detail')->name('advertising.detail');

Route::group(['middleware' => ['auth','shop','person.shop.permission']], function () {

  Route::get('shop/{shopSlug}/advertising','ShopController@advertising')->name('shop.advertising');
  
  Route::get('shop/{shopSlug}/shop_ad_post','AdvertisingController@add')->name('shop.advertising.add');
  Route::post('shop/{shopSlug}/shop_ad_post','AdvertisingController@addingSubmit')->name('shop.advertising.add');

  Route::get('shop/{shopSlug}/shop_ad_edit/{id}','AdvertisingController@edit')->name('shop.advertising.edit');
  Route::patch('shop/{shopSlug}/shop_ad_edit/{id}','AdvertisingController@editingSubmit')->name('shop.advertising.edit');

});

// Person Post Item
Route::get('item/list','ItemController@listView')->name('itme.list');
Route::get('item/detail/{id}','ItemController@detail')->name('itme.detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('item/post','ItemController@add')->name('itme.post');
  Route::post('item/post','ItemController@addingSubmit')->name('itme.post');

  Route::get('item/edit/{id}','ItemController@edit')->name('item.edit');
  Route::patch('item/edit/{id}','ItemController@editingSubmit')->name('item.edit');
});

// Real Estate
Route::get('real-estate/list','RealEstateController@listView');
Route::get('real-estate/detail/{id}','RealEstateController@detail');

Route::group(['middleware' => 'auth'], function () {
  Route::get('real-estate/post','RealEstateController@add');
  Route::post('real-estate/post','RealEstateController@addingSubmit');

  Route::get('real-estate/edit/{id}','RealEstateController@edit');
  Route::patch('real-estate/edit/{id}',[
    'uses' => 'RealEstateController@editingSubmit'
  ]);
});

Route::group(['prefix' => 'api/v1', 'middleware' => 'api'], function () {
  Route::get('get_district/{provinceId}', 'ApiController@GetDistrict');
  Route::get('get_sub_district/{districtId}', 'ApiController@GetSubDistrict');
});

Route::group(['middleware' => 'auth'], function () {
  Route::post('upload_image', 'ApiController@uploadImage');
  // Route::post('delete_image', 'ApiController@deleteImage');
});

// Route::group(['namespace' => 'Admin'], function () {
//     // Controllers Within The "App\Http\Controllers\Admin" Namespace
// });