<?php

namespace App\Http\Controllers\api\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendCode;
use App\services\AuthService;
use App\traits\Generaltrait;
use Exception;

class Authcintroller extends Controller
{    use Generaltrait;
    public function register(Request $request)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255', 'regex: /(?-i)(?=^.{8,}$)((?!.*\s)(?=.*[A-Z])(?=.*[a-z]))(?=(1)(?=.*\d)|.*[^A-Za-z0-9])^.*$/'],
                'email' => ['required', 'string', 'max:255', 'regex:/^(?:(?:[\w\.\-_]+@[\w\d]+(?:\.[\w]{2,6})+)[,;]?\s?)+$/'],
                'phone' => ['required', 'string', 'digits:11']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['success'=>false,"data" => "['errors' => $errors]", "status" => 400, 'message' => "Validation Error has occured"]);
            } else {
                $data = $request->all();
                $data['password'] = Hash::make($data['password']);
                $user = User::create($data);
                $credentials = request(['email', 'password']);
                $token = auth('api')->attempt($credentials);
                $user->token = "Bearer " . $token;
                return response()->json(['success'=>true,"data" => ['user' => $user], "status" => 200, 'message' => "User has been registered"]);
            }
        } catch (Exception $e) {
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function login(Request $request,AuthService $service)
    {
        try {
            if ($errors = $service::validatecredentials($request)) {
                return response()->json(['success'=>false,"data" => ['errors' => $errors], "status" => 400, 'message' => "Validation Error has occured"]);
            }
            $credentials = request(['email', 'password']);
            if ($token = auth('api')->attempt($credentials)) {
                $user = auth('api')->user();
                $user->token = "Bearer " . $token;
                if (!$user->email_verified_at)
                    return response()->json(['success'=>true,"data" => ['user'=>$user], "status" => 400, 'message' => "User is not verified"]);
                else {
                    return response()->json(['success'=>true,"data" => ['user' => $user], "status" => 200, 'message' => "User has been successfully login"]);
                }
            }
            return response()->json(['success'=>false,"data" => "[]", "status" => 400, 'message' => "User credentials are incorrect"]);
        } catch (Exception $e) {
            return response()->json(['success'=>false,"data" => "[]", "status" => 500, 'message' => "Something went wrong"]);
        }
    }
    public function sendCode(Request $request,AuthService $service)
    {
        try {
            $user = auth('api')->user();
                $token = $request->header('Authorization');
                $exist = user::find($user->id);
                if ($exist) {
                    $service::updatecode($exist);
                    $exist->token = $token;
                    return response()->json(['success'=>true,"data" => ['user' => $exist], "status" => 200, 'message' => "Verification mail code has been sent"]);
                }
                return response()->json(['success'=>false,"data" => [], "status" => 404, 'message' => "User Not found"]);

        } catch (Exception $e) {
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function verifyCode(Request $request,AuthService $service)
    {
        try {
            $user = auth('api')->user();
                $token = $request->header('Authorization');
                $exist = user::find($user->id);
                if ($exist) {
                    if ($service::checkcode($exist, $request->code)) {
                        $exist->token = $token;
                        return response()->json(['success'=>true,"data" => ['user' => $exist], "status" => 200, 'message' => "User has been verified"]);
                    } else
                        return response()->json(['success'=>false,"data" => [], "status" => 400, 'message' => "user has not been verified"]);
                }

                return response()->json(['success'=>false,"data" => [], "status" => 404, 'message' => "User Not found"]);

        } catch (Exception $e) {
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function forgetPassword(Request $request,AuthService $service)
    {
        try {
            if ($errors = $service::validateemail($request->email)) {
                return response()->json(['success'=>false,"data" => ['errors' => $errors], "status" => 400, 'message' => "Validation error"]);
            }
            $user = user::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['success'=>false,"data" => [], "status" => 400, 'message' => "This user is not exist"]);
            }
            $service::updatecode($user);
            return response()->json(['success'=>true,"data" => [], "status" => 200, 'message' => "Reset mail code has been sent"]);
        } catch (exception $e) {
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function verifyForgetCode(Request $request,AuthService $service)
    {
        try {
            if ($errors = $service::validateemail($request->email)) {
                return response()->json(['success'=>false,"data" => ['errors' => $errors], "status" => 400, 'message' => "Validation error"]);
            }
            $user = user::where('email', $request->email)->first();
            if ($user) {
                if ($service::checkcode($user, $request->code))
                    return response()->json(['success'=>true,"data" => [], "status" => 200, 'message' => "User has been verified"]);
                else
                    return response()->json(['success'=>false,"data" => [], "status" => 400, 'message' => "user has not been verified"]);
            }
            return response()->json(['success'=>false,"data" => [], "status" => 404, 'message' => "User Not found"]);
        } catch (Exception $e) {
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function setNewPassword(Request $request,AuthService $service){
        try{
            if($errors=$service::validatecredentials($request)){
                return response()->json(['success'=>false,"data" => ['errors' => $errors], "status" => 400, 'message' => "Validation Error has occured"]);
            }
            $user=User::where('email',$request->email)->first();
            if(!$user){
                return response()->json(['success'=>false,"data" => [], "status" => 404, 'message' => "User Not found"]);
            }
            $user->password=Hash::make($request->password);
            $user->save();
            $user->token=auth('api')->attempt($request->only('email','password'));
            return response()->json(['success'=>true,"data" => ['user' => $user], "status" => 200, 'message' => "Password has been renewed"]);

        }catch(Exception $e){
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function profile(Request $request){
        try{
            $user=auth('api')->user();
            $userDB=user::find($user->id);
            if($userDB){
              $userDB->token=$request->header('Authorization') ;
              return response()->json(['success'=>true,"data" => ['user'=>$userDB], "status" => 200, 'message' => "User data has been correctly retrieved"]); }
            else{
                return response()->json(['success'=>false,"data" => [], "status" => 400, 'message' => "User Not Found"]);
            }
        }catch(Exception $e){
            return response()->json(['success'=>false,"data" => [], "status" => 500, 'message' => "something went wrong"]);
        }

    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['success'=>true,"data" => [], "status" => 200, 'message' => "User has been logout"]);
    }



}
