<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

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
     */
    public function store(UserCreateRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role,
        ]);

        return new UserResource($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'You cannot delete yourself'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

}

