<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function token(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            abort(403);
        }

        $token = auth()->user()->createToken('Our Token');
        return response()->json([
            'token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->expired_at
        ]);
    }
}
