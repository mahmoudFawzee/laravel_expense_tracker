<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Responses\ErrorResponse;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    public function store(RegisterRequest $request)
    {
          $validAttributes= $request->validated();
        
        $createdUser = $this->userService->createUser($validAttributes);
        if($createdUser){
            return response()->json([
            'status' => 'success',
            'message' => 'user created successfully',
            'data' =>$createdUser,
        ],201);
        }
        return response()->json(ErrorResponse::somethingWentWrong());
        
    }
}
