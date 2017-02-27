<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Models\Slug;
use App\library\message;
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

        // $id = Slug::where('slug','like',$request->shopSlug)->select('model_id')->first()->model_id;

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

        // if($request->session()->has('Shop.'.$id.'.id')) {       
        //   $request->session()->put('Shop.'.$id.'.id',$id);
        //   $request->session()->put('Shop.'.$id.'.model',$shop);
        //   $request->session()->put('Shop.'.$id.'.role_name',$person->role->name);
        //   $request->session()->put('Shop.'.$id.'.role_permission',$person->role->getPermission());
        // }

        // get permission
        $request->attributes->add([
          'shopId' => $id,
          'shop' => $shop,
          'shopUrl' => $url->url('shop/'.$request->shopSlug)
        ]);

        return $next($request);
    }
}
