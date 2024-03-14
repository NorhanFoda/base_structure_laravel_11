<?php

namespace App\Http\Controllers\Api\V1\Mobile\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserContract;
use Illuminate\Http\JsonResponse;

class LoginController extends BaseApiController
{

    public function __construct(UserContract $userContract)
    {
        parent::__construct($userContract, UserResource::class);
    }

    /**
     * login
     *
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam device_id string required
     * @bodyParam device_name string required
     * @bodyParam device_os string
     * @bodyParam device_os_version number
     * @bodyParam app_version number
     * @bodyParam timezone string
     * @bodyParam fcm_token string
     * @response {
     *  "status": 200,
     *  "message": "",
     *  "data":{
     *
     *   }
     * }
     *
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $login = $this->repository->loginCheck($credentials);
        if (is_string($login)) {
            return $this->errorWrongArgs($login);
        }
        $login->api_token = $this->repository->createToken($login, $request->validated());
        return $this->respondWithModel($login);
    }

}
