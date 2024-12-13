<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Embarque;
use App\Models\Mensaje;
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\MensajeGenericoMailable;


class seguimientoembarques implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $mensaje=New Mensaje();
        $mensaje->mensaje='hola';
        Mail::to('carlos.alvarez@greenex.cl')->send(new MensajeGenericoMailable($mensaje,''));

    }
}
