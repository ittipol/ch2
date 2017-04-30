<?php

namespace App\Http\Middleware;

use App\Models\PersonExperience;
use App\library\messageHelper;
use Closure;

class CheckForPersonExperience
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
    $personExperience = new PersonExperience;
    if(!$personExperience->checkExistByPersonId()) {
      MessageHelper::display('กรุณาเพิ่มประวัติการทำงานเพื่อใช้ในการสมัครงาน','info');
      return redirect('person/experience');
    }

    return $next($request);
  }
}
