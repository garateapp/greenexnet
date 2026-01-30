<?php

namespace App\Console\Commands;

use App\Mail\ControlAccessReportMail;
use App\Services\ControlAccessReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendControlAccessReport extends Command
{
    protected $signature = 'report:control-access';

    protected $description = 'Envía el reporte diario de control de acceso.';

    public function handle(ControlAccessReportService $service): int
    {
        $timezone = config('app.timezone', 'America/Santiago');
        $reportDate = Carbon::now($timezone)->subDay();
        $start = $reportDate->copy()->startOfDay();
        $end = $reportDate->copy()->endOfDay();

        $recipients = $this->resolveRecipients();
        if (empty($recipients['to'])) {
            $this->error('No hay destinatarios configurados para CONTROL_ACCESS_REPORT_TO.');
            return self::FAILURE;
        }

        $rows = $service->buildForRange($start, $end);
        $subjectLine = $this->buildSubject($start, $end);

        $mail = Mail::to($recipients['to']);
        if (!empty($recipients['cc'])) {
            $mail->cc($recipients['cc']);
        }
        if (!empty($recipients['bcc'])) {
            $mail->bcc($recipients['bcc']);
        }

        $mail->send(new ControlAccessReportMail($rows, $start, $end, $subjectLine));

        $this->info('Reporte de control de acceso enviado.');
        return self::SUCCESS;
    }

    private function resolveRecipients(): array
    {
        return [
            'to' => $this->parseRecipientList(config('reports.control_access.to')),
            'cc' => $this->parseRecipientList(config('reports.control_access.cc')),
            'bcc' => $this->parseRecipientList(config('reports.control_access.bcc')),
        ];
    }

    private function parseRecipientList(?string $raw): array
    {
        return collect(explode(',', (string) $raw))
            ->map(fn ($value) => trim($value))
            ->filter(fn ($value) => $value !== '')
            ->values()
            ->all();
    }

    private function buildSubject(Carbon $start, Carbon $end): string
    {
        $base = config('reports.control_access.subject', 'Reporte Control de Acceso');
        $range = $start->format('Y-m-d');

        return Str::of($base)
            ->append(' ')
            ->append($range)
            ->toString();
    }
}
