<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Models\Slug;
use App\Models\PersonToShop;
use App\library\message;
use Closure;
use Route;

class CheckForPersonHasShopPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $name = Route::currentRouteName();

      $pages = array(
        'shop.manage' => true,
        'shop.setting' => true,
        'shop.edit.profile_image' => 'edit',
        'shop.edit.description' => 'edit',
        'shop.edit.address' => 'edit',
        'shop.edit.contact' => 'edit',
        'shop.edit.opening_hours' => 'edit',
        'shop.job' => true,
        'shop.job.add' => 'add',
        'shop.job.edit' => 'edit',
        'shop.job.apply_list' => true,
        'shop.job.apply_detail' => true,
        'shop.branch' => true,
        'shop.branch.add' => 'add',
        'shop.branch.edit' => 'edit',
        'shop.product' => true,
        'shop.product.add' => 'add',
        'shop.product.edit' => 'edit',
        'shop.advertising' => true,
        'shop.advertising.add' => 'add',
        'shop.advertising.edit' => 'edit',
      );

      if(empty($name) || empty($pages[$name])) {
        Message::display('ไม่อนุญาตให้เข้าถึงหน้านี้ได้','error');
        return redirect('/');
      }

      $personToShop = new PersonToShop;
      $person = $personToShop->getData(array(
        'conditions' => array(
          ['person_id','=',session()->get('Person.id')],
          ['shop_id','=',Slug::where('slug','like',$request->shopSlug)->select('model_id')->first()->model_id],
        ),
        'fields' => array('role_id'),
        'first' => true
      ));

      if(empty($person)) {
        Message::display('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้','error');
        return redirect('/');
      }

      $permissions = $person->role->getPermission();

      if(!$pages[$name] && empty($permissions[$pages[$name]])) {
        Message::display('ไม่อนุญาตให้แก้ไขร้านค้านี้ได้','error');
        return redirect('/');
      }

      // page level
      // who can access in this page?
      // admin = 1, can access all page
      // if(level <= pageLevel)
      // have 4 levels
      // just concept

      return $next($request);
    }
}
