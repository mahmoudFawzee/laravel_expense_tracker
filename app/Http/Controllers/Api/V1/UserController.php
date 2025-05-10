<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

      private function checkPassword(string $password,string $enteredPassword):bool
      {
        return Hash::check( $enteredPassword,$password);
      }

      private function failureResponse(string $message='something went wrong')
       {
        return response()->json([
                'status'=>'failure',
                'message'=>$message,
            ],422);
      }

      private function updateUserData(User $user,?string $email,?string $password)
      {
        $emailChanged = $this->isEmailChanged($email);
        if($emailChanged){
            if($password==null){
                return $this->failureResponse('password is required to change email');
            }
            $isRightPassword = $this->checkPassword($user->password,$password);
            
            if(!$isRightPassword){
                return $this->failureResponse('Password is Wrong');
            }
        }
        return null;
      }

      private function isEmailChanged(?string $email) : bool {
        if($email==null){return false;}
        $userEmail = Auth::user()->email;
        
        return $email!=$userEmail;
      }
    
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        logger($user);
        $resource = new UserResource($user);
        return response()->json($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request)
    {
        $validated = $request->validated();
        logger($validated);
        $user = User::findOrFail(Auth::user()->id);
        
       $acceptUpdates = $this->updateUserData($user,$request['email'],$request->password);
        if($acceptUpdates!=null){
            return $acceptUpdates;
        }
          $updated = $user->update($validated);

        if(!$updated){
            return $this->failureResponse();
        }
        $user->refresh();
        $updatedUser = new UserResource($user);
        return response()->json(
            $updatedUser
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate(
            ['password'=>'required']
        );

        $password = $validated['password'];
        $user = User::findOrFail(Auth::user()->id);
        $valid =$this->checkPassword($password,$user->password);
        if(!$valid){
             return response()->json([
                        'status'=>'failure',
                        'message'=>'password is wrong'
                    ]);
        }

        $deleted = $user->delete();
        if(!$deleted){
            return $this->failureResponse();
        }
         return response()->json([
                'status'=>'success',
                'message'=>'user deleted'
            ]);
    }


    public function change_password(Request $request) {
    
        //?validate
        $validated = $request->validate(
            [
            'oldPassword'=>['required',Password::min(6)],
            'newPassword'=>['required','confirmed',Password::min(6)],
            ]
        );
        $user = User::findOrFail(Auth::user()->id);
        $rightPassword = $this->checkPassword($user->password,$validated['oldPassword']);
        if(!$rightPassword){
            return $this->failureResponse('old password is wrong');
        }
        Auth::user()->tokens()->delete(); // deletes the current token
        $updatedPassword = $user->update(
            [
                'password'=>$validated['newPassword'],
            ]
        );
        if(!$updatedPassword){
            return $this->failureResponse();
        }

        


        return response()->json([
            'status'=>'success',
            'message'=>'password changed successfully'
        ]);
    }
  
}
