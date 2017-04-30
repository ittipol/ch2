<?php

namespace App\Http\Middleware;

use App\Models\DataAccessPermission;
use App\Models\AccessLevel;
use App\library\service;
use Closure;
use Route;
use Auth;

class CheckForDataAccessPermission
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
        // 'item.detail' => array(
        //   'modelName' => 'Item'
        // ),
        // 'real_estate.detail' => array(
        //   'modelName' => 'RealEstate'
        // ),
        // 'freelance.detail' => array(
        //   'modelName' => 'Freelance'
        // ),
        // 'person_experience.detail' => array(
        //   'modelName' => 'PersonExperience'
        // ),
      );

      $name = Route::currentRouteName();

      if(empty($name) || empty($pages[$name])) {
        return $next($request);
      }

      $pageLevel = DataAccessPermission::select('access_level')
      ->where([
        ['model','like',$pages[$name]['modelName']],
        ['model_id','=',$request->id]
      ])
      ->first()
      ->access_level;

      if(empty($pageLevel)) {
        return response(view('errors.error',array(
          'error'=>array(
            'message'=>'สิทธิการเข้าถึงหน้าไม่ถูกต้อง'
          ))
        ));
      }

      $hasPermission = true;
      switch ($pageLevel) {
        case 1:
          // only me can see
          $model = Service::loadModel($pages[$name]['modelName'])->select(array('created_by'))->find($request->id);

          if(!Auth::check() || empty($model) || ($model->created_by != session()->get('Person.id'))) {
            $hasPermission = false;
          }

          break;
        
        case 9:
          
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
