<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Models\Slug;
use App\library\messageHelper;
use App\library\url;
use Closure;

class CheckForShopSlug
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
    if(empty($request->shopSlug)) {
      return redirect('/');
    }
    
    $url = new Url;

    $slug = Slug::where('slug','like',$request->shopSlug)->select('model_id')->first();

    if(empty($slug)) {
      return response(view('errors.error',array(
        'error'=>array(
          'message'=>'ไม่พบร้านค้านี้'
        ))
      ));
    }

    $id = $slug->model_id;
    $shop = Shop::find($id);

    // get permission
    $request->attributes->add([
      'shopId' => $id,
      'shop' => $shop,
      'shopUrl' => $url->url('shop/'.$request->shopSlug)
    ]);

    return $next($request);
  }
}
