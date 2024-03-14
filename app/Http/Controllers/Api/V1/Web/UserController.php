<?php

namespace App\Http\Controllers\Api\V1\Web;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\TokenRequest;
use App\Http\Resources\UserResource;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use App\Repositories\Contracts\UserContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UserController extends BaseApiController
{
    /**
     * UserController constructor.
     * @param UserContract $repository
     */
    public function __construct(UserContract $repository)
    {
        parent::__construct($repository, UserResource::class, 'user');
    }

    public function updateToken(TokenRequest $request,User $user): JsonResponse
    {
        try {
            $this->repository->updateToken($request->validated(), $user);
            return $this->respondWithModel($user);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @param ForgetPasswordRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse{
        try {
            DB::transaction(function ()use($request){
                $user =  app(UserContract::class)->findBy('email',$request['email']);
                $token = app('auth.password.broker')->createToken($user);
                Mail::to($user->email)
                    ->send(new ForgetPasswordMail('emails.forgetPassword',$token,$user));
            });
            return $this->respondWithSuccess('Password reset successfully');
        }
        catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
