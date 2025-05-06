<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function index(){
        $users = User::all();
        return response()->json([
            'status'=>'success',
            'message'=>'all users got',
            'data'=>$users,
        ]);
    }


     


    public function store(RegisterRequest $request)
    {
          $user= $request->validated();
        
        $createdUser = User::create([
            'first_name'=>$user['firstName'],
            'last_name'=>$user['lastName'],
            'email'=>$user['email'],
            'phone_number'=>$user['phoneNumber'],
            'password' => Hash::make($user['password']),
        ]);
        Auth::login($createdUser);
        return response()->json([
            'status' => 'success',
            'message' => 'user created successfully',
            'data' =>$createdUser,
        ]);
    }
}
