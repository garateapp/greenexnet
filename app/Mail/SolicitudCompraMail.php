<?php

namespace App\Mail;

use App\Models\SolicitudCompra;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudCompraMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $tipo;
    public $estadoAnterior;
    public $estadoNuevo;

    public function __construct(SolicitudCompra $solicitud, string $tipo, ?string $estadoAnterior = null, ?string $estadoNuevo = null)
    {
        $this->solicitud = $solicitud;
        $this->tipo = $tipo;
        $this->estadoAnterior = $estadoAnterior;
        $this->estadoNuevo = $estadoNuevo;
    }

    public function build()
    {
        $subject = $this->tipo === 'created'
            ? 'Nueva solicitud de compra #' . $this->solicitud->id
            : 'Cambio de estado solicitud #' . $this->solicitud->id;

        return $this->subject($subject)
            ->view('mail.solicitud-compra');
    }
}
