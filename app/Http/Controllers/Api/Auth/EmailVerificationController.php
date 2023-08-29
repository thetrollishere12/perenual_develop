<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json("Email already verified", 422);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json('Verification link sent', 200);
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (! $user || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json('Invalid verification link', 422);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json("Email already verified", 422);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json('Email has been verified', 200);
    }
}