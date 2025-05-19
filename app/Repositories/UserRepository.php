<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserRepository{

    private User $user;
    public function __construct() {
        $this->user = Auth::user();
    }

    public function fetchUsers(){
        return User::all();
    }

    public function showUser() : User {
        return User::findOrFail($this->user->id);
    }

    public function updateUser(array $attributes){
        $updated =  $this->user->update($attributes);
        if($updated){
            $this->user->refresh();
        }
        //?return the updated user.
        return $this->user;
    }

    public function createUser(array $attributes) : ?User {
        return User::create([
            'first_name'=>$attributes['firstName'],
            'last_name'=>$attributes['lastName'],
            'email'=>$attributes['email'],
            'password'=>Hash::make($attributes['password']),
            'phone_number'=>$attributes['phoneNumber'],
        ]);
    }

    public function deleteUser():bool{
        $deleted =  $this->user-> delete();
        if($deleted){
            $this->user->tokens()->delete();
        }
        return $deleted;
    }
}
