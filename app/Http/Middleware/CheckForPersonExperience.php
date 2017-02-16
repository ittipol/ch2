<?php

namespace App\Http\Middleware;

use App\Models\PersonExperience;
use App\library\message;
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
      Message::display('ไม่พบประวัติการทำงานของคุณ','info');
      return redirect('experience');
    }

    return $next($request);
  }
}
