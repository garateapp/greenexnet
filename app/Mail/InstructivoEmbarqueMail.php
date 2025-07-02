<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructivoEmbarqueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instructivoEmbarque;
    public $excelFilePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($instructivoEmbarque, $excelFilePath)
    {
        $this->instructivoEmbarque = $instructivoEmbarque;
        $this->excelFilePath = $excelFilePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Instructivo de Embarque N° ' . $this->instructivoEmbarque->instructivo)
                    ->view('mail.instructivo_embarque') // You might want to create this view
                    ->attach($this->excelFilePath, [
                        'as' => 'Instructivo_Embarque_' . $this->instructivoEmbarque->instructivo . '.xlsx',
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
