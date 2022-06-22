<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\User;
use Auth;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(Request $request) {
        if(Auth::attempt($request->only('email','password'))) {
            $user = Auth::user();
            $scope = $request->input('scope');

            if ($user->isInfluencer() && $scope !== 'influencer') {
                return response([
                    'error' => 'Access Denied!'
                ], Response::HTTP_FORBIDDEN);
            }
            $token = $user->createToken($scope, [$scope])->accessToken;

            // store token into cookie where front end doesn't need to access
            $cookie = cookie('jwt', $token, 3600);

            return response([
                'token' => $token,
            ])->withCookie($cookie);
        }
    }

    public function logout() {
        // remove cookie on logout
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function register(RegisterRequest $request) {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
            + [
                'password' => Hash::make($request->input('password')),
                'is_influencer' => 1
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function user() {
        $user = Auth::user();

        $resource = new UserResource($user);

        Log::info($user);

        if ($user->isInfluencer()) {
            return $resource;
        }

        // adding an additional field to user function
        return $resource->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request) {
        $user = Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request) {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
