<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Collections\UsersCollection;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view-all-users');
        return UserResource::collection(UsersCollection::collection($request))->collection;
    }

    public function show(User $user)
    {
        $this->authorize('view-user');
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request, User $user)
    {
        $this->authorize('create-user');
        $user = User::create($request->validated());
        $validated_type = $request->safe()->only(['type']);
        if($validated_type == 'driver')
        {
            $user->assignRole('driver');

        }else
        {
            $user->assignRole('supervisor');
        }
        return (new UserResource($user))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);

    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('edit-user');
        $user->update($request->validated());
  
        return (new UserResource($user->refresh()))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-user');
        $user->delete();
        return response()->json([
            'message' => ('User successfully deleted')
        ]);
    }

    
}
