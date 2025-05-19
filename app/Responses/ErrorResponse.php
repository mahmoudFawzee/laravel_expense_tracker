<?php

namespace App\Responses;

class ErrorResponse
{
    public static  function handleErrorResponse(string $field, string $message): array
    {
        return [
            "message" => $message,
            "errors" => [
                $field => [$message],
            ]
        ];
    }


    public static function emailNotExists(): array
    {
        return ErrorResponse::handleErrorResponse(field: "email", message: "email does not exists");
    }

    public static function wrongPassword(): array
    {
        return ErrorResponse::handleErrorResponse(field: "password", message: "password is wrong",);
    }


    public static function wrongOldPassword(): array
    {
        return ErrorResponse::handleErrorResponse(field: "oldPassword", message: "old password is wrong",);
    }


    public static function somethingWentWrong(): array
    {
        return [
            "message" => "something went wrong"
        ];
    }
}
