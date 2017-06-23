<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use App\Models\Person;
use App\library\token;

class CheckAuthSession
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
        if(Auth::check() && !Session::has('Person')) {
            $person = Person::select('id','name','profile_image_id','token')->where('user_id','=',Auth::user()->id)->first();
            
            // Store data
            Session::put('Person.id',$person->id);
            Session::put('Person.name',$person->name);
            Session::put('Person.token',$person->token);

            if(empty($person->profile_image_id)) {
              Session::put('Person.profile_image_xs','/images/common/avatar.png');
              Session::put('Person.profile_image','/images/common/avatar.png');
            }else{
              Session::put('Person.profile_image_xs',$person->getProfileImageUrl('xs'));
              Session::put('Person.profile_image',$person->getProfileImageUrl('xsm'));
            }
        }

        return $next($request);
    }
}
