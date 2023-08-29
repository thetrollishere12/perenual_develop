<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class NewRegisterController
{
    use CreatesNewUsers;

    public function register(Request $request, CreateNewUser $creator)
    {

        try{

            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            return $creator->create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ]);

        }catch(\Exception $e){

            return response()->json([
                'message' => $e,
            ], 401);
            
        }

    }
}