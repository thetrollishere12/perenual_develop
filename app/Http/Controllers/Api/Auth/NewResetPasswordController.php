<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Fortify;

class NewResetPasswordController
{
    public function reset(Request $request, ResetUserPassword $resetter)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => Fortify::validationRules()['password'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user) use ($request, $resetter) {
                $resetter->reset($user, $request->only('password'));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
}