<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Team;



class GoogleLoginController extends Controller
{
    public function login(Request $req)
    {
        $googleUser = Socialite::driver('google')->userFromToken($req->token);
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            if ($user->google_id) {
                // user already exists and has linked Google account
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            } else {
                // user already exists but has not linked Google account
                $user->google_id = $googleUser->getId();
                $user->save();
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            }
        } else {
            // user does not exist, so create the user
            $user = new User;
            $user->name = $googleUser->getName();
            $user->email = $googleUser->getEmail();
            $user->google_id = $googleUser->getId();
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->password = bcrypt(str_random(16)); // generate random password as we don't need it for Google users
            $user->save();

            //every user needs a team for dashboard/jetstream to work.
            //create a personal team for the user
            $newTeam = Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]);

            // save the team and add the team to the user.
            $newTeam->save();
            $user->current_team_id = $newTeam->id;
            $user->save();

            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        }

        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}