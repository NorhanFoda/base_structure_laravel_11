<?php

namespace App\Http\Controllers\Api\V1\Web\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseApiController
{

    public function __construct(UserContract $userContract)
    {
        parent::__construct($userContract, UserResource::class);
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $login = $this->repository->loginCheck($credentials);
        if (is_string($login)) {
            return $this->errorWrongArgs($login);
        }
        return $this->respondWithModel($login);
    }
}
