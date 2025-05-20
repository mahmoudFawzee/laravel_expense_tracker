<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Responses\ErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Services\UserService;
use App\Exceptions\GlobalException;
use App\Exceptions\InvalidPasswordException;

class UserController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }



    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = $this->userService->showUser();
        $resource = new UserResource($user);
        return response()->json($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            //?if success it will return bool
            //?if not it will return failure response.
            $result   = $this->userService->updateUser($validated);
            return response()->json(
                $result
            );
        } catch (InvalidPasswordException $e) {
            return response()->json(ErrorResponse::wrongPassword(), 422);
        } catch (GlobalException $e) {
            return response()->json(ErrorResponse::somethingWentWrong(), 422);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validated = $request->validate(
                ['password' => 'required']
            );

            $this->userService->destroyUser($validated['password']);
            return response()->json([
                'status' => 'success',
                'message' => 'user deleted'
            ]);
        } catch (InvalidPasswordException $e) {
            return response()->json(ErrorResponse::wrongPassword(), 422);
        } catch (GlobalException $e) {
            return response()->json(ErrorResponse::somethingWentWrong(), 422);
        }
    }


    public function changePassword(Request $request)
    {

        try {  //?validate
            $validated = $request->validate(
                [
                    'oldPassword' => ['required', Password::min(6)],
                    'newPassword' => ['required', 'confirmed', Password::min(6), 'different:oldPassword'],
                ]
            );

            $this->userService->changePassword(oldPassword: $validated['oldPassword'], newPassword: $validated['newPassword']);
            return response()->json([
                'status' => 'success',
                'message' => 'password changed successfully'
            ]);
        } catch (InvalidPasswordException $e) {
            return response()->json(ErrorResponse::wrongOldPassword(), 422);
        } catch (GlobalException $e) {
            return response()->json(ErrorResponse::somethingWentWrong(), 422);
        }
    }
}
