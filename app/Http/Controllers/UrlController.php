<?php

namespace App\Http\Controllers;

use Redirect;

class UrlController extends Controller
{
  public function redirect() {

    if(empty($this->query['url'])) {
      return Redirect::to('/');
    }

    return Redirect::to($this->query['url']);

  }
}
