<?php

namespace App\Services;

use SoapClient;
use SoapFault;

class PtiService
{
    protected $client;

    public function __construct()
    {
        $wsdl = config('soap.wsdl');
        $options = config('soap.options');
        $this->client = new \SoapClient('http://sistema-test.ptichile.cl/ws/ws_pti_recepcion.php?wsdl', $options);
        //$this->client = new SoapClient($wsdl, $options);
    }

    public function call($operation, $params = [])
    {
        try {
            return $this->client->__soapCall($operation, [$params]);
        } catch (SoapFault $e) {
            throw new \Exception("SOAP Error: {$e->getMessage()}");
        }
    }
}
