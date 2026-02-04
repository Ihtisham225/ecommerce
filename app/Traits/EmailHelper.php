<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Exception;

trait EmailHelper
{
    public function sendEmail($to, $subject, $body, $attachments = [], $cc = [])
    {
        // Always include permanent CC
        $cc = collect(is_array($cc) ? $cc : [$cc])
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $to = is_array($to) ? $to : [$to];

        try {
            Mail::send('emails.main', ['body' => $body, 'subject' => $subject], function ($message) use ($to, $subject, $attachments, $cc) {
                $message->to($to)->subject($subject);

                if (!empty($cc)) {
                    $message->cc($cc);
                }

                if ($attachments) {
                    foreach ($attachments as $file) {
                        $path = $file->getRealPath();
                        $name = method_exists($file, 'getClientOriginalName')
                            ? $file->getClientOriginalName()
                            : basename($file->getPathname());
                        $mime = method_exists($file, 'getMimeType')
                            ? $file->getMimeType()
                            : mime_content_type($file->getPathname());

                        $message->attach($path, [
                            'as' => $name,
                            'mime' => $mime,
                        ]);
                    }
                }
            });
        } catch (Exception $e) {
            // Skip invalid emails, log if needed
            \Log::warning("Email could not be sent: " . $e->getMessage());
        }
    }
}
