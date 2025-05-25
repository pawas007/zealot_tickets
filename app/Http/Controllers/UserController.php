<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * @return UserCollection
     */
    public function index(): UserCollection
    {

        $users = User::query()->paginate(5);
        return new UserCollection($users);
    }

    /**
     * @param UserCreateRequest $request
     * @return UserResource
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request): UserResource
    {
        $this->authorize('store', User::class);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role,
        ]);

        return new UserResource($user);
    }


}

