<?php

namespace App\Http\Middleware;

use App\library\service;
// use App\library\string;
use Closure;
use Route;

class CheckForEditingPermission
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
      if(empty($request->id)) {
        return redirect('home');
      }

      $pages = array(
        'item.edit' => array(
          'modelName' => 'Item'
        ),
        'real_estate.edit' => array(
          'modelName' => 'RealEstate'
        ),
        'freelance.edit' => array(
          'modelName' => 'Freelance'
        ),
      );

      $name = Route::currentRouteName();
    
      if(empty($name) || empty($pages[$name])) {
        return $next($request);
      }

      $model = Service::loadModel($pages[$name]['modelName'])->select(array('id','person_id'))->find($request->id);

      if(empty($model) || ($model->person_id != session()->get('Person.id'))) {
        return response(view('errors.error',array(
          'error'=>array(
            'message'=>'ขออภัย ไม่สามารถแก้ไขข้อมูลนี้ได้ หรือข้อมูลนี้อาจถูกลบแล้ว'
          ))
        ));
      }

      return $next($request);
    }
}
