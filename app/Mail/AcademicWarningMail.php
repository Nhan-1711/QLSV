<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcademicWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $gpa;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $gpa)
    {
        $this->student = $student;
        $this->gpa = $gpa;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cảnh báo học vụ - ' . $this->student->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.academic_warning',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
