<?php

namespace App\Http\Controllers;

use App\library\service;
use App\library\cache;
use Auth;
// use File;
use Response;

class StaticFileController extends Controller
{
  private $noImagePath = 'images/common/no-img.png';

  public function serveImages($filename){

    $cache = new Cache;
    $path = $cache->getCacheImagePath($filename);

    if(!empty($path)) {

      $headers = array(
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-cache, must-revalidate',
        'Cache-Control' => 'pre-check=0, post-check=0, max-age=0',
        'Content-Type' => mime_content_type($path),
        // 'Content-length' => filesize($path),
      );

      // return Response::make(file_get_contents($path), 200, $headers);

      return Response::download($path, $filename, $headers);
    }

    $image = Service::loadModel('Image')
    ->where('filename','like',$filename)
    ->select(array('model','model_id','filename','image_type_id'))
    ->first();

    if(empty($image)) {
      return response()->download($this->noImagePath, null, [], null);
    }

    $path = $image->getImagePath();

    if(file_exists($path)){

      $headers = array(
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-cache, must-revalidate',
        'Cache-Control' => 'pre-check=0, post-check=0, max-age=0',
        'Content-Type' => mime_content_type($path),
        // 'Content-length' => filesize($path),
      );

      // return Response::make(file_get_contents($path), 200, $headers);

      return Response::download($path, $filename, $headers);

    }

    return response()->download($this->noImagePath, null, [], null);

    // $headers = array(
    //   'Cache-Control' => 'no-cache, must-revalidate',
    //   // 'Cache-Control' => 'no-store, no-cache, must-revalidate',
    //   // 'Cache-Control' => 'pre-check=0, post-check=0, max-age=0',
    //   // 'Pragma' => 'no-cache',
    //   'Content-Type' => mime_content_type($path),
    //   // 'Content-Disposition' => 'inline; filename="'.$image->name.'"',
    //   // 'Content-Transfer-Encoding' => 'binary',
    //   'Content-length' => filesize($path),
    // );

    // return Response::make(file_get_contents($path), 200, $headers);

  }

  public function attachedFile($id) {

    $file = Service::loadModel('AttachedFile')->find($id);

    $path = $file->getImagePath();

    $headers = array(
      // 'Content-Description' => 'File Transfer',
      'Content-Disposition' => 'attachment; filename=' . $file->filename,
      'Content-Type' => mime_content_type($path),
      'Content-Length' => $file->filesize,
    );

    return Response::download($path, $file->filename, $headers);
    // return Response::make(file_get_contents($path), 200, $headers);
    // Service::loadModel('AttachedFileAccessPermission');
    dd(session()->get('Person.id'));

  }

}
