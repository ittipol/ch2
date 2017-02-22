<?php

namespace App\Http\Middleware;

use App\Models\DataAccessPermission;
use App\Models\PageLevel;
use App\library\service;
use Closure;
use Route;
use Auth;

class CheckForPagePermission
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
        'item.detail' => array(
          'modelName' => 'Item'
        ),
        'real_estate.detail' => array(
          'modelName' => 'RealEstate'
        ),
        'freelance.detail' => array(
          'modelName' => 'Freelance'
        ),
      );

      $name = Route::currentRouteName();

      if(empty($name) || empty($pages[$name])) {
        return $next($request);
      }

      $pageAccessPermission = DataAccessPermission::select('page_level_id')
      ->where([
        ['model','like',$pages[$name]['modelName']],
        ['model_id','=',$request->id]
      ])->first();


      $hasPermission = true;
      switch ($pageAccessPermission->page_level_id) {
        case 1:
          // only me can see
          $model = Service::loadModel($pages[$name]['modelName'])->select(array('person_id'))->find($request->id);

          if(!Auth::check() || empty($model) || ($model->person_id != session()->get('Person.id'))) {
            $hasPermission = false;
          }

          break;
        
        case 5:
          
          if(!Auth::check()) {
            $hasPermission = false;
          }

          break;

      }

      if(!$hasPermission) {
        return response(view('errors.error',array(
          'error'=>array(
            'message'=>'ขออภัย คุณไม่สามารถเข้าถึงหน้านี้ได้'
          ))
        ));
      }

      return $next($request);
    }
}
