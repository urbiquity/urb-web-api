<?php

namespace App\Http\Controllers;

use Avatar;
use Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    // region register

    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ( $validator->fails() ) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);


        $user = User::create($input);

        $user->save();

        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);

        return $this->absorb(
            $this->setResponse([
                'title' => 'Successfully registered.',
                'meta' => [
                    'name' => $user->name,
                    'token' => $user->createToken('Urbiquity')-> accessToken
                ]
            ])
        )->json();

    }

    // endregion register

    // region login

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return $this->absorb(
            $this->setResponse([
                'title' => 'Successfully logging in',
                'meta' => [
                    'token_type' => 'Bearer',
                    'access_token' => $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]
            ])
        )->json();
    }

    // endregion login

    // region logout

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->absorb(
           $this->setResponse([
               'title' => 'Successfully logged out'
           ])
        )->json();
    }

    // endregion logout

    // region details

    public function user(Request $request)
    {
        $user = User::all();
        return $this->absorb(
            $this->setResponse([
                'title' => 'Successfully retrieve user list',
                'meta' => $user
            ])
        )->json();
    }

    // endregion details

}