<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AuthController extends Controller
{
    # render login views
    public function login()
    {
        return Inertia::render('auth/Login');
    }

    public function _loginWithApi($username, $password){
        $user = Users::select('*')->where('username', $username)->first();

        if(!$user){
            return redirect()->to('/');
        }

        if($user->password != $password){
            return redirect()->to('/');
        } else {
             # insert activity table
            $data = [
                'activity' => "Melakukan login ",
                'id_user'  => $user['id']
            ];
            Activities::updateOrCreate($data);

            # save user session
            $this->_setSession($user);
            return redirect()->to('/');
        }
    }

    public function _storeLogin(Request $request)
    {
        if ($request->has('email') && $request->has('password')) {

            $email = $request->get('email');
            $pass  = $request->get('password');

            if (!$email) { # if email was empty
                return response()->json([
                    'message'       => 'Email or username is empty',
                    'error_code'    => 'error code : login-form x 0002'
                ], 400);
            }

            if (!$pass) { # if pass was empty
                return response()->json([
                    'message'       => 'Password is empty',
                    'error_code'    => 'error code : login-form x 0003'
                ], 400);
            }


            #--- check user input email or username
            $user = [];
            if (strpos($email, "@") !== false) {
                $user = Users::select('*')->where('email', $email)->first();
                if (!$user) {
                    return response()->json([
                        'message'      => 'sorry email not found',
                        'error_code'   => 'try login using username'
                    ], 404);
                } else {
                    $user = $user->toArray();
                }
            } else {
                $user = Users::select('*')->where('username', $email)->first();
                if (!$user) {
                    return response()->json([
                        'message'       => 'sorry username not found',
                        'error_code'    => 'use email instead username'
                    ], 404);
                } else {
                    $user = $user->toArray();
                }
            }

            # password verify
            if ($pass != $user['password']) {
                return response()->json([
                    'message'       => 'Incorecct password',
                    'error_code'    => 'error code : password x 400'
                ], 400);
            } else {


                # insert activity table
                $data = [
                    'activity' => "Melakukan login ",
                    'id_user'  => $user['id']
                ];
                Activities::updateOrCreate($data);

                # save user session
                $this->_setSession($user);
                return response()->json([
                    'message' => 'login succeed'
                ], 200);
            }
        } else {
            return response()->json([
                'message'       => 'form name is not define correctly',
                'error_code'    => 'error code : login-form x 0001'
            ], 400);
        }
    }

    function _logout()
    {
        session()->flush();
        return redirect()->to(route('login'));
    }

    protected function _setSession($user)
    {
        session()->put('user', $user);
    }
}
