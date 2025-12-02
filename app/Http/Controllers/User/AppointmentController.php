<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    protected $viewFolderPath = 'screens.user.';

    public function index()
    {
        $user = Auth::user();

        // Get appointments for this user (through leads.user_id)
        $appointments = Appointment::with(['lead.listing'])
            ->whereHas('lead', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderByDesc('appointment_date')
            ->paginate(10);

        return view($this->viewFolderPath . 'appointments', compact('appointments'));
    }
}
