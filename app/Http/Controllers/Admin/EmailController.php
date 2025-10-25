<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Traits\EmailHelper;
use App\Models\Email;
use Webklex\IMAP\Facades\Client;

class EmailController extends Controller
{
    use EmailHelper;

    // Show send email form
    public function create()
    {
        $documents = Document::where('document_type', 'outline')->orWhere('document_type', 'complete_document')->orWhere('document_type', 'flyer')->get();
        return view('admin.emails.create', compact('documents'));
    }

    // Send email
    public function send(Request $request)
    {
        $request->validate([
            'to_email' => 'required|string',
            'cc_email' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240',
        ]);

        // Parse TO recipients
        $recipients = collect(preg_split('/[,;]+/', $request->to_email))
            ->map(fn($email) => trim($email))
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        // Parse CC recipients (add permanent CC always)
        $ccRecipients = collect(preg_split('/[,;]+/', $request->cc_email ?? ''))
            ->map(fn($email) => trim($email))
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->push('admin@infotechkw.co')
            ->unique()
            ->values();

        if ($recipients->isEmpty()) {
            return back()->withErrors(['to_email' => 'Please provide at least one valid recipient email.']);
        }

        // Uploaded attachments
        $uploadedFiles = $request->file('attachments', []);

        // From Documents Library
        $docFiles = [];
        $docFileNames = [];
        if ($request->filled('attach_documents')) {
            $docs = Document::whereIn('id', $request->attach_documents)->get();
            foreach ($docs as $doc) {
                $path = storage_path('app/public/' . $doc->file_path);
                if (file_exists($path)) {
                    $docFiles[] = new \Illuminate\Http\File($path);
                    $docFileNames[] = basename($path);
                }
            }
        }

        // Merge both
        $attachments = array_merge($uploadedFiles, $docFiles);

        // Send email with all attachments
        $this->sendEmail(
            $recipients->toArray(),
            $request->subject,
            $request->message,
            $attachments,
            $ccRecipients->toArray()
        );

        // Store file names
        $fileNames = array_merge(
            collect($uploadedFiles)->map->getClientOriginalName()->toArray(),
            $docFileNames
        );

        Email::create([
            'from' => config('mail.from.address'),
            'to' => $recipients->join(', '),
            'cc' => $ccRecipients->join(', '),
            'subject' => $request->subject,
            'body' => $request->message,
            'attachments' => $fileNames,
            'is_read' => true,
        ]);

        return back()->with('success', 'Email sent successfully to: ' . $recipients->join(', ') .
            ($ccRecipients->isNotEmpty() ? ' (CC: ' . $ccRecipients->join(', ') . ')' : ''));
    }


     /**
     * Decode MIME-encoded strings safely without imap_mime_header_decode
     */
    private function decodeMimeStr($string, $targetCharset = 'UTF-8') {
        $decoded = iconv_mime_decode($string, 0, $targetCharset);
        return $decoded !== false ? $decoded : $string;
    }

    /**
     * Sync last 5 emails from this month (with attachments, CC, etc.)
     */
    public function syncInbox()
    {
        set_time_limit(120);

        $client = Client::account('default');
        $client->connect();

        $inbox = $client->getFolder('INBOX');

        $mails = $inbox->query()
            ->all()
            ->since(now()->startOfMonth())
            ->limit(5)
            ->setFetchOrder("desc")
            ->get();
        
        foreach ($mails as $mail) {
            $body = $mail->getTextBody() ?: strip_tags($mail->getHTMLBody());

            $attachments = [];
            if ($mail->getAttachments()) {
                $path = storage_path('app/public/documents');
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }

                foreach ($mail->getAttachments() as $attach) {
                    $originalName = $attach->name ?? 'attachment';
                    $cleanName = $this->decodeMimeStr($originalName);
                    $cleanName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $cleanName);
                    $filename = uniqid().'_'.$cleanName;
                    $attach->save($path, $filename);
                    $attachments[] = $filename;
                }
            }

            $to = [];
            $cc = [];

            // Try standard parsed fields
            if ($mail->getTo()) {
                foreach ($mail->getTo() as $addr) {
                    $to[] = $addr->mail;
                }
            }

            if ($mail->getCc()) {
                foreach ($mail->getCc() as $addr) {
                    $cc[] = $addr->mail;
                }
            }

            Email::updateOrCreate(
                ['uid' => $mail->uid],
                [
                    'from'        => $mail->from[0]->mail ?? '',
                    'to'          => $to,
                    'cc'          => $cc,
                    'subject'     => $this->decodeMimeStr($mail->subject ?? ''),
                    'body'        => $body,
                    'html_body'   => $mail->hasHTMLBody() ? $mail->getHTMLBody() : null,
                    'attachments' => $attachments,
                    'is_read'     => $mail->getFlags()->has('Seen'),
                    'uid'         => $mail->uid,
                ]
            );
        }

        $client->disconnect(); // ✅ close IMAP cleanly

        return back()->with('success', 'Last 5 emails from this month synced successfully!');
    }

    // List emails
    public function inbox()
    {
        $emails = Email::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.emails.inbox', compact('emails'));
    }

    // View single email
    public function view($id)
    {
        $email = Email::findOrFail($id);
        $email->update(['is_read' => true]);
        return view('admin.emails.view', compact('email'));
    }

    // Reply to email
    public function reply(Request $request, $id)
    {
        $email = Email::findOrFail($id);

        $request->validate([
            'cc_email' => 'nullable|string',
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240'
        ]);

        // Uploaded attachments
        $uploadedFiles = $request->file('attachments', []);

        // Parse CC recipients (include permanent CC)
        $ccRecipients = collect(preg_split('/[,;]+/', $request->cc_email ?? ''))
            ->map(fn($mail) => trim($mail))
            ->filter(fn($mail) => filter_var($mail, FILTER_VALIDATE_EMAIL))
            ->push('admin@infotechkw.co')
            ->unique()
            ->values();

        // From Documents Library
        $docFiles = [];
        $docFileNames = [];
        if ($request->filled('attach_documents')) {
            $docs = Document::whereIn('id', $request->attach_documents)->get();
            foreach ($docs as $doc) {
                $path = storage_path('app/public/' . $doc->file_path);
                if (file_exists($path)) {
                    $docFiles[] = new \Illuminate\Http\File($path);
                    $docFileNames[] = basename($path);
                }
            }
        }

        // Combine uploaded + library docs
        $attachments = array_merge($uploadedFiles, $docFiles);

        // Send reply email with all attachments
        $this->sendEmail(
            $email->from,
            'Re: ' . $email->subject,
            $request->message,
            $attachments,
            $ccRecipients->toArray()
        );

        // Store file names in DB
        $fileNames = array_merge(
            collect($uploadedFiles)->map->getClientOriginalName()->toArray(),
            $docFileNames
        );

        Email::create([
            'from' => config('mail.from.address'),
            'to' => $email->from,
            'cc' => $ccRecipients->join(', '),
            'subject' => 'Re: ' . $email->subject,
            'body' => $request->message,
            'attachments' => $fileNames,
            'is_read' => true,
        ]);

        return back()->with('success', 'Reply sent successfully!');
    }

}
