<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use App\Mail\SendMail; // <-- yeh add karo
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ContactFormController extends Controller
{
    private function sendEmail(string $toEmail, array $data, string $template = 'contact_email'): void
    {
        try {
            Mail::to($toEmail)->send(new SendMail($data, $template));
            Log::info('Contact form email sent successfully to ' . $toEmail);
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email to ' . $toEmail . ': ' . $e->getMessage());
        }
    }
    public function index()
    {
        // Sare contact form submissions latest first
        $contacts = ContactForm::orderBy('id', 'desc')->get();

        return view('screens.admin.contact-forms.index', compact('contacts'));
    }

    public function detail(ContactForm $contact)
    {
        return view('screens.admin.contact-forms.detail', compact('contact'));
    }
    // NEW: Store contact form
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:191',
            'email'        => 'required|email|max:191',
            'phone_number' => 'nullable|string|max:100',
            'service'      => 'nullable|string|max:191',
            'message'      => 'required|string|max:2000',
        ]);
    
        DB::beginTransaction();
    
        try {
            // 1. Save data in DB
            $contact = ContactForm::create($data);
    
            // 2. Prepare email data
            $mailData = [
                'subject'   => 'Contact form received from ' . $contact->full_name,
                'form_data' => [
                    'full_name'    => $contact->full_name,
                    'email'        => $contact->email,
                    'phone_number' => $contact->phone_number,
                    'service'      => $contact->service,
                    'message'      => $contact->message,
                ],
            ];
    
            // 3. Admin email
            $adminEmail = 'info@cooperativeshares.com'; // later: info@cooperativeshares.com
    
            // 4. Send Email Inside Try/Catch
            try {
                Mail::to($adminEmail)->send(new SendMail($mailData, 'contact_email'));
    
            } catch (\Throwable $emailError) {
                // Log email specific error
                Log::error('Email FAILED to send: ' . $emailError->getMessage());
    
                // Rollback DB insertion also
                DB::rollBack();
    
                return back()->with('error', 'Form saved failed because email could not be sent. Please try again.');
            }
    
            // 5. If everything is perfect → Commit
            DB::commit();
    
            return back()->with('success', 'Thank you for contacting us. We will get back to you soon.');
    
        } catch (\Throwable $e) {
    
            // Log main error
            Log::error('Contact form failed: ' . $e->getMessage());
    
            // Make sure database is clean
            DB::rollBack();
    
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function storeForTest(Request $request)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:191',
            'email'        => 'required|email|max:191',
            'phone_number' => 'nullable|string|max:100',
            'service'      => 'nullable|string|max:191',
            'message'      => 'required|string|max:2000',
        ]);
    
        // Save in DB
        $contact = ContactForm::create($data);
    
        // Email ke liye data prepare karo
        $mailData = [
            'subject'   => 'Contact form received from ' . $contact->full_name,
            'form_data' => [
                'full_name'    => $contact->full_name,
                'email'        => $contact->email,
                'phone_number' => $contact->phone_number,
                'service'      => $contact->service,
                'message'      => $contact->message,
            ],
        ];
    
        // TEST admin email
        $adminEmail = 'webhost9783@gmail.com';
    
        // Debug mode - test email directlys
        try {
    
            // Use Mailable directly
            Mail::to($adminEmail)->send(new \App\Mail\SendMail($mailData, 'contact_email'));
    
        } catch (\Throwable $e) {
            // ERROR CATCH HERE!
            dd('EMAIL ERROR: ' . $e->getMessage());
        }
    
        // If no error — email sent
        dd("Email sent successfully to: " . $adminEmail);
    }

}
