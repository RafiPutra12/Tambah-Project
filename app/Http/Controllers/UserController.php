<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

	public function login(Request $request)
	{
		$scredentials = $request->only('email', 'password');
		try {
			if (! $token = JWTAuth::attempt($scredentials)) {
				return response()->json(['error' => 'invalid_credentials'], 400);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'could_not_create_token'], 500);
		}
		return response()->json(compact('token'));
	}

	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'firstname'       => 'required|string|max:255',
            'lastname'        => 'required|string|max:255',
			'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:6',
            'is_freelancer'   => 'required|integer',
            'is_productowner' => 'required|integer',
            'address'         => 'required|string|max:255',
		]);

        if($validator->fails()){
			return response()->json($validator->errors()->toJson(),400);
        }

		$user = new User();
        $user->firstname 	    = $request->firstname;
        $user->lastname 	    = $request->lastname;
		$user->email 	        = $request->email;
        $user->password         = Hash::make($request->password);
        $user->is_freelancer 	= $request->is_freelancer;
        $user->is_productowner 	= $request->is_productowner;
        $user->address 	        = $request->address;
		$user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
			'status'	=> '1',
			'message'	=> 'User berhasil ter-registrasi'
		], 201);

	}
	public function getAuthenticatedUser()
	{
		try {
			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json(['user_not_found'],404);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json(['token_expired'], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json(['token_invalid'], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json(['token_absent'], $e->getStatusCode());
		}
		return response()->json(compact('user'));
	}
}
