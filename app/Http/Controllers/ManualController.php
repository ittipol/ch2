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

      case 'notification':
          $goto = 'pages.manual.notification';
        break;

      case 'profile-edit':
          $goto = 'pages.manual.profile-edit';
        break;

      case 'my-shop':
          $goto = 'pages.manual.my-shop';
        break;
      
      default:
          abort(404);
        break;
    }
    
    return $this->view($goto);

  }
}
