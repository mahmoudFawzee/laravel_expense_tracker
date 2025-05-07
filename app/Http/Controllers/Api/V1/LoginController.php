<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(LoginRequest $request)
    {
        $credentials = $request->validated();
        $valid = Auth::attempt($credentials);
        if(!$valid){
            return response()->json(['status'=>'forbidden',
            'message'=>'invalid credentials',
            
            ],403);
        }

        $user = Auth::user();
        return response()->json(
            ['status'=>'loggedIn',
            'message'=>'user logged in',
            'data'=>[
                'id'=>$user['id'],
                'firstName'=>$user['first_name'],
                'lastName'=>$user['last_name'],
                'email'=>$user['email'],
                'phoneNumber'=>$user['phone_number'],
            ],
            ]

        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
