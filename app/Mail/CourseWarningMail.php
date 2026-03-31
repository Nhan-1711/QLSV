<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $grade;

    public function __construct($grade)
    {
        $this->grade = $grade;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cảnh báo kết quả học tập - ' . $this->grade->courseClass->course->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.course_warning',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
