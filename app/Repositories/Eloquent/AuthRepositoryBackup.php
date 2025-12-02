<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthRepositoryBackup implements AuthRepositoryInterface
{
    public function register($request, $role)
    {
        $user = User::create([
            'role' => $role,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return $user;
    }

    public function login($request)
    {
        $credentails = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentails)) {
            $user = Auth::user();
        } else {
            $user = false;
        }

        return $user;
    }

    public function logout()
    {
        return Auth::logout();
    }

    public function viewProfile()
    {
        return Auth::user();
    }

    public function saveProfile($request)
    {
        $user = Auth::user();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return $user;
    }

    public function uploadProfilePicture($request)
    {
        if ($request->has('profile_image')) {
            $user = Auth::user();
            $file = $request->file('profile_image');
            $name = time() . '_profile_image' . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_images', $name, 'public');

            if($path)
            {
                $user->update([
                    'profile_image' => $path
                ]);

                return $user;
            }
        }
    }
    public function deleteProfilePicture()
    {
        $user = Auth::user();

        if (Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->update(
            ['profile_image' => null]
        );

        return $user;
    }

    public function updatePassword($request)
    {
        $user = Auth::user();
        $user->update([
            'password' => $request->password
        ]);

        return $user;
    }
}
