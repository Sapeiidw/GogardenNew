<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return inertia('User/Index', ['roles' => Role::get(), 'users' => User::with('roles')->paginate(5)]);
    }

    public function create()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        request()->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users,email,except,id',
            'password' => 'required',
            'role' => 'required',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'profile_photo_path' => request('photo') ? request()->file('profile_photo_path')->store('profile_photos') : null,
        ]);
        $user->roles()->sync($request->input('role'));

        return redirect()->back()->with('message', 'User Created!!');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function edit(User $user)
    {

        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return inertia('User/Edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        request()->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users,email,'.$user->id,
            'password' =>  'nullable|string',
            'role' => 'required',
            'profile_photo_path' => request()->file('profile_photo_path') ? 'image|mimes:jpeg,jpg,png,gif' : '',
        ]);

        if (request()->file('profile_photo_path') != null || request()->file('profile_photo_path') != '') {
            Storage::delete($user->profile_photo_path);
            $photo = request()->file('profile_photo_path')->store('profile-photos');            
        } elseif($user->profile_photo_path != null) {  
            $photo = $user->profile_photo_path;
        } else {
            $photo = null;
        }

        if (request('password') != null) {
            $password =  Hash::make(request('password'));
        } else {  
            $password = $user->password;
        }
        
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => $password,
            'profile_photo_path' => $photo,
        ]);
        $user->roles()->sync($request->input('role'));

        return redirect()->back()->with('message', 'User Updated!!');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }
}