<?php
namespace App\services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendCode;

Class AuthService{
    public static function validateemail($email)
    {
        $rules = [
            'email' => ['required', 'string', 'max:255', 'regex:/^(?:(?:[\w\.\-_]+@[\w\d]+(?:\.[\w]{2,6})+)[,;]?\s?)+$/']
        ];
        $validator = Validator::make(["email" => $email], $rules);
        if ($validator->fails())
            return $validator->errors();
        else return [];
    }
    public static function validatecredentials($request)
    {
        $rules = [
            'password' => ['required', 'string', 'max:255', 'regex: /(?-i)(?=^.{8,}$)((?!.*\s)(?=.*[A-Z])(?=.*[a-z]))(?=(1)(?=.*\d)|.*[^A-Za-z0-9])^.*$/'],
            'email' => ['required', 'string', 'max:255', 'regex:/^(?:(?:[\w\.\-_]+@[\w\d]+(?:\.[\w]{2,6})+)[,;]?\s?)+$/']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return $validator->errors();
        else return [];
    }
    public static  function updatecode($user)
    {
        $code = rand(10000, 99999);
        $user->code = $code;
        $user->save();
        Mail::to($user->email)->send(new sendcode($user));
        return true;
    }
    public static function checkcode($user, $code)
    {
        if ($user->code == $code) {
            if (!$user->email_verified_at) {
                $user->email_verified_at = date('Y-m-d h:i:sa');
                $user->save();
            }
            return true;
        } else return false;
    }
}
?>
