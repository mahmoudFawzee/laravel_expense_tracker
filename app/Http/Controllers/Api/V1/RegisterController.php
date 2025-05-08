<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function store(RegisterRequest $request)
    {
        logger('start register');
          $user= $request->validated();
        
        $createdUser = User::create([
            'first_name'=>$user['firstName'],
            'last_name'=>$user['lastName'],
            'email'=>$user['email'],
            'phone_number'=>$user['phoneNumber'],
            'password' => Hash::make($user['password']),
        ]);
        if($createdUser){
            return response()->json([
            'status' => 'success',
            'message' => 'user created successfully',
            'data' =>[
                'id'=>$createdUser['id'],
                'firstName'=>$createdUser['first_name'],
                'lastName'=>$createdUser['last_name'],
                'email'=>$createdUser['email'],
                'phoneNumber'=>$createdUser['phone_number'],
            ],
        ],201);
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'user creation failed'
        ]);
        
    }
}
