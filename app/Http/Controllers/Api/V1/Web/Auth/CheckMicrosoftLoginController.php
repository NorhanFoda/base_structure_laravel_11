<?php

namespace App\Http\Controllers\Api\V1\Web\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckMicrosoftLoginController extends BaseApiController
{

    public function __construct(UserContract $userContract)
    {
        parent::__construct($userContract, UserResource::class);
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user = $request->user()->load('roles', 'permissions', 'employee.avatar',
            'employee.user', 'customer.user', 'customer.avatar', 'tokens', 'employee.workRegulation');
            return $this->respondWithModel($user);
        }
        return $this->errorWrongArgs('Wrong Credentials');
    }
}
