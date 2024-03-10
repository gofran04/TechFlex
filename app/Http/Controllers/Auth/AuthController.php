<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
USe App\Models\User;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Requests\Auth\LoginRequest;



class AuthController extends Controller
{
    
   public function login(LoginRequest $request){
       $validated = $request->validated();
       if (! $token = auth()->attempt($validated))
       {
           return response()->json(['error' => ('The credentials that you entered do not match any of our records')], 401);
       }
     
       return $this->createNewToken($token);
   }
 
   public function register(RegistrationRequest $request) 
   {
    $input = $request->validated();
    $input['password'] = bcrypt($input['password']);        
    $user = User::create($input);

    return response()->json([
        'message' => 'User successfully registered',
        'user' => $user
    ], 201);
   }

   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout() {
       auth()->logout();
       return response()->json(['message' => 'User successfully signed out']);
   }
 
   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function createNewToken($token){
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60,
           'user' => auth()->user()
       ]);
   }
}