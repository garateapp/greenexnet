<?php

namespace App\Mail;

use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ControlAccessReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $rows;
    public CarbonInterface $start;
    public CarbonInterface $end;
    public string $subjectLine;

    public function __construct(Collection $rows, CarbonInterface $start, CarbonInterface $end, string $subjectLine)
    {
        $this->rows = $rows;
        $this->start = $start;
        $this->end = $end;
        $this->subjectLine = $subjectLine;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('mail.control-access-report', [
                'rows' => $this->rows,
                'start' => $this->start,
                'end' => $this->end,
            ]);
    }
}
