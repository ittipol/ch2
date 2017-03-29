<?php

namespace App\Models;

use App\library\token;
use Hash;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['email','password','token','api_token'];
    protected $hidden = ['password','remember_token'];
    protected $modelRelations = array('Person');
    protected $directory = true;

    protected $validation = array(
      'rules' => array(
        'Person.name' => 'required|max:255',
        // 'email' => 'required|email|unique:users,email',
        'email' => 'required|email',
        'password' => 'required|min:4|max:255|confirmed',
        // 'password_confirmation' => 'required',
        // 'avatar' => 'mimes:jpeg,jpg,png|max:1024',
        'birth_date' => 'date_format:Y-m-d'
      ),
      'messages' => array(
        'Person.name.required' => 'กรุณากรอกชื่อ',
        'email.required' => 'กรุณากรอกอีเมล',
        'email.email' => 'อีเมลไม่ถูกต้อง',
        // 'email.unique' => 'อีเมลถูกใช้งานแล้ว',
        'password.required' => 'กรุณากรอกรหัสผ่าน',
        'password.min' => 'รัสผ่านต้องมีอย่างน้อย 4 อักขระ',
        'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
        // 'password_confirmation.required' => 'กรุณากรอกรหัสผ่านอีกครั้ง',
        'birth_date.date_format' => 'รูปแบบวันที่ไม่ถูกต้อง'
      )
    ); 

    public function __construct() {  
      parent::__construct();
    }

    public static function boot()
    {
    	parent::boot();

    	User::saving(function($model){
        $model->password = Hash::make($model->attributes['password']);
        $model->api_token = Token::generate();
      });

      // User::saved(function($model){

      // });

    }

    public function createUserFolder() {

      $avatarFolder = storage_path($this->profileDirPath).$this->attributes['id'].'/avatar';
      $imageFolder = storage_path($this->profileDirPath).$this->attributes['id'].'/images';

      if(!is_dir($avatarFolder)){
        mkdir($avatarFolder,0777,true);
      }

      if(!is_dir($imageFolder)){
        mkdir($imageFolder,0777,true);
      }
      
    }

    public function avatar($avatar) {

      if(!empty($avatar)){  

        $img = $avatar->getRealPath();

        if (($img_info = getimagesize($img)) === false) {
          return false;
        }
        
        $width = $img_info[0];
        $height = $img_info[1];

        switch ($img_info[2]) {
          case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);  break;
          case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); break;
          case IMAGETYPE_PNG  : $src = imagecreatefrompng($img);  break;
          default : die("Unknown filetype");
        }

        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
        imagejpeg($tmp, storage_path($this->profileDirPath).$this->attributes['id'].'/avatar/avatar.jpg',100);
        imagedestroy($tmp);
        
      }
    }
}
