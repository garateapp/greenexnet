<?php

namespace App\Mail;

use App\Models\Mensaje;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class MensajeGenericoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $archivoAdjunto;
    public $storageDisk;
    public $totalsByTransportAndClient;
    public $totalsByTransport;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mensaje $mensaje, ?string $archivoAdjunto = null, ?string $storageDisk = null, $totalsByTransportAndClient = null, $totalsByTransport = null)
    {
        $this->mensaje = $mensaje;
        $this->archivoAdjunto = $archivoAdjunto;
        $this->storageDisk = $storageDisk ?? config('filesystems.default');
        $this->totalsByTransportAndClient = collect($totalsByTransportAndClient ?? []);
        $this->totalsByTransport = collect($totalsByTransport ?? []);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        $mail = $this->from('contacto@greenex.cl', 'COMEX Greenex')
            ->subject('Seguimiento de Embarques')
            ->view('mail.seguimiento-embarques', [
                'totalsByTransportAndClient' => $this->totalsByTransportAndClient,
                'totalsByTransport' => $this->totalsByTransport,
            ])
            ->with('data', $this->mensaje);

        if (
            $this->archivoAdjunto
            && Storage::disk($this->storageDisk)->exists($this->archivoAdjunto)
        ) {
            $mail->attachFromStorageDisk(
                $this->storageDisk,
                $this->archivoAdjunto,
                basename($this->archivoAdjunto),
                ['mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            );
        }

        return $mail;
    }
}
