<?php

namespace App\Http\Controllers;

class ManualController extends Controller
{
  public function index() {
    
    if(empty($this->param['pageSlug'])) {
      return $this->view('pages.manual.index');
    }

    $goto = null;

    switch ($this->param['pageSlug']) {
      case 'ui-and-nav':
          $goto = 'pages.manual.ui-and-nav';
        break;

      case 'search':
          $goto = 'pages.manual.search';
        break;
      
      default:
          abort(404);
        break;
    }
    
    return $this->view($goto);

  }
}
