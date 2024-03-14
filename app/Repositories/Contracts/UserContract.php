<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserContract extends BaseContract
{
    public function loginCheck(array $credentials): string|User;
    public function createToken($model, $data): mixed;
}

