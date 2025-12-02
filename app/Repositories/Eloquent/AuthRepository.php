<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthRepository implements AuthRepositoryInterface
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
        if ($request->hasFile('profile_image')) {
            $user = Auth::user();

            // 1) Purani image delete karo agar public/storage ke andar hai
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                @unlink(public_path($user->profile_image));
            }

            // 2) Nayi image save karo public/storage/profile_images ke andar
            $file = $request->file('profile_image');
            $name = time() . '_profile_image.' . $file->getClientOriginalExtension();

            // public/storage/profile_images
            $uploadDir = 'storage/profile_images';

            // ensure directory exists (ya manually bana sakte ho)
            if (!file_exists(public_path($uploadDir))) {
                mkdir(public_path($uploadDir), 0775, true);
            }

            // Move file
            $file->move(public_path($uploadDir), $name);

            // 3) DB me relative path save karein (jaise: storage/profile_images/xxx.jpg)
            $relativePath = $uploadDir . '/' . $name;

            $user->update([
                'profile_image' => $relativePath,
            ]);

            return $user;
        }

        return null;
    }
    public function deleteProfilePicture()
    {
        $user = Auth::user();

        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            @unlink(public_path($user->profile_image));
        }

        $user->update(['profile_image' => null]);

        return $user;
    }

    public function uploadProfileLogo($request)
    {
        if ($request->hasFile('profile_logo')) {
            $user = Auth::user();

            // 1) Purani image delete karo agar public/storage ke andar hai
            if ($user->profile_logo && file_exists(public_path($user->profile_logo))) {
                @unlink(public_path($user->profile_logo));
            }

            // 2) Nayi image save karo public/storage/profile_images ke andar
            $file = $request->file('profile_logo');
            $name = 'logo_'.time() . '_profile_.' . $file->getClientOriginalExtension();

            // public/storage/profile_images
            $uploadDir = 'storage/profile_images';

            // ensure directory exists (ya manually bana sakte ho)
            if (!file_exists(public_path($uploadDir))) {
                mkdir(public_path($uploadDir), 0775, true);
            }

            // Move file
            $file->move(public_path($uploadDir), $name);

            // 3) DB me relative path save karein (jaise: storage/profile_images/xxx.jpg)
            $relativePath = $uploadDir . '/' . $name;

            $user->update([
                'profile_logo' => $relativePath,
            ]);

            return $user;
        }

        return null;
    }
    public function deleteProfileLogo()
    {
        $user = Auth::user();

        if ($user->profile_logo && file_exists(public_path($user->profile_logo))) {
            @unlink(public_path($user->profile_logo));
        }

        $user->update(['profile_logo' => null]);

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
    
    /* ==========================================
     * FORGOT PASSWORD FLOW METHODS
     * ========================================== */

    /**
     * Step 1: Send OTP to user's email (store in password_reset_tokens)
     */
    public function sendForgotOtp($data)
    {
        $email = is_array($data) ? ($data['email'] ?? null) : $data->email ?? null;

        if (!$email) {
            throw new \Exception('Email is required.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('We could not find a user with this email.');
        }

        try {
            DB::beginTransaction();

            $otp = random_int(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token'      => $otp,        // can be hashed later if you want
                    'created_at' => Carbon::now(),
                ]
            );

            // TODO: send OTP via email / SMS here if needed

            DB::commit();

            return $otp; // optional (for debugging / testing)
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('sendForgotOtp error', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Unable to process forgot password request right now.');
        }
    }

    /**
     * Step 2: Verify OTP (email + otp)
     */
    public function verifyForgotOtp($data)
    {
        $email = is_array($data) ? ($data['email'] ?? null) : $data->email ?? null;
        $otp   = is_array($data) ? ($data['otp'] ?? null)   : $data->otp ?? null;

        if (!$email || !$otp) {
            throw new \Exception('Email and OTP are required.');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $otp)
            ->first();

        if (!$record) {
            throw new \Exception('Invalid or expired OTP.');
        }

        // Optional: check expiry e.g. 15 minutes
        /*
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            throw new \Exception('OTP has expired. Please request a new one.');
        }
        */

        return true;
    }

    /**
     * Step 3: Reset password (email + otp + new password)
     */
    public function resetForgotPassword($data)
    {
        $email    = is_array($data) ? ($data['email'] ?? null)    : $data->email ?? null;
        $otp      = is_array($data) ? ($data['otp'] ?? null)      : $data->otp ?? null;
        $password = is_array($data) ? ($data['password'] ?? null) : $data->password ?? null;

        if (!$email || !$otp || !$password) {
            throw new \Exception('Email, OTP and new password are required.');
        }

        try {
            DB::beginTransaction();

            // 1) Verify OTP record
            $record = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $otp)
                ->first();

            if (!$record) {
                throw new \Exception('Invalid or expired OTP.');
            }

            // 2) Get user and update password
            $user = User::where('email', $email)->first();

            if (!$user) {
                throw new \Exception('We could not find a user with this email.');
            }

            $user->update([
                'password' => $password, // auto-hashed by casts()
            ]);

            // 3) Delete used token
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('resetForgotPassword error', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Unable to reset password right now. Please try again later.');
        }
    }
}
