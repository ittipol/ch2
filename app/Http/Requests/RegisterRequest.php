<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Response;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      return true;
    }

    public function messages()
    {
      return [
        'Person.name.required' => 'กรุณากรอกชื่อ',
        'email.required' => 'กรุณากรอกอีเมล',
        'email.email' => 'อีเมลไม่ถูกต้อง',
        // 'email.unique' => 'อีเมลถูกใช้งานแล้ว',
        'password.required' => 'กรุณากรอกรหัสผ่าน',
        'password.min' => 'รัสผ่านต้องมีอย่างน้อย 4 อักขระ',
        'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
        // 'password_confirmation.required' => 'กรุณากรอกรหัสผ่านอีกครั้ง',
        'birth_date.date_format' => 'รูปแบบวันที่ไม่ถูกต้อง'
      ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'Person.name' => 'required|max:255',
        // 'email' => 'required|email|unique:users,email',
        'email' => 'required|email',
        'password' => 'required|min:4|max:255|confirmed',
        // 'password_confirmation' => 'required',
        // 'avatar' => 'mimes:jpeg,jpg,png|max:1024',
        'birth_date' => 'date_format:Y-m-d'
      ];
    }

    public function forbiddenResponse()
    {
      return Response::make('Permission Denied!', 403);
    }

    public function response(array $errors) {
      return \Redirect::back()->withErrors($errors)->withInput();
    }

}
