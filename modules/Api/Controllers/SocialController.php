<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\UserMeta;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Matrix\Exception;
use Modules\User\Events\SendMailUserRegistered;
use Socialite;
use App\User;
class SocialController  extends Controller
{
public function social(Request $request) {

        // try {
            $provider = $request->input('provider');
            $user_id = $request->user_id;
            $user_email = $request->email;
            $user_name  = $request->name;
            // $token = $request->access_token;
         
            //  dd($user_id);
            // $this->initConfigs($provider);

        //              $provider_user = Socialite::driver($provider)->userFromToken($token);
        //  dd($provider_user);
                    // if (empty($user)) {
                    //     return redirect()->to('login')->with('error', __('Can not authorize'));
                    // }

            // $existUser = User::getUserBySocialId($provider, $user_id);
             $existUser = User::where('email', $user_email)->first();

            //  dd($existUser);

            if (empty($existUser)) {

                $meta = UserMeta::query()->where('name', 'social_' . $provider . '_id')->where('val', $user_id)->first();
                if (!empty($meta)) {
                    $meta->delete();
                }

                // if (empty($user_email)) {
                //     return redirect()->route('login')->with('error', __('Can not get email address from your social account'));
                // }

                $userByEmail = User::query()->where('email', $user_email)->first();
                // dd($userByEmail);
                // if (!empty($userByEmail)) {
                //     return redirect()->route('login')->with('error', __('Email :email exists. Can not register new account with your social email', ['email' => $user_email]));
                // }

                // Create New User
                $realUser = new User();
                $realUser->email = $user_email;
                // $realUser->password = Hash::make(uniqid() . time());
                 $realUser->password = Hash::make($user_id);

                $realUser->name = $user_name;
                $realUser->first_name = $user_name;
                $realUser->status = 'publish';

                $realUser->save();

                $realUser->addMeta('social_' . $provider . '_id', $user_id);
                $realUser->addMeta('social_' . $provider . '_email', $user_email);
                $realUser->addMeta('social_' . $provider . '_name', $user_name);
                // $realUser->addMeta('social_' . $provider . '_avatar', $user->getAvatar());
                // $realUser->addMeta('social_meta_avatar', $user->getAvatar());

                $realUser->assignRole('customer');

                try {
                    event(new SendMailUserRegistered($realUser));
                } catch (Exception $exception) {
                    Log::warning("SendMailUserRegistered: " . $exception->getMessage());
                }
                // $token = Auth::login($realUser);
                // Login with user
                // Auth::login($realUser);

                $credentials = request(['email', 'password']);
// dd($credentials);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status'=>0,'message' => __('error in login'),'status'=>0], 401);
        }

        return $this->respondWithToken($token);
// return $realUser;
                // return redirect('/');
// dd($realUser);
            } else {

                if ($existUser->deleted == 1) {
                    return response()->json(['success'=>'false' , 'data'=>'User blocked']);
                }
                if (in_array($existUser->status, ['blocked'])) {
                    return response()->json(['success'=>'false', __('Your account has been blocked')]);
                }
                // $token = Auth::login($existUser);
                // Auth::login($existUser);
                $credentials = request(['email', 'password']);
                // dd($credentials);
                        if (! $token = auth('api')->attempt($credentials)) {
                            return response()->json(['status'=>0,'message' => __('Password is not correct'),'status'=>0], 401);
                        }
                
                        return $this->respondWithToken($token);
            }
        // }catch (\Exception $exception)
        // {
        //     $message = $exception->getMessage();
        //     if(empty($message) and request()->get('error_message')) $message = request()->get('error_message');
        //     if(empty($message)) $message = $exception->getCode();

        //     return response()->json(['success'=>'false',$message]);
        // }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=>1,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
  

// public function social(Request $request){
//              $user_id = $request->user_id;
//             $user_email = $request->email;
//             $user_name  = $request->name;
//              $request->password = $request->user_id;

//             $user = User::query()->where('email', $user_email)->first();
//             // dd($user);
//             if (!empty($user)) {

//                 $credentials = request(['email', 'password']);

//                 if (! $token = auth('api')->attempt($credentials)) {
//                     return response()->json(['status'=>0,'message' => __('Password is not correct'),'status'=>0], 401);
//                 }
        
//                 return $this->respondWithToken($token);


//                 }else{
//                     return $credentials;
//                 }


// }

}