<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadFormRequest;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\NotificationService;
use Carbon\Carbon; // make sure this is at the top with other uses

class LeadController extends Controller
{
    /**
     * @var NotificationService
     */
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $leads = Lead::whereHas('listing', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('appointment')->paginate(5);

        return view('screens.vendor.leads.index', compact('leads'));
    }
    public function store(LeadFormRequest $request)
    {
        DB::beginTransaction();
    
        try {
            $authUser = Auth::user();
    
            // 1) Fetch Listing + its User (Vendor)
            $listing = Listing::with('user')->findOrFail($request->listing_id);
            $vendor  = $listing->user; // this is the vendor
    
            if (!$vendor || $vendor->role !== 'vendor') {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Property owner not found.']);
            }
    
            // Build names from FIRST + LAST names
            $senderFullName = trim(($authUser->first_name ?? '') . ' ' . ($authUser->last_name ?? ''));
            $vendorFullName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
    
            // 2) Create Lead
            $lead = Lead::create([
                'user_id'      => $authUser->id,
                'listing_id'   => $request->listing_id,
                'name'         => $senderFullName,
                'phone_number' => $authUser->phone_number,
                'email'        => $authUser->email,
                'message'      => $request->message,
                'status'       => 'Pending',
            ]);
    
            // 3) Create Notification to Vendor
            $slug          = Str::slug('lead-' . $lead->id . '-' . uniqid());
            $propertyTitle = $listing->property_title ?? 'Property #' . $listing->id;
    
            $title = "New lead received on your property";
            $content = "You have received a new lead on your property: \"{$propertyTitle}\".
    Sender: {$senderFullName}
    Phone: {$lead->phone_number}
    Email: {$lead->email}
    
    Message:
    {$lead->message}";
    
            $this->notificationService->createNotification(
                $authUser->id,      // sender (lead creator)
                $vendor->id,        // receiver (vendor)
                $title,
                $content,
                $slug,
                'lead',
                'Vendor'
            );
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Your inquiry has been sent successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            Log::error('Lead creation failed', [
                'user_id'    => Auth::id(),
                'listing_id' => $request->listing_id,
                'error'      => $e->getMessage(),
            ]);
    
            // dd($e->getMessage()); // use only for debugging, then remove
    
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong while submitting your inquiry. Please try again.',
            ]);
        }
    }


    public function storeOld(LeadFormRequest $request)
    {
        try {

            $lead = Lead::create([
                'user_id' => Auth::id(),
                'listing_id' => $request->listing_id,
                'name' => Auth::user()->full_name,
                'phone_number' => Auth::user()->phone_number,
                'email' => Auth::user()->email,
                'message' => $request->message,
                'status' => 'Pending',
            ]);

            return redirect()->back();

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['Error' => "Error Message : $e"]);
        }
    }

    public function show(Lead $lead)
    {
        dd($lead);
    }

    public function edit(Lead $lead)
    {
        if($lead->appointment)
        {
            return redirect()->back();
        }

        $lead->update([
            'status' => 'Viewed'
        ]);

        return view('screens.vendor.leads.edit', compact('lead'));
    }
    public function update(Request $request, Lead $lead)
    {
        DB::beginTransaction();
    
        try {
            $vendor = Auth::user(); // Vendor (property owner) creating appointment
    
            // 1) Mark lead as viewed
            $lead->update([
                'status' => 'Viewed',
            ]);
    
            // 2) Create appointment
            $appointment = Appointment::create([
                'lead_id'          => $lead->id,
                'appointment_date' => $request->appointment_date,
                'status'           => 'Pending',
            ]);
    
            // 3) Load related models for notification
            $lead->load('listing', 'user');
    
            $user    = $lead->user;      // the user who created the lead
            $listing = $lead->listing;   // the property
    
            if ($user && $listing) {
                // Full names
                $vendorFullName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                $userFullName   = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
    
                // Property title
                $propertyTitle = $listing->property_title ?? ('Property #' . $listing->id);
    
                // Appointment date formatted (you already cast it in model)
                $appointmentDateFormatted = $appointment->appointment_date
                    ? $appointment->appointment_date->format('F d, Y h:i A')
                    : null;
    
                // Notification title
                $title = 'New Appointment Scheduled for Your Inquiry';
    
                // Paragraph-style content
                $content = "Hello {$userFullName}, your inquiry for the property \"{$propertyTitle}\" has been viewed and the vendor {$vendorFullName} has scheduled an appointment for you on {$appointmentDateFormatted}. Thank you for using our service.";
    
                // Slug for notification
                $slug = Str::slug('appointment-' . $appointment->id . '-' . uniqid());
    
                // Send notification (Vendor → User)
                $this->notificationService->createNotification(
                    $vendor->id,   // sender: vendor
                    $user->id,     // receiver: user (lead creator)
                    $title,
                    $content,
                    $slug,
                    'appointment', // type
                    'User'         // notification_for
                );
            }
    
            DB::commit();
    
            return response()->json([
                'status'  => true,
                'message' => 'Your Appointment Has Been Made!',
                'url'     => route('vendor.leads'),
            ], 200);
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            Log::error('Lead appointment creation failed', [
                'lead_id'       => $lead->id ?? null,
                'user_id'       => Auth::id(),
                'error_message' => $e->getMessage(),
            ]);
    
            return response()->json([
                'status'  => false,
                'message' => "Error Message : " . $e->getMessage() . "!",
            ], 200);
        }
    }


    public function updateOld(Request $request, Lead $lead)
    {
        try {

            $lead->update([
                'status' => 'Viewed'
            ]);

            $appointment = Appointment::create([
                'lead_id' => $lead->id,
                'appointment_date' => $request->appointment_date,
                'status' => 'Pending'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Your Appointment Has Been Made!',
                'url' => route('vendor.leads')
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Error Message : $e!"
            ], 200);
        }
    }

    public function destroy(Lead $lead)
    {

        try {

            $lead->delete();

            return response()->json([
                'status' => true,
                'message' => 'The Lead Has Been Deleted Successfully!'
            ],200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Error Message : $e"
            ],200);
        }
    }

}
