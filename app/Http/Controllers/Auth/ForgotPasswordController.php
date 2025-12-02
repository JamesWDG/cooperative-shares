<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Send OTP to user's email
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
            'email.exists'   => 'We could not find a user with this email.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;

        try {
            // 1. Generate OTP
            $otp = random_int(100000, 999999); // 6-digit

            // 2. Remove any old tokens for this email
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            // 3. Store hashed OTP in token column
            DB::table('password_reset_tokens')->insert([
                'email'      => $email,
                'token'      => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]);

            // 4. Prepare email data for SendMail Mailable
            $mailData = [
                'subject' => 'Verify Your E-mail Address',
                'otp'     => $otp,
                'email'   => $email,
                'heading' => 'Email Verification',
                'line1'   => "You're almost ready to get started.",
                'line2'   => 'Use the OTP below to verify your email and reset your password.',
            ];

            // 5. Send email using your SendMail class and OTP template
            // Make sure you have a blade like: resources/views/emails/forgot_otp.blade.php
            Mail::to($email)->send(new SendMail($mailData, 'forgot_otp'));

            Log::info('Forgot password OTP sent successfully to ' . $email);

            return response()->json([
                'status' => true,
                'msg'    => 'An OTP has been sent to your email address.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Error sending forgot password OTP: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Step 2: Verify OTP only (AJAX)
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|digits:6',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
            'email.exists'   => 'We could not find a user with this email.',
            'otp.required'   => 'Please enter the OTP.',
            'otp.digits'     => 'OTP must be a 6-digit code.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;
        $otp   = $request->otp;

        try {
            $record = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->orderByDesc('created_at')
                ->first();

            if (!$record) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'No OTP found for this email. Please request a new one.',
                ], 200);
            }

            // Check expiry (e.g. 10 minutes)
            $createdAt = Carbon::parse($record->created_at);
            if ($createdAt->lt(Carbon::now()->subMinutes(10))) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'OTP has expired. Please request a new one.',
                ], 200);
            }

            // Check OTP
            if (!Hash::check($otp, $record->token)) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Invalid OTP. Please try again.',
                ], 200);
            }

            // If you want, you can also mark something in session that OTP is verified
            // session(['forgot_password_verified_email' => $email]);

            return response()->json([
                'status' => true,
                'msg'    => 'OTP verified successfully.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Error verifying OTP: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to verify OTP.',
            ], 200);
        }
    }

    /**
     * Step 3: Reset Password (also double-checks OTP)
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|exists:users,email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'email.required'                 => 'Please enter your email address.',
            'email.email'                    => 'Please enter a valid email address.',
            'email.exists'                   => 'We could not find a user with this email.',
            'otp.required'                   => 'Please enter the OTP.',
            'otp.digits'                     => 'OTP must be a 6-digit code.',
            'password.required'              => 'Please enter a new password.',
            'password.min'                   => 'Password must be at least 8 characters.',
            'password_confirmation.required' => 'Please confirm your new password.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg'    => $validator->errors()->first(),
            ], 422);
        }

        $email    = $request->email;
        $otp      = $request->otp;
        $password = $request->password;

        try {
            // 1. Fetch OTP record
            $record = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->orderByDesc('created_at')
                ->first();

            if (!$record) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'No OTP found for this email. Please request a new one.',
                ], 200);
            }

            // 2. Expiry check (same as in verifyOtp)
            $createdAt = Carbon::parse($record->created_at);
            if ($createdAt->lt(Carbon::now()->subMinutes(10))) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'OTP has expired. Please request a new one.',
                ], 200);
            }

            // 3. Check OTP
            if (!Hash::check($otp, $record->token)) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'Invalid OTP. Please try again.',
                ], 200);
            }

            // 4. Update user password
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'msg'    => 'User not found.',
                ], 200);
            }

            $user->password = Hash::make($password);
            $user->save();

            // 5. Delete OTP after successful reset
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Your password has been reset successfully.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Error resetting password: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'msg'    => 'Unable to reset password right now.',
            ], 200);
        }
    }
}
