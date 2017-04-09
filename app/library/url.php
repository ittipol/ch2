<?php

namespace App\library;

class Url
{
  private $urls = array();

  public function url($url,$check=true) {

    $url = url($url);

    if($check) {
      $url = $this->addSlash($url);
    }

    return $url;
  }

  public function addSlash($str) {
    if(substr($str, -1) != '/') {
      $str .= '/';
    }
    return $str;
  }

  public function getUrls() {
    return $this->urls;
  }

  public function setUrl($url,$index) {

    preg_match_all('/{[\w0-9]+}/', $url, $matches);

    $this->urls[$index] = array(
      'url' => url($url),
      'pattern' => $matches[0]
    );
  }

  public function setAndParseUrl($url,$data) {

    preg_match_all('/{[\w0-9]+}/', $url, $matches);

    $url = array(
      'url' => url($url),
      'pattern' => $matches[0]
    );

    foreach ($url['pattern'] as $pattern) {
    
      $field = substr($pattern, 1,-1);

      if(!empty($data[$field])) {
        $url['url'] = str_replace($pattern, $data[$field], $url['url']);
      }

    }

    return $url['url'];

  }

  public function clearUrls() {
    $this->urls = array();
  }

  public function parseUrl($data) {
    $urls = array();

    foreach ($this->urls as $index => $url) {

      foreach ($url['pattern'] as $pattern) {
    
        $field = substr($pattern, 1,-1);

        if(!empty($data[$field])) {
          $url['url'] = str_replace($pattern, $data[$field], $url['url']);
        }

      }

      $urls[$index] = $url['url'];

    }

    return $urls;
  }

  public function redirect($url,$ssl=false) {

    if((substr($url, 0,7) == 'http://') || (substr($url, 0,8) == 'https://')) {
      return $this->url('redirect?url='.$url);
    }

    if($ssl) {
      $url = 'https://'.$url;
    }else{
      $url = 'http://'.$url;
    }

    return $this->url('redirect?url='.$url);
  }

  public function download() {
    //download/{code}
  }

}