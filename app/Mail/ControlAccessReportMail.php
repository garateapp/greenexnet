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
    public array $summary;
    public Collection $totalsByDepartment;
    public CarbonInterface $start;
    public CarbonInterface $end;
    public string $subjectLine;

    public function __construct(Collection $rows, array $summary, Collection $totalsByDepartment, CarbonInterface $start, CarbonInterface $end, string $subjectLine)
    {
        $this->rows = $rows;
        $this->summary = $summary;
        $this->totalsByDepartment = $totalsByDepartment;
        $this->start = $start;
        $this->end = $end;
        $this->subjectLine = $subjectLine;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('mail.control-access-report', [
                'rows' => $this->rows,
                'summary' => $this->summary,
                'totalsByDepartment' => $this->totalsByDepartment,
                'start' => $this->start,
                'end' => $this->end,
            ]);
    }
}
