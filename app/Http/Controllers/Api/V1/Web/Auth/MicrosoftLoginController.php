<?php

namespace App\Http\Controllers\Api\V1\Web\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\EmployeeContract;
use App\Repositories\Contracts\UserContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftLoginController  extends Controller
{

    public function __invoke(): RedirectResponse
    {
        $microsoftUser = Socialite::driver('microsoft')->user();
        $user = app(UserContract::class)->findBy('email', $microsoftUser->email, false);
        $user?->load('employee');
        if ($user && $user->employee?->is_active) {
            resolve(EmployeeContract::class)->update($user->employee, ['microsoft_id' => $microsoftUser->id]);
            Auth::login($user);
            return Redirect::to(config('app.url') . '/employee-login-loading');
        }
        return Redirect::to(config('app.url'). '?is-inactive=true');
    }

}
