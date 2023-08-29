<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPasswordController extends Controller
{
    public function update(Request $request, UpdatesUserPasswords $updater)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $updater->update($request->user(), $request->all());

        return response()->json('Password updated', 200);
    }
}