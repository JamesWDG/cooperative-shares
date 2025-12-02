<?php

namespace App\Repositories\Interfaces;

interface AuthRepositoryInterface
{
    public function register($data, $role);
    public function login($credentials);
    public function logout();
    public function viewProfile();
    public function saveProfile($data);
    public function uploadProfilePicture($data);
    public function deleteProfilePicture();
    public function updatePassword($data);
    
    // Forgot Password Flow
    public function sendForgotOtp($data);
    public function verifyForgotOtp($data);
    public function resetForgotPassword($data);
}
