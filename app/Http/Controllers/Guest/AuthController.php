<?php

namespace App\Http\Controllers\Guest;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Validator;

use Carbon\Carbon;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'client_id' => 'required|integer',
                'client_secret' => 'required|string',
            ]);
        if ($validator->fails())
            return response()->json([
                    'status' =>'error',
                    'message' => $validator->errors()
                ]);
        $client = DB::table('oauth_clients')->find($request->client_id);
        if(!isset($client) || $client->secret != $request->client_secret)            
            return response()->json([
                    'status' =>'error',
                    'message' => app('translator')->trans('errors.wrong_client_id_or_client_secret'),
                ]);
        $proxy = Request::create(
            'oauth/token',
            'POST',
            [
                'grant_type' => 'password',
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]
        );
        $response = app()->handle($proxy);
        return $response;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]);
        if ($validator->fails())
            return response()->json([
                    'status' =>'error',
                    'message' => $validator->errors()
                ]);
        $user = new User();
        $user->password = app('hash')->make($request->password);
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->age = $request->age;
        $user->saveOrFail();
        return response()->json([
                'status' =>'ok',
                'data' => $user->toArray(),
            ]);
    }
}