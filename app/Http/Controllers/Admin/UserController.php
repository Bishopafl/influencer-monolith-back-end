<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use App\UserRole;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index() {
        /**
         * restrict access for users
         * users with view ability
         * require authorization
         */
        Gate::authorize('view', 'users');

        $users = User::paginate();
        return UserResource::collection($users);
    }

    public function show($id) {
        Gate::authorize('view', 'users');

        $user = User::find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request){
        Gate::authorize('edit', 'users');

        $user = User::create($request->only('first_name', 'last_name', 'email') + [
            'password' => Hash::make(1234),
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id) {
        Gate::authorize('edit', 'users');

        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        UserRole::where('user_id', $user->id)->delete();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id) {
        Gate::authorize('view', 'users');

        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
