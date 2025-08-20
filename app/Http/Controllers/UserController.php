<?php

namespace App\Http\Controllers;

use App\Filters\UserFilters;
use App\Http\Requests\UserFilterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(UserFilterRequest $request): AnonymousResourceCollection
    {
        $query = User::query();
        UserFilters::apply($query, $request->validated());
        $users = $query->paginate();

        return UserResource::collection($users);
    }
}
