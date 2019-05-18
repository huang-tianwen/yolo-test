<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Validator;

use Exception;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
                'status' =>'ok',
                'data' => $request->user()->toArray(),
            ]);
    }
    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $accessToken->revoke();
        return response()->json([
                'status' => 'ok',
            ]);
    }
}
