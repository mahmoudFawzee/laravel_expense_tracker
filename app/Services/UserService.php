<?php

namespace App\Services;

use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Exceptions\InvalidPasswordException;

use function PHPUnit\Framework\throwException;

class UserService
{
  private UserRepository $userRope;
  private User $user;
  public function __construct(UserRepository $repo)
  {
    $this->userRope = $repo;
    $this->user = Auth::user();
  }


  //?to check if the entered password user entered is equals to the stored one.
  private function checkPassword(string $enteredPassword): bool
  {
    return Hash::check($enteredPassword, $this->user->password);
  }

  //?when user update his data we need to check if he asked to change the email
  //?if yes we need to check if the password provided with the request of no we just update data
  //?if yes we check if the password is right, if not we ask him to provide if
  //?if password wrong we replay to tell him password is wrong.
  private  function verifyRequest(array $attributes)
  {

    $emailChanged = $this->isEmailChanged($attributes['email']);
    if ($emailChanged) {
      $this->verifyPassword($attributes['password']);
    }
  }

  private function verifyPassword(?string $password)
  {
    $isRightPassword = $this->checkPassword($password, $this->user->password);

    if (!$isRightPassword) {
      throw new InvalidPasswordException(message: "password is wrong");
    }
  }

  private function isEmailChanged(?string $email): bool
  {
    return $email != $this->user->email;
  }


  //?all we just need is to get the user.
  public function showUser(): User
  {
    return $this->userRope->showUser();
  }

  //?we will get these attributes from the request body
  //?here we just need to make our additional validation.
  public function updateUser(array $attributes)
  {
    $this->verifyRequest($attributes);
    //?it will return use if everything works perfectly
    return $this->userRope->updateUser($attributes);
  }

  //?we need password to delete the user.
  public function destroyUser(?string $password)
  {
    //?verify password
    $this->verifyPassword($password);

    //?check if pass right.
    return $this->userRope->deleteUser();
  }

  public function createUser(array $attributes): User
  {
    $user =  $this->userRope->createUser($attributes);
    if ($user == null) {
      throw new GlobalException();
    }
    return $user;
  }

  public function changePassword(string $oldPassword, string $newPassword)
  {
    $rightPassword = $this->checkPassword(enteredPassword: $oldPassword);
    if (!$rightPassword) {
      throw new InvalidPasswordException(field: "oldPassword", message: 'old password is wrong');
    }
    $updatedPassword = $this->user->update(
      [
        'password' => $newPassword,
      ]
    );
    if (!$updatedPassword) {
      throw new GlobalException();
    }
  }
}
