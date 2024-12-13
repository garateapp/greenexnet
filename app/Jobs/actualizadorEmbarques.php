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
use Carbon\Carbon;
use Illuminate\Support\Collection;
class actualizadorEmbarques implements ShouldQueue
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
        $cargados = Embarque::orderBy('num_embarque', 'desc')->first();

        $embarques = DB::connection("sqlsrv")->table('dbo.V_PKG_Embarques')
            ->select(
                'n_embarque',
                'id_destinatario',
                'n_destinatario',
                'fecha_embarque',
                DB::RAW('DATEPART(WEEK, etd) as Semana'),
                'n_packing_origen',
                'n_naviera',
                'n_nave',
                'contenedor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_etiqueta',
                DB::RAW('SUM(Cantidad) as Cajas'),
                DB::RAW('SUM(peso_neto) as Peso_neto'),
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'n_packing_origen',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'total_pallets',
                'numero_referencia',
                'nave'
            )
            //->where(DB::raw('DATEPART(WEEK, etd)'), '>', 48)
            ->where('n_embarque', '>', $cargados->num_embarque)
            ->where('id_exportadora','=','22')
            ->whereNotNull('id_destinatario')
            ->whereNotNull('n_destinatario')
            ->groupBy(
                'n_embarque',

                'n_destinatario',
                'id_destinatario',
                'fecha_embarque',
                'n_packing_origen',
                'n_naviera',
                'n_nave',
                'contenedor',
                'N_Especie',
                'N_Variedad',
                'n_embalaje',
                't_embalaje',
                'n_etiqueta',
                'n_puerto_origen',
                'n_pais_destino',
                'n_puerto_destino',
                'transporte',
                'n_packing_origen',
                'total_pallets',
                'etd',
                'eta',
                'numero_reserva_agente_naviero',
                'numero_referencia',
                'nave'
            )->get();
        $lstEmbarque = collect();

        foreach ($embarques as $embarque) {
            $objEmbarque = new Embarque();
            $annioEmb = Carbon::parse($embarque->fecha_embarque)->year;
            $agno = date('Y');

            if (date('Y') == Carbon::parse($embarque->fecha_embarque)->year) {
                $temporada = $annioEmb . '-' . ($annioEmb + 1);
            } else {
                $temporada = ($annioEmb - 1) . '-' . ($annioEmb);
            }

            $objEmbarque->temporada = $temporada;
            $objEmbarque->num_embarque = $embarque->n_embarque;
            $objEmbarque->id_cliente = $embarque->id_destinatario;
            $objEmbarque->n_cliente = $embarque->n_destinatario;
            $objEmbarque->semana = $embarque->Semana;
            $objEmbarque->planta_carga = $embarque->n_packing_origen;
            $objEmbarque->n_naviera = $embarque->n_naviera;
            $objEmbarque->nave = $embarque->n_nave;
            $objEmbarque->num_contenedor = $embarque->contenedor;
            $objEmbarque->especie = $embarque->N_Especie;
            $objEmbarque->variedad = $embarque->N_Variedad;
            $objEmbarque->embalajes = $embarque->n_embalaje;
            $objEmbarque->etiqueta = $embarque->n_etiqueta;
            $objEmbarque->cajas = $embarque->Cajas;
            $objEmbarque->peso_neto = $embarque->Peso_neto;
            $objEmbarque->puerto_embarque = $embarque->n_puerto_origen;
            $objEmbarque->pais_destino = $embarque->n_pais_destino;
            $objEmbarque->puerto_destino = $embarque->n_puerto_destino;
            $objEmbarque->mercado = $embarque->transporte;
            $objEmbarque->etd_estimado = Carbon::parse($embarque->etd)->format('d-m-Y H:i:s'); //$embarque->etd;
            $objEmbarque->eta_estimado = Carbon::parse($embarque->eta)->format('d-m-Y H:i:s'); //$embarque->eta;
            $objEmbarque->numero_reserva_agente_naviero = $embarque->numero_reserva_agente_naviero;
            $objEmbarque->cant_pallets = $embarque->total_pallets;
            $objEmbarque->transporte = $embarque->transporte;


            $lstEmbarque->push($objEmbarque);

        }
        $lstEmbarqueAgrupado = $lstEmbarque->groupBy('num_embarque');
        $lstEmbarque = new Collection();
        $lstEmbarque = $lstEmbarqueAgrupado->map(function ($embarqueAgrupado, $num_embarque) {

            return [
                'num_embarque' => $num_embarque,
                'id_cliente' => $embarqueAgrupado[0]->id_cliente,
                'n_cliente' => $embarqueAgrupado[0]->n_cliente,
                'semana' => $embarqueAgrupado[0]->semana,
                'planta_carga' => $embarqueAgrupado[0]->planta_carga,
                'n_naviera' => $embarqueAgrupado[0]->n_naviera,
                'nave' => $embarqueAgrupado[0]->nave,
                'num_contenedor' => $embarqueAgrupado[0]->num_contenedor,
                'especie' => $embarqueAgrupado[0]->especie,
                'variedad' => collect($embarqueAgrupado->pluck('variedad')->toArray())
                    ->filter() // Eliminar valores nulos o vacíos
                    ->unique() // Asegurar valores únicos
                    ->implode(', '),
                'embalajes' => collect($embarqueAgrupado->pluck('embalajes')->toArray())
                    ->filter() // Eliminar valores nulos o vacíos
                    ->map(function ($embalaje) {
                        // Extraer únicamente los valores de Kg con una expresión regular
                        preg_match('/(\d+(?:[.,]\d+)?)\s*kg/i', $embalaje, $matches);
                        return isset($matches[1]) ? $matches[1] . ' Kg' : null;
                    })
                    ->filter() // Eliminar valores nulos generados por embalajes sin Kg
                    ->unique() // Asegurar valores únicos
                    ->implode(', '),
                'etiqueta' => $embarqueAgrupado[0]->etiqueta,
                'cajas' => $embarqueAgrupado->sum('cajas'),
                'peso_neto' => $embarqueAgrupado->sum('peso_neto'),
                'puerto_embarque' => $embarqueAgrupado[0]->puerto_embarque,
                'pais_destino' => $embarqueAgrupado[0]->pais_destino,
                'puerto_destino' => $embarqueAgrupado[0]->puerto_destino,
                'mercado' => $embarqueAgrupado[0]->mercado,
                'etd_estimado' => Carbon::parse($embarqueAgrupado[0]->etd_estimado)->format('d-m-Y H:i:s'), //$embarqueAgrupado[0]->etd_estimado,
                'eta_estimado' => Carbon::parse($embarqueAgrupado[0]->eta_estimado)->format('d-m-Y H:i:s'), //$embarqueAgrupado[0]->eta_estimado,
                'numero_reserva_agente_naviero' => $embarqueAgrupado[0]->numero_reserva_agente_naviero,
                'cant_pallets' => $embarqueAgrupado->sum('cant_pallets'),
                'temporada' => $embarqueAgrupado[0]->temporada,
                'transporte' => $embarqueAgrupado[0]->transporte,
            ];
        });
        foreach ($lstEmbarque as $embarque) {

            $objEmbarque = new Embarque();

            $objEmbarque->temporada = $embarque["temporada"];
            $objEmbarque->num_embarque = $embarque["num_embarque"];
            $objEmbarque->id_cliente = $embarque["id_cliente"];
            $objEmbarque->n_cliente = $embarque["n_cliente"];
            $objEmbarque->semana = $embarque["semana"];
            $objEmbarque->planta_carga = $embarque["planta_carga"];
            $objEmbarque->n_naviera = isset($embarque["n_naviera"]) ? $embarque["n_naviera"] : 'sin información';
            $objEmbarque->nave = (isset($embarque["nave"])) ? $embarque["nave"] : "sin información";
            $objEmbarque->num_contenedor = $embarque["num_contenedor"];
            $objEmbarque->especie = $embarque["especie"];
            $objEmbarque->variedad = $embarque["variedad"];
            $objEmbarque->embalajes = $embarque["embalajes"];
            $objEmbarque->etiqueta = $embarque["etiqueta"];
            $objEmbarque->cajas = $embarque["cajas"];
            $objEmbarque->peso_neto = $embarque["peso_neto"];
            $objEmbarque->puerto_embarque = $embarque["puerto_embarque"];
            $objEmbarque->pais_destino = $embarque["pais_destino"];
            $objEmbarque->puerto_destino = $embarque["puerto_destino"];
            $objEmbarque->mercado = $embarque["mercado"];
            $objEmbarque->etd_estimado = Carbon::parse($embarque["etd_estimado"])->format('d-m-Y H:i:s'); //$embarque["etd_estimado"];
            $objEmbarque->eta_estimado = Carbon::parse($embarque["eta_estimado"])->format('d-m-Y H:i:s'); //$embarque["eta_estimado"];
            $objEmbarque->numero_reserva_agente_naviero = $embarque["numero_reserva_agente_naviero"];
            $objEmbarque->cant_pallets = $embarque["cant_pallets"];
            $objEmbarque->transporte = $embarque["transporte"];

            $objEmbarque->save();

        }
    }
}
