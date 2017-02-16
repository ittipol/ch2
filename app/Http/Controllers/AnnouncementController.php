<?php

namespace App\Http\Controllers;

class AnnouncementController extends Controller
{
  public function create() {
    return $this->view('pages.announcement.create');
  }
}
