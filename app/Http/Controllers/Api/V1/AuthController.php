<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseHelpers;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = $request->user()->createToken('token');

            return $this->authSuccess($token->plainTextToken);
        }

        return $this->respondWithError('Authentication failed');
    }
}
