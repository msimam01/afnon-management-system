<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Enquiry;
use Illuminate\Support\Facades\View;

class PreviewEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:preview {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preview email templates in browser';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        switch ($type) {
            case 'enquiry':
                $this->previewEnquiryEmail();
                break;
            case 'user-created':
                $this->previewUserCreatedEmail();
                break;
            case 'password-reset':
                $this->previewPasswordResetEmail();
                break;
            default:
                $this->error('Invalid email type. Available types: enquiry, user-created, password-reset');
                return 1;
        }

        return 0;
    }

    private function previewEnquiryEmail()
    {
        $enquiry = new Enquiry([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+234 801 234 5678',
            'subject' => 'Inquiry about farming programs',
            'message' => 'Hello, I am interested in learning more about the farming programs offered by AFNON. Could you please provide me with more information about the available courses and how to apply?',
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'created_at' => now(),
        ]);

        $enquiry->formatted_created_at = $enquiry->created_at->format('F j, Y \a\t g:i A');

        $html = View::make('emails.enquiry', compact('enquiry'))->render();

        $this->savePreview('enquiry', $html);
        $this->info('Enquiry email preview saved to: storage/app/email-previews/enquiry.html');
    }

    private function previewUserCreatedEmail()
    {
        $user = new User([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'created_at' => now(),
        ]);

        $password = 'TempPass123!';

        $html = View::make('emails.user-created', compact('user', 'password'))->render();

        $this->savePreview('user-created', $html);
        $this->info('User created email preview saved to: storage/app/email-previews/user-created.html');
    }

    private function previewPasswordResetEmail()
    {
        $url = url('/reset-password/token123?email=user@example.com');

        $html = View::make('emails.password-reset', compact('url'))->render();

        $this->savePreview('password-reset', $html);
        $this->info('Password reset email preview saved to: storage/app/email-previews/password-reset.html');
    }

    private function savePreview($type, $html)
    {
        $directory = storage_path('app/email-previews');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . '/' . $type . '.html';
        file_put_contents($filePath, $html);
    }
}

