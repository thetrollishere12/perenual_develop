<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformationController extends Controller
{
    public function update(Request $request, UpdatesUserProfileInformation $updater)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user()->id),
            ],
        ]);

        $updater->update($request->user(), $request->all());

        return $request->user();
    }
}