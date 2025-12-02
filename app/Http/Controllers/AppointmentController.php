<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        // $appointments = Appointment::with('lead')->latest('status')->paginate(5);
        $appointments = Appointment::with('lead')
            ->orderByRaw("FIELD(status, 'Pending', 'Completed', 'Cancelled')")
            ->latest()
            ->paginate(5);
        return view('screens.vendor.appointments.index', get_defined_vars());
    }

    public function update(Request $request, Appointment $appointment)
    {

        try {

            $appointment->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'The Appointment Status Has Been Updated Successfully!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Error Message : $e"
            ], 200);
        }
    }

    public function destroy(Appointment $appointment)
    {
        try {

            $appointment->delete();

            return response()->json([
                'status' => true,
                'message' => 'Your Appointment Has Been Deleted Successfully!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Error Message : $e"
            ], 200);
        }
    }
}
