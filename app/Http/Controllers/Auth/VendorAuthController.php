<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileFormRequest;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorAuthController extends Controller
{
    public function __construct(private AuthRepositoryInterface $authRepo){}

    public function register(Request $request)
    {
        try{
            $this->authRepo->register($request, 'vendor');

            return response()->json([
                'status' => true,
                'message' => 'Your Account Has Been Created Successfully!',
            ],200);
        }
        catch(\Exception $e){

            return response()->json([
                'status' => false,
                'message' => $e,
            ],200);
        }
    }

    public function login(Request $request)
    {
        $user = $this->authRepo->login($request);

        if ($user && $user->role === 'vendor') {
            return response()->json([
                'status'  => true,
                'message' => null,
                'url' => route($user->role.'.dashboard')
            ], 200);

        } else {

            // Agar admin nahi hai to logout kara do safety ke liye
            Auth::logout();

            return response()->json([
                'status'  => false,
                'message' => 'The provided credentials do not match our admin records.',
                'url'     => null,
            ], 200);
        }
    }


    public function destroy()
    {
        $this->authRepo->logout();
        return redirect()->route('index');
    }

    public function profileView()
    {
        $user = $this->authRepo->viewProfile();
        return view('screens.vendor.profile', compact('user'));
    }

    public function profileStore(ProfileFormRequest $request)
    {

        try {

            $this->authRepo->saveProfile($request);

            return response()->json([
                'status' => true,
                'message' => 'Your Profile Information Has Been Saved Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }

    public function profileImageStore(Request $request)
    {
        try {
            $this->authRepo->uploadProfilePicture($request);
            return response()->json([
                'status' => true,
                'message' => 'Your Profile Picture Has Been Uploaded Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }

    public function profileImageDestory(Request $request)
    {
        try {
            $this->authRepo->deleteProfilePicture();

            return response()->json([
                'status' => true,
                'message' => 'Your Profile Picture Has Been Deleted Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }
    public function profileLogoStore(Request $request)
    {
        try {
            $this->authRepo->uploadProfileLogo($request);
            return response()->json([
                'status' => true,
                'message' => 'Your Profile Logo Has Been Uploaded Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }

    public function profileLogoDestory(Request $request)
    {
        try {
            $this->authRepo->deleteProfileLogo();

            return response()->json([
                'status' => true,
                'message' => 'Your Profile Logo Has Been Deleted Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }

    public function passwordUpdate(Request $request)
    {
        try {
            $this->authRepo->updatePassword($request);

            return response()->json([
                'status' => true,
                'message' => 'Your new Password Has Been Updated Successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }
}
