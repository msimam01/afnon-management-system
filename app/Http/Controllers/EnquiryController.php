<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Mail\EnquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class EnquiryController extends Controller
{
    /**
     * Store a newly created enquiry
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'honeypot' => 'nullable|string|max:0', // Honeypot field for spam protection
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check honeypot field (spam protection)
        if (!empty($request->honeypot)) {
            // This is likely spam, silently ignore
            return redirect()->back()
                ->with('success', 'Thank you for your message. We will get back to you soon.');
        }

        // Check for spam patterns
        $isSpam = $this->detectSpam($request);


        // Create the enquiry
        $enquiry = Enquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => $isSpam,
        ]);

        // Send email notification if not spam
        if (!$isSpam) {
            try {
                $adminEmail = config('mail.admin_email', 'admin@afnon.com.ng');
                Mail::to($adminEmail)->send(new EnquiryMail($enquiry));
            } catch (\Exception $e) {
                // Log the error but don't fail the request
                Log::error('Failed to send enquiry email: ' . $e->getMessage());
            }
        }

        // Show success message
        ToastMagic::success('Thank you for your enquiry! We will get back to you soon.');

        return redirect()->back()->with('success', 'Thank you for your enquiry! We will get back to you soon.');
    }

    /**
     * Display a listing of enquiries (Admin)
     */
    public function index(Request $request)
    {
        $query = Enquiry::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'unread':
                    $query->unread();
                    break;
                case 'read':
                    $query->read();
                    break;
                case 'spam':
                    $query->spam();
                    break;
            }
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->dateRange($request->date_from, $request->date_to);
        }

        $enquiries = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.enquiries.index', compact('enquiries'));
    }

    /**
     * Display the specified enquiry
     */
    public function show(Enquiry $enquiry)
    {
        // Mark as read if not already
        if (!$enquiry->read_at) {
            $enquiry->markAsRead();
        }

        return view('admin.enquiries.show', compact('enquiry'));
    }

    /**
     * Mark enquiry as spam
     */
    public function markAsSpam(Enquiry $enquiry)
    {
        $enquiry->markAsSpam();

        ToastMagic::success('Enquiry marked as spam.');
        return redirect()->back();
    }

    /**
     * Mark enquiry as not spam
     */
    public function markAsNotSpam(Enquiry $enquiry)
    {
        $enquiry->markAsNotSpam();

        ToastMagic::success('Enquiry marked as not spam.');
        return redirect()->back();
    }

    /**
     * Delete enquiry
     */
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        ToastMagic::success('Enquiry deleted successfully.');
        return redirect()->route('admin.enquiries.index');
    }

    /**
     * Detect spam patterns
     */
    private function detectSpam(Request $request)
    {
        $spamKeywords = [
            'viagra', 'casino', 'lottery', 'winner', 'congratulations',
            'click here', 'free money', 'make money', 'work from home',
            'bitcoin', 'cryptocurrency', 'investment opportunity'
        ];

        $text = strtolower($request->name . ' ' . $request->subject . ' ' . $request->message);

        foreach ($spamKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }

        // Check for excessive links
        $linkCount = substr_count($text, 'http');
        if ($linkCount > 2) {
            return true;
        }

        // Check for excessive caps
        $capsRatio = strlen(preg_replace('/[^A-Z]/', '', $text)) / strlen($text);
        if ($capsRatio > 0.5) {
            return true;
        }

        return false;
    }
}
