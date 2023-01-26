<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get()->except(auth()->id());

        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->removeRole($user->getRoleNames()->toArray()[0]);
        $user->assignRole($request->validated());

        return redirect()->route('home.users.index')->with('success', 'Successfully change user role.');
    }
}
