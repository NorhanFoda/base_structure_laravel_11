<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\User;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements UserContract
{
    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function syncRelations($attributes, $model): void
    {
        if (isset($attributes['role_id']) ) {
            $model->syncRoles($attributes['role_id']);
        }
    }

    public function updateToken($attributes, $model)
    {
        $fcm = $this->model->tokens()->where('fcm_token', $attributes['fcm_token'])->first();
        if (!$fcm) {
            $token = $model->createToken('web', $attributes);
            $token->accessToken->fcm_token = $attributes['fcm_token'];
            $token->accessToken->save();
        }
        return $model->refresh();
    }

    public function createMigratedUser($record, $JsonName = null): mixed
    {
        if (is_null($JsonName)) {
            $JsonName = [
                'ar' => $record->name,
                'en' => $record->name
            ];
        }
        return $this->create([
            'name' => $JsonName,
            'email' => $record->email,
            'phone' => $record->phone,
            'password' => $record->password,
            'remember_token' => $record->remember_token,
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at,
        ]);
    }

    public function loginCheck(array $credentials): string|User
    {
        if (Auth::attempt($credentials)) {
            return auth()->user()?->load('roles', 'permissions', 'tokens', );
        }
        return __json('messages.wrong_credentials');
    }

    public function createToken($model, $data): mixed
    {
        $apiToken = $model->createToken($data['device_name'] ?? request()?->header('User-Agent'));
        $accessToken = $apiToken->accessToken;
        $accessToken->device_id = $data['device_id'] ?? null;
        $accessToken->device_os = $data['device_name'] ?? null;
        $accessToken->device_os_version = $data['device_os_version'] ?? null;
        $accessToken->app_version = $data['app_version'] ?? null;
        $accessToken->timezone = $data['timezone'] ?? null;
        $accessToken->fcm_token = $data['fcm_token'] ?? null;
        $apiToken->accessToken->save();
        return $apiToken->plainTextToken;
    }

}
