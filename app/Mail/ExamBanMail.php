<?php

namespace App\Mail;

use App\Models\CourseClass;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExamBanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $courseClass;
    public $absentCount;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, CourseClass $courseClass, int $absentCount)
    {
        $this->student = $student;
        $this->courseClass = $courseClass;
        $this->absentCount = $absentCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo cấm thi - ' . $this->courseClass->course->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.exam_ban',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
