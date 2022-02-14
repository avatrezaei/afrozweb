<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $username;


    public function login(Request $request)
    {

        $validator = $this->validateLogin($request);

        if ($validator->fails()) {
            return response()->json([
                'code'=>422,
                'status'=>'ok',
                'message'=>[$validator->errors()->all()],
            ],422);
        }

        $credentials = request([$this->username, 'password']);
        if(!Auth::attempt($credentials)){
            $response[] = 'Unauthorized user';
            return response()->json([
                'code'=>401,
                'status'=>'unauthorized',
                'message'=>[$response],
            ],401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('auth_token')->plainTextToken;
        $this->authenticated($request,$user);
        $response[] = 'Login Succesfull';
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'message'=>['success'=>$response],
            'data'=>[
                'user' => auth()->user(),
                'access_token'=>$tokenResult,
                'token_type'=>'Bearer'
            ]
        ]);


    }


    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        $validate = Validator::make($request->all(),$validation_rule);
        return $validate;

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        $notify[] = 'Logout Succesfull';
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'message'=>['success'=>$notify],
        ]);
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            auth()->user()->tokens()->delete();
            $notify[] = 'Your account has been deactivated';
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'message'=>['success'=>$notify],
            ]);
        }


        $user = auth()->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();


        $info = json_decode(json_encode(getIpInfo()), true);
        $userLogin = new UserLogin();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  request()->ip();
        $userLogin->longitude =  @implode(',',$info['long']);
        $userLogin->latitude =  @implode(',',$info['lat']);
        $userLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
        $userLogin->country_code = @implode(',',$info['code']);
        $userLogin->city = @implode(',',$info['city']);
        $userLogin->browser = @$info['browser'];
        $userLogin->os = @$info['os_platform'];
        $userLogin->country =  @implode(',', $info['country']);
        $userLogin->save();
    }


}
