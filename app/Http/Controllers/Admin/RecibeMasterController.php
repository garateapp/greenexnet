<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRecibeMasterRequest;
use App\Http\Requests\StoreRecibeMasterRequest;
use App\Http\Requests\UpdateRecibeMasterRequest;
use App\Models\RecibeMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Services\PtiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use DB;
use DOMDocument;

class RecibeMasterController extends Controller
{
    use CsvImportTrait;
    protected $soapService;
    protected $usuario;
    protected $pass;
    protected $url;
    protected $IdExportador;
    protected $especies;
    protected $codCentral;

    public function __construct()
    {
        if (env('APP_DEBUG') == true) {
            $this->usuario = env('PTI_USER_TEST');
            $this->pass = env('PTI_USER_TEST_PASS');
            $this->url = env('PTI_WSDL_URL_TEST');
        } else {
            $this->usuario = env('PTI_USER');
            $this->pass = env('PTI_USER_PASS');
            $this->url = env('PTI_WSDL_URL_PROD');
        }
        if ($this->IdExportador == null) {
            $data = $this->AccesoSis();
            $this->IdExportador = ((string)$data["Usuario"]["IdExportador"]);
            $this->especies = $data["Especies"];
        }
        $this->codCentral = env("PTI_CODCENTRAL");
    }
    public function index(Request $request)
    {
        abort_if(Gate::denies('recibe_master_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RecibeMaster::query()->select(sprintf('%s.*', (new RecibeMaster)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', ' ');
            $table->addColumn('actions', ' ');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'recibe_master_show';
                $editGate      = 'recibe_master_edit';
                $deleteGate    = 'recibe_master_delete';
                $crudRoutePart = 'recibe-masters';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('especie', function ($row) {
                return $row->especie ? $row->especie : '';
            });
            $table->editColumn('exportador', function ($row) {
                return $row->exportador ? $row->exportador : '';
            });
            $table->editColumn('partida', function ($row) {
                return $row->partida ? $row->partida : '';
            });
            $table->editColumn('estado', function ($row) {
                return $row->estado ? RecibeMaster::ESTADO_SELECT[$row->estado] : '';
            });
            $table->editColumn('cod_central', function ($row) {
                return $this->codCentral ? $this->codCentral : '';
            });
            $table->editColumn('cod_productor', function ($row) {
                return $row->cod_productor ? $row->cod_productor : '';
            });
            $table->editColumn('nro_guia_despacho', function ($row) {
                return $row->nro_guia_despacho ? $row->nro_guia_despacho : '';
            });

            $table->editColumn('cod_variedad', function ($row) {
                return $row->cod_variedad ? $row->cod_variedad : '';
            });
            $table->editColumn('estiba_camion', function ($row) {
                return $row->estiba_camion ? RecibeMaster::ESTIBA_CAMION_SELECT[$row->estiba_camion] : '';
            });
            $table->editColumn('esponjas_cloradas', function ($row) {
                return $row->esponjas_cloradas ? RecibeMaster::ESPONJAS_CLORADAS_SELECT[$row->esponjas_cloradas] : '';
            });
            $table->editColumn('nro_bandeja', function ($row) {
                return $row->nro_bandeja ? $row->nro_bandeja : '';
            });
            $table->editColumn('hora_llegada', function ($row) {
                return $row->hora_llegada ? $row->hora_llegada : '';
            });
            $table->editColumn('kilo_muestra', function ($row) {
                return $row->kilo_muestra ? $row->kilo_muestra : '';
            });
            $table->editColumn('kilo_neto', function ($row) {
                return $row->kilo_neto ? $row->kilo_neto : '';
            });
            $table->editColumn('temp_ingreso', function ($row) {
                return $row->temp_ingreso ? $row->temp_ingreso : '';
            });
            $table->editColumn('temp_salida', function ($row) {
                return $row->temp_salida ? $row->temp_salida : '';
            });
            $table->editColumn('lote', function ($row) {
                return $row->lote ? $row->lote : '';
            });
            $table->editColumn('huerto', function ($row) {
                return $row->huerto ? $row->huerto : '';
            });
            $table->editColumn('hidro', function ($row) {
                return $row->hidro ? $row->hidro : '';
            });
            $table->editColumn('fecha_envio', function ($row) {
                return $row->fecha_envio ? $row->fecha_envio : '';
            });
            $table->editColumn('respuesta_envio', function ($row) {
                return $row->respuesta_envio ? $row->respuesta_envio : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.recibeMasters.index');
    }

    public function create()
    {
        abort_if(Gate::denies('recibe_master_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recibeMasters.create');
    }

    public function store(StoreRecibeMasterRequest $request)
    {

        // $recibeMaster = RecibeMaster::create($request->all());

        // numero_g_recepcion --lote
        // ,fecha_g_recepcion --FechaRecepcion
        // ,Hora_g_Recepcion --HoraLlegada
        // ,fecha_cosecha --FechaCosecha
        // ,c_productor --CodProductor
        // ,c_especie --Especie
        // ,c_variedad --CodVariedad
        // ,peso_neto --KILO NETO
        // ,numero_documento_recepcion --NroGuiaDespacho
        // ,NS_Productor --Huerto
        // ,N_tratamiento --Hidro
        //   --Exportador
        //   --Partida
        //   --Estado
        //   --CodCentral
        //   --TempIngreso No esta en FX
        //   --TempSalida No esta en FX
        //   --EstibaCamion No esta en FX
        //   --EsponjasCloradas No esta en FX
        //   --NroBandeja No esta en FX
        //   --KiloMuestra No esta en FX
        $recibeMaster = new RecibeMaster();
        $rMaster = RecibeMaster::where('nro_guia_despacho', $request->nro_guia_despacho)->first();

        if ($rMaster) {
            $recibeMaster = $rMaster;
        }


        $recibeMaster->nro_guia_despacho = $request->nro_guia_despacho;
        $recibeMaster->hora_llegada = date('H:i:s', strtotime($request->hora_llegada)); //$request->hora_llegada;
        $recibeMaster->fecha_cosecha = date('Y-m-d H:i:s', strtotime($request->fecha_cosecha));
        $recibeMaster->cod_productor = $request->cod_productor;
        $recibeMaster->especie = $request->especie;
        $recibeMaster->cod_variedad = $request->cod_variedad;
        $recibeMaster->kilo_neto = $request->kilo_neto;
        $recibeMaster->huerto = $request->huerto;
        if ($request->hidro == 'Sin Tratamiento') {
            $recibeMaster->hidro = "No";
        } else {
            $recibeMaster->hidro = "Si";
        }
        $recibeMaster->exportador = $request->exportador;
        $recibeMaster->partida = $request->partida;
        $recibeMaster->estado = $request->estado;
        $recibeMaster->lote = $request->lote;
        $recibeMaster->cod_central = $this->codCentral;
        $recibeMaster->temp_ingreso = $request->temp_ingreso;
        $recibeMaster->temp_salida = $request->temp_salida;
        $recibeMaster->estiba_camion = $request->estiba_camion;
        $recibeMaster->esponjas_cloradas = $request->esponjas_cloradas;
        $recibeMaster->nro_bandeja = $request->nro_bandeja;
        $recibeMaster->kilo_muestra = $request->kilo_muestra;
        $recibeMaster->fecha_recepcion = date('Y-m-d H:i:s', strtotime($request->fecha_recepcion)); //$request->fecha_recepcion;
        if ($recibeMaster->fecha_envio != null) {
            $recibeMaster->estado = "REEMPLAZO";
        } else {
            $recibeMaster->estado = "NUEVO";
        }
        $res = $recibeMaster;
        //dd($recibeMaster);
        $response = $this->RecibeMasterRequest($res);
        $recibeMaster->save();
        $response = trim($response); // Eliminar espacios al inicio y al final
        $response = str_replace(["\n", "\t"], '', $response); // Eliminar saltos de línea y tabulaciones
        $response = html_entity_decode($response);
        libxml_use_internal_errors(true); // Habilitar manejo de errores
        $decodedXml = html_entity_decode($response);

        $decodedXml = str_replace("\/", "/", $decodedXml);
        $dom = new DOMDocument();
        $xml = simplexml_load_string($decodedXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_use_internal_errors(true);
        $decodedXml = str_replace('<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns1=\"urn:Organization\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\" SOAP-ENV:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\"><SOAP-ENV:Body><ns1:RecibeMasterResponse><result xsi:type=\"xsd:string\">', "",  $decodedXml);
        $decodedXml = str_replace("</result></ns1:RecibeMasterResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>", "", $decodedXml);
        $decodedXml = substr($decodedXml, 1, 1);


        // Navegar en el XML para encontrar el nodo <result>
        $result = $decodedXml;

        // Extraer el valor de 'result'
        //$result = (string)$responseData->result;
        $recibeMaster->fecha_envio = date('Y-m-d H:i:s');
        if ($result == '0') {

            $recibeMaster->respuesta_envio = 'OK';
        } else {
            $recibeMaster->respuesta_envio = 'ERROR';
        }
        $recibeMaster->save();
        return redirect()->route('admin.recibe-masters.index');
    }

    public function edit(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recibeMasters.edit', compact('recibeMaster'));
    }

    public function update(UpdateRecibeMasterRequest $request, RecibeMaster $recibeMaster)
    {
        $recibeMaster->update($request->all());
        $response = $this->RecibeMasterRequest($recibeMaster);
        $xml = simplexml_load_string($response);

        // Especificar el namespace (en este caso, el de ns1)
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['SOAP-ENV'])->Body;
        $responseData = $body->children($namespaces['ns1'])->RecibeMasterResponse;

        // Extraer el valor de 'result'
        $result = (string)$responseData->result;
        $recibeMaster->fecha_envio = date('Y-m-d H:i:s');
        if ($result == '0') {

            $recibeMaster->respuesta_envio = 'OK';
        } else {
            $recibeMaster->respuesta_envio = 'ERROR';
        }
        $recibeMaster->save();
        return redirect()->route('admin.recibe-masters.index');
    }

    public function show(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recibeMasters.show', compact('recibeMaster'));
    }

    public function destroy(RecibeMaster $recibeMaster)
    {
        abort_if(Gate::denies('recibe_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recibeMaster->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecibeMasterRequest $request)
    {
        $recibeMasters = RecibeMaster::find(request('ids'));

        foreach ($recibeMasters as $recibeMaster) {
            $recibeMaster->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function ObtieneLotes(Request $request)
    {

        $actualiceLotes = 0;
        try {
            foreach ($this->especies as $especie) {

                $idEspecie = (string)$especie["IdEspecie"][0];
                $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Lotes>
                                <idExportador>' . $this->IdExportador . '</idExportador>
                                <idEspecie>' . $idEspecie . '</idEspecie>
                                <Usuario>' . $this->usuario . '</Usuario>
                            </urn:Lotes>
                        </soapenv:Body>
                    </soapenv:Envelope>';
                $options = [
                    'trace' => true, // Para rastrear la solicitud/respuesta
                    'exceptions' => true, // Lanzar excepciones en caso de error
                    'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
                ];

                $client = new \SoapClient($this->url, $options);



                $response = $client->__doRequest($xmlBody, $this->url, 'Lotes', SOAP_1_1);
                // 1. Decodificar entidades HTML
                $decodedXml = html_entity_decode($response);
                $doc = new DOMDocument();
                $doc->loadXML($decodedXml);
                $decodedXml = html_entity_decode($doc->saveXML($doc->getElementsByTagName('Xml')->item(0)->firstChild));
                //dd($decodedXml);
                // 2. Cargar el contenido del XML en SimpleXMLElement
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($decodedXml, 'SimpleXMLElement', LIBXML_NOCDATA);

                if (!$xml) {
                    // Mostrar errores si el XML no es válido
                    foreach (libxml_get_errors() as $error) {
                        echo "Error: " . $error->message . "\n";
                    }
                    libxml_clear_errors();
                    throw new \Exception("Error al procesar el XML.");
                }
                // 4. Iterar sobre los lotes y procesar datos
                $data = [];
                foreach ($xml->Lotes->Lote as $lote) {
                    $data[] = [
                        'IdTemporada' => (string) $lote->IdTemporada,
                        'IdCentral' => (string) $lote->IdCentral,
                        'nroLote' => (string) $lote->nroLote,
                        'IdPartida' => (string) $lote->IdPartida,
                    ];


                    $recibeMaster = RecibeMaster::where('lote', (string) $lote->nroLote)
                        ->where('partida', (string) $lote->IdPartida)
                        ->where('cod_central', (string) $lote->IdCentral)
                        ->first();
                    if ($recibeMaster) {
                        $recibeMaster->update([
                            'lote' => (string) $lote->nroLote,
                            'partida' => (string) $lote->IdPartida,
                            'cod_central' => (string) $lote->IdCentral,
                            'respuesta_envio' => 'LOTE OK',
                        ]);
                        $actualiceLotes = 1;
                    }
                }
            }
            if ($actualiceLotes == 1) {
                return response()->json(['message' => 'Lotes Actualizados', 'status' => 'OK']);
            } else {
                return response()->json(['message' => 'No se encontraron lotes', 'status' => 'KO']);
            }
            // Log de solicitud y respuesta
            Log::info('SOAP Request:', ['request' => $client->__getLastRequest()]);
            Log::info('SOAP Response Raw:', ['response_raw' => $client->__getLastResponse()]);
        } catch (\SoapFault $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener lotes'], 404);
        }
    }
    public function CapturaLotes(Request $request)
    {
        $recibeMaster = RecibeMaster::where('id', $request->id)->first();
        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:CapturaLotes>
                                <idExportador>' . $recibeMaster->idExportador . '</idExportador>
                                <idEspecie>' . $recibeMaster->idEspecie . '</idEspecie>
                                <idCentral>' . $this->codCentral . '</idCentral>
                                <Lote>' . $recibeMaster->Lote . '</Lote>
                                <Partida>' . $recibeMaster->Partida . '</Partida>
                                <Usuario>' . $this->usuario . '</Usuario>
                            </urn:CapturaLotes>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];

        $client = new \SoapClient($this->url, $options);
        //$soapService = new PtiService();

        try {
            $response = $client->__doRequest($xmlBody, $this->url, 'AccesoSis', SOAP_1_1);
            Log::info('SOAP Response:', ['response' => $response]);



            $parsedXml = $this->parseResponseAccesoSis($response);
            //$this->LotesRequest(2, 4);
            // dd(response()->json($parsedXml));
            return $parsedXml;
            // Log de solicitud y respuesta
            Log::info('SOAP Request:', ['request' => $client->__getLastRequest()]);
            Log::info('SOAP Response Raw:', ['response_raw' => $client->__getLastResponse()]);
        } catch (\SoapFault $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
        }
    }
    //Funciones de interacción con web service de PTI
    public function AccesoSis()
    {


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:AccesoSis>
                                <Usuario>' . $this->usuario . '</Usuario>
                                <Contrasena>' . $this->pass . '</Contrasena>
                            </urn:AccesoSis>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];

        $client = new \SoapClient($this->url, $options);
        //$soapService = new PtiService();

        try {
            $response = $client->__doRequest($xmlBody, $this->url, 'AccesoSis', SOAP_1_1);
            Log::info('SOAP Response:', ['response' => $response]);



            $parsedXml = $this->parseResponseAccesoSis($response);
            //$this->LotesRequest(2, 4);
            // dd(response()->json($parsedXml));
            return $parsedXml;
            // Log de solicitud y respuesta
            Log::info('SOAP Request:', ['request' => $client->__getLastRequest()]);
            Log::info('SOAP Response Raw:', ['response_raw' => $client->__getLastResponse()]);
        } catch (\SoapFault $e) {
            Log::error('SOAP Error: ' . $e->getMessage());
        }
    }
    public function parseResponseAccesoSis($response)
    {
        $decodedXml = html_entity_decode($response);
        $doc = new DOMDocument();
        $resp = new DOMDocument();
        // Crear un objeto SimpleXMLElement a partir del XML de respuesta
        $respuesta = $doc->loadXML($decodedXml);

        $decodedXml = html_entity_decode($doc->saveXML($doc->getElementsByTagName('AccesoSisResponse')->item(0)->firstChild->firstChild));
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);
        //$namespace = $data->getNamespaces(true);
        $data = simplexml_load_string($decodedXml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xmlString = simplexml_load_string($data->saveXML(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $usuario = array(
            'IdExportador' => $xmlString->Usuario->IdExportador,
            'NombreUsuario' => $xmlString->Usuario->NombreUsuario
        );


        $especies = array();

        foreach ($xmlString->Especies->Especie as $especie) {
            $especies[] = array(
                'IdEspecie' => $especie->IdEspecie,
                'Nombre' => $especie->Nombre
            );
        }

        $parsedXml = array(
            'Usuario' => $usuario,
            'Especies' => $especies
        );
        return $parsedXml;
    }

    public function CentralRequest(int $idExportador)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Central>
                                <IdExportador>' . $idExportador . '</IdExportador>
                            </urn:Central>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'Central', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function ColorRequest(int $idExportador, int $idEspecie)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Color>
                                <IdExportador>' . $idExportador . '</IdExportador>
                                <IdEspecie>' . $idEspecie . '</IdEspecie>
                            </urn:Color>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'Color', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function DefectosRequest(int $idExportador, int $idEspecie)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Defectos>
                                <IdExportador>' . $idExportador . '</IdExportador>
                                <IdEspecie>' . $idEspecie . '</IdEspecie>
                            </urn:Defectos>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'Defectos', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function FirmezaRequest(int $idExportador, int $idEspecie)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Firmeza>
                                <IdExportador>' . $idExportador . '</IdExportador>
                                <IdEspecie>' . $idEspecie . '</IdEspecie>
                            </urn:Firmeza>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'Firmeza', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function ColorFRequest(int $idExportador, int $idEspecie)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:ColorF>
                                <IdExportador>' . $idExportador . '</IdExportador>
                                <IdEspecie>' . $idEspecie . '</IdEspecie>
                            </urn:ColorF>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'ColorF', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function CalibreRequest(int $idExportador, int $idEspecie)
    {
        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);


        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                        <soapenv:Body>
                            <urn:Calibre>
                                <IdExportador>' . $idExportador . '</IdExportador>
                                <IdEspecie>' . $idEspecie . '</IdEspecie>
                            </urn:Calibre>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $response = $client->__doRequest($xmlBody, $this->url, 'Calibre', SOAP_1_1);
        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);

        return response()->json($decodedXml);
    }
    public function RecibeMasterRequest($recibeMaster)
    {

        $options = [
            'trace' => true, // Para rastrear la solicitud/respuesta
            'exceptions' => true, // Lanzar excepciones en caso de error
            'cache_wsdl' => WSDL_CACHE_NONE, // Evitar caché de WSDL durante desarrollo
        ];
        $client = new \SoapClient($this->url, $options);
        $xmlBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:PtiChile">
                    <soapenv:Header/>
                    <soapenv:Body>
                        <urn:RecibeMaster>
                            <Especie>' . $recibeMaster->especie . '</Especie>
                            <Exportador>' . $recibeMaster->exportador . '</Exportador>
                            <Partida>' . $recibeMaster->partida . '</Partida>
                            <Estado>' . $recibeMaster->estado . '</Estado>
                            <CodCentral>' . $this->codCentral . '</CodCentral>
                            <CodProductor>' . $recibeMaster->cod_productor . '</CodProductor>
                            <NroGuiaDespacho>' . $recibeMaster->nro_guia_despacho . '</NroGuiaDespacho>
                            <FechaRecepcion>' . date('d/m/Y', strtotime($recibeMaster->fecha_recepcion)) . '</FechaRecepcion>
                            <FechaCosecha>' . date('d/m/Y', strtotime($recibeMaster->fecha_cosecha)) .  '</FechaCosecha>
                            <CodVariedad>' . $recibeMaster->cod_variedad . '</CodVariedad>
                            <EstibaCamion>0</EstibaCamion>
                            <EsponjasCloradas>0</EsponjasCloradas>
                            <NroBandeja>0</NroBandeja>
                            <HoraLlegada>' . date('H:i', strtotime($recibeMaster->hora_llegada)) . '</HoraLlegada>
                            <KiloMuestra>0</KiloMuestra>
                            <KiloNeto>' . $recibeMaster->kilo_neto . '</KiloNeto>
                            <TempIngreso>0</TempIngreso>
                            <TempSalida>0</TempSalida>
                            <Lote>' . $recibeMaster->lote . '</Lote>
                            <Huerto>' . $recibeMaster->huerto . '</Huerto>
                            <Hidro>' . $recibeMaster->hidro . '</Hidro>
                        </urn:RecibeMaster>
                    </soapenv:Body>
                    </soapenv:Envelope>';
        Log::info('XML Request:', ['xml' => $xmlBody]);
        $response = $client->__doRequest($xmlBody, $this->url, 'RecibeMaster', SOAP_1_1);

        Log::info('SOAP Response:', ['response' => $response]);
        $decodedXml = html_entity_decode($response);
        Log::info('Decoded XML Response:', ['decoded_xml' => $decodedXml]);
        return json_encode($response);
    }

    /**
     * Función para obtener registro desde fx
     */
    public function ObtieneDatosRecepcion(Request $request)
    {
        //abort_if(Gate::denies('datos_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        // numero_g_recepcion --lote
        // ,fecha_g_recepcion --FechaRecepcion
        // ,Hora_g_Recepcion --HoraLlegada
        // ,fecha_cosecha --FechaCosecha
        // ,c_productor --CodProductor
        // ,c_especie --Especie
        // ,c_variedad --CodVariedad
        // ,peso_neto --KILO NETO
        // ,numero_documento_recepcion --NroGuiaDespacho
        // ,NS_Productor --Huerto
        // ,N_tratamiento --Hidro
        //   --Exportador
        //   --Partida
        //   --Estado
        //   --CodCentral
        //   --TempIngreso No esta en FX
        //   --TempSalida No esta en FX
        //   --EstibaCamion No esta en FX
        //   --EsponjasCloradas No esta en FX
        //   --NroBandeja No esta en FX
        //   --KiloMuestra No esta en FX
        //dd($request);
        $datos = DB::connection("sqlsrv")->table('dbo.V_PKG_Recepcion_FG')
            ->select(
                'numero_g_recepcion',
                'fecha_g_recepcion',
                'Hora_g_Recepcion',
                'fecha_cosecha',
                'c_productor',
                'c_especie',
                'c_variedad',
                'id_variedad',
                DB::RAW('SUM(peso_neto) as peso_neto'),
                'numero_documento_recepcion',
                'NS_Productor',
                'N_tratamiento',
                'n_especie',
                'n_variedad'
            )
            ->where('numero_g_recepcion', '=', $request->numero_g_recepcion)
            ->groupBy(
                'numero_g_recepcion',
                'fecha_g_recepcion',
                'Hora_g_Recepcion',
                'fecha_cosecha',
                'c_productor',
                'c_especie',
                'c_variedad',
                'numero_documento_recepcion',
                'NS_Productor',
                'N_tratamiento',
                'n_especie',
                'n_variedad',
                'id_variedad'
            )
            ->first();
        //dd($datos);
        $recibeMaster = new RecibeMaster();
        $recibeMaster->numero_g_recepcion = $datos->numero_g_recepcion;
        $recibeMaster->fecha_g_recepcion = $datos->fecha_g_recepcion; //$datos->fecha_g_recepcion;
        $recibeMaster->Hora_g_Recepcion = $datos->Hora_g_Recepcion;
        $recibeMaster->fecha_cosecha = $datos->fecha_cosecha;
        $recibeMaster->c_productor = $datos->c_productor;
        $recibeMaster->c_especie = $datos->c_especie;
        $recibeMaster->c_variedad = $datos->id_variedad;
        $recibeMaster->peso_neto = $datos->peso_neto;
        $recibeMaster->numero_documento_recepcion = $datos->numero_documento_recepcion;
        $recibeMaster->NS_Productor = $datos->NS_Productor;
        $recibeMaster->N_tratamiento = $datos->N_tratamiento;
        $recibeMaster->cod_central = $this->codCentral;


        //$recibeMaster->save();
        //vamos a AccesoSis para homologar los datos

        // Acceder a datos del usuario

        $especieInterna = $datos->n_especie;


        // Acceder a las especies

        switch ($especieInterna) {
            case 'Apples':
                $recibeMaster->n_especie = 'MANZANAS';
                break;
            case 'Pears':
                $recibeMaster->n_especie = 'PERAS';
                break;
            case 'Grapes':
                $recibeMaster->n_especie = 'UVAS';
                break;
            case 'Nectarines':
                $recibeMaster->n_especie = 'NECTARIN';
                break;
            case 'Peaches':
                $recibeMaster->n_especie = 'DURAZNO';
                break;
            case 'Plums':
                $recibeMaster->n_especie = 'CIRUELAS';
                break;
            case 'Cherries':
                $recibeMaster->n_especie = 'CEREZAS';
                break;
            case 'Membrillos':
                $recibeMaster->n_especie = 'MEMBRILLOS';
                break;
            case 'Kiwis':
                $recibeMaster->n_especie = 'KIWIS';
                break;
            case 'Apricot':
                $recibeMaster->n_especie = 'DAMASCOS';
                break;
            case 'Mandarinas':
                $recibeMaster->n_especie = 'MANDARINAS';
                break;
            case 'Lemons':
                $recibeMaster->n_especie = 'LIMONES';
                break;
            case 'Orange':
                $recibeMaster->n_especie = 'NARANJAS';
                break;
            case 'Granadas':
                $recibeMaster->n_especie = 'GRANADAS';
                break;
            case 'Caquis':
                $recibeMaster->n_especie = 'CAQUIS';
                break;
            case 'Arandanos':
                $recibeMaster->n_especie = 'ARANDANOS';
                break;
            case 'Clementinas':
                $recibeMaster->n_especie = 'CLEMENTINAS';
                break;
            case 'Paltas':
                $recibeMaster->n_especie = 'PALTAS';
                break;
            default:
                $recibeMaster->n_especie = $especieInterna;
                break;
        }


        $accesoSis = $this->AccesoSis();

        $data = $accesoSis;

        $especies = $data['Especies'];

        $idExportador = (string)$data['Usuario']['IdExportador'];
        $recibeMaster->exportador = $idExportador;
        $nombreUsuario = (string)$data['Usuario']['NombreUsuario'];
        foreach ($especies as $especie) {
            $nombreJson = strtoupper((string)$especie['Nombre']); // Convertir a mayúsculas para facilitar el match

            if ($nombreJson == $recibeMaster->n_especie) {

                $codigoEspecie = (string)$especie['IdEspecie'];
                $recibeMaster->n_especie = $codigoEspecie;
            } else {
                // $recibeMaster->n_especie = $especie['IdEspecie']['0'];
            }
        }

        return response()->json($recibeMaster, 200);
    }
}
