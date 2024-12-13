<?php

namespace App\Mail;

use App\Models\Embarque;
use App\Models\Mensaje;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class MensajeGenericoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $archivoAdjunto;
    public $embarque;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mensaje $mensaje,string $archivoAdjunto)
    {
        $this->mensaje = $mensaje;
        $this->archivoAdjunto = $archivoAdjunto;

        $this->attachments();
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        $embarques=Embarque::whereNull('fecha_arribo_real')->where("transporte","=","AEREO")->orderBy('num_embarque','desc')->get();
        return $this->from('contacto@greenex.cl','COMEX Greenex')
                    ->subject('Seguimiento de Embarques')
                    ->view('mail.seguimiento-embarques', compact('embarques'))
                    ->with('data', $this->mensaje);
    }


    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {    $archivoAdjunto = Storage::path($this->archivoAdjunto);

        return [
            $archivoAdjunto,
        ];
    }
}
