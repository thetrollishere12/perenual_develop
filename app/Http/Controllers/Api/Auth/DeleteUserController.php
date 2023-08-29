<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function delete(Request $request)
    {
        $request->user()->delete();

        return response()->json('Account deleted', 200);
    }
}