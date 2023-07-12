<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Public\CheckCodeRequest;
use App\Http\Requests\Api\Public\LoginRequest;
use App\Http\Requests\Api\Public\PasswordCodeRequest;
use App\Http\Requests\Api\Public\RegisterRequest;
use App\Http\Resources\Public\UserAuthResource;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Verifie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = auth('api')->attempt(["mobile" => $request->mobile, "password" => $request->password]);
        $user->token = $token;

        //send code after register
        $code =  rand(100000, 999999);
        $verify = new Verifie();

        $verify->mobile_or_email = auth('api')->user()->mobile;
        $verify->code = $code;
        $verify->save();

        return ResponseHelper::sendResponseSuccess(new UserAuthResource($user));
    }



    public function login(LoginRequest $request)
    {

        if (!$token = auth('api')->attempt(["mobile" => $request->mobile, "password" => $request->password])) {
            return ResponseHelper::sendResponseError([], Response::HTTP_BAD_REQUEST, __("messages.The mobile number or password is incorrect"));
        }

        $user = User::whereMobile($request->mobile)->first();

        if ($user->is_block == 1) {
            return ResponseHelper::sendResponseError([], Response::HTTP_BAD_REQUEST, __("messages.Your account has been restricted by the site administration"));
        }

        $user->token = $token;
        return ResponseHelper::sendResponseSuccess(new UserAuthResource($user));
    }

    //check code send is exist on data base or not
    public function checkcode(CheckCodeRequest $request){

        $code = $request->code;
        $verify = Verifie::where('code',$code)->where('mobile_or_email',auth('api')->user()->mobile)->first();
        if ($verify) {

            $user = User::whereId(auth('api')->user()->id)->first();
            $user->update(['mobile_verified_at'=> Carbon::now()]);
            $verify->delete();

            return ResponseHelper::sendResponseSuccess([], 200 , __("messages_1.you verify Data"));
        }else{

            return ResponseHelper::sendResponseError([], 400 ,  __("messages_1.the code you enter not equel code is send"));
        }

    }

    //resend code
    public function resendCode(){
        if(auth('api')->user()->mobile_verified_at !=null){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"You Already Verify Your Data .!");
        }else{
            $code =  rand(100000, 999999);
            Verifie::where('mobile_or_email' , auth('api')->user()->mobile)->delete();
            $verify = new Verifie();
            $verify->mobile_or_email = auth('api')->user()->mobile;
            $verify->code = $code;
            $verify->save();
            return ResponseHelper::sendResponseSuccess([], Response::HTTP_OK , __("messages_1.code Send again"));
        }
    }

    //Reset Password

    public function resetPassword(Request $request){
        $email = $request->email;
        $checkEmail = User::where('email',$email)->first();
        if(!$checkEmail){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"This email not Exist");
        }else{
            $code =  rand(100000, 999999);
            PasswordReset::create([
                'email' => $email,
                'token' => $code
            ]);

            $getCode = PasswordReset::whereEmail($email)->select('token')->first();
            if(!$getCode){
                return  ResponseHelper::sendResponseError([],'Not Code send yet');
            }
            mail::to($email)->send(new ResetPasswordMail($getCode));

            return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Check YourEmail");
        }
    }

    public function updatePassword(Request $request){
        $code = $request->code;
        $checkCode = PasswordReset::where('token',$code)->first();
          if($checkCode){
              $user = PasswordReset::where('token',$code)->select('email')->first();
              $email =$user->email;
              $user = User::where('email',$email)->first();
              $user->update(['password' => bcrypt($request->password)]);;
              return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Your Password Updated");
          }else{
              return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'This code not exist');
          }
    }

    public function logout(){
        try {
            auth('api')->logout();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"logout Successfully");
        }catch (\Exception $exception){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$exception->getMessage());

        }
    }

}
