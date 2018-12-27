<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

define('YOUR_SERVER_URL', env('YOUR_SERVER_URL'));
// Check "oauth_clients" table for next 2 values: 
define('CLIENT_ID', env('CLIENT_ID'));
define('CLIENT_SECRET', env('CLIENT_SECRET'));

class LoginControllerAPI extends Controller
{
    public function login(Request $request)
    {
        if ($request->email !== null && $request->password !== null) {
            $email = User::where('username', $request->email)
                            ->orWhere('email', $request->email)
                            ->pluck('email')->first();
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], 400);
        }

        $http = new \GuzzleHttp\Client;
        $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
