<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Responses\ErrorResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
 
    /**
     * Store a newly created resource in storage.
     */
     public function store(LoginRequest $request)
    {
        $credentials = $request->validated();
        //?look for the email is in database
        $gotUser = User::where('email', $request['email'])->first();
        if($gotUser==null){
            return response()->json(
            ErrorResponse::emailNotExists()
            ,422);
        }
        
        //?if yes check entered password
        $valid = Auth::attempt($credentials);
        if(!$valid){
            
            return response()->json(
            ErrorResponse::wrongPassword()
            
            ,403);
        }

        $user = Auth::user();
         if ($user) {
        $user->tokens()->delete();
    }
        
        
         $token = $user->createToken('authToken')->plainTextToken;

         return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => [
            'id'=>$user['id'],
            'firstName'=>$user['first_name'],
            'lastName'=>$user['last_name'],
            'email'=>$user['email'],
            'phoneNumber'=>$user['phone_number'],
        ],
    ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
{
    $user = Auth::user();

    if ($user) {
        $user->tokens()->delete(); // deletes all tokens
    }

    return response()->json([
        'status' => 'success',
        'message' => 'User logged out'
    ]);
}
}
