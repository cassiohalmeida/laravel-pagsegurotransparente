<?php

namespace Cassioalmeida\Pagsegurotransparente;


class PagSeguro {

    private $pagSeguroData;

    public function __construct($sandbox = true, $sandboxData, $productionData) {
        $this->sandbox = $sandbox;
        $this->pagSeguroData = new PagSeguroData($sandbox, $sandboxData, $productionData);
    }

    /**
     * Return if PagSeguro is on SandBox;
     * @return bool
     */
    public function isSandbox(){
        if($this->sandbox){
            return true;
        }
        return false;
    }

    public function getPagSeguroData(){
        return $this->pagSeguroData;
    }

    public function printSessionId() {

        // Creating a http connection (CURL abstraction)
        $httpConnection = new HttpConnection();

        // Request to PagSeguro Session API using Credentials
        $httpConnection->post($this->pagSeguroData->getSessionURL(), $this->pagSeguroData->getCredentials());

        // Request OK getting the result
        if ($httpConnection->getStatus() === 200) {

            $data = $httpConnection->getResponse();

            $sessionId = $this->parseSessionIdFromXml($data);

            echo $sessionId;

        } else {

            throw new Exception("API Request Error: ".$httpConnection->getStatus());

        }

    }

    public function getSessionId() {

        // Creating a http connection (CURL abstraction)
        $httpConnection = new HttpConnection();

        // Request to PagSeguro Session API using Credentials
        $httpConnection->post($this->pagSeguroData->getSessionURL(), $this->pagSeguroData->getCredentials());

        // Request OK getting the result
        if ($httpConnection->getStatus() === 200) {

            $data = $httpConnection->getResponse();

            $sessionId = $this->parseSessionIdFromXml($data);

            return $sessionId;

        } else {

            throw new Exception("API Request Error: ".$httpConnection->getStatus());

        }

    }

    /**
     * Do Payment;
     * @param $params
     * @throws Exception
     */
    public function doPayment($params) {

        // Adding parameters

        $params += $this->pagSeguroData->getCredentials(); // add credentials
        $params['paymentMode'] = 'default'; // paymentMode
        $params['currency'] = 'BRL'; // Currency (only BRL)
        //$params['reference'] = rand(0, 9999); // Setting the Application Order to Reference on PagSeguro

        // treat parameters here!
        $httpConnection = new HttpConnection();
        $httpConnection->post($this->pagSeguroData->getTransactionsURL(), $params);

        // Get Xml From response body
        $xmlArray = $this->paymentResultXml($httpConnection->getResponse());

        // Setting http status and show json as result
        //http_response_code($httpConnection->getStatus());
        header("HTTP/1.1 ".$httpConnection->getStatus());

        return $xmlArray;

    }

    private function parseSessionIdFromXml($data) {

        // Creating an xml parser
        $xmlParser = new XmlParser($data);

        // Verifying if is an XML
        if ($xml = $xmlParser->getResult("session")) {

            // Retrieving the id from "session node"
            return $xml['id'];

        } else {
            throw new Exception("[$data] is not an XML");
        }

    }


    private function paymentResultXml($data) {

        // Creating an xml parser
        $xmlParser = new XmlParser($data);

        // Verifying if is an XML
        if ($xml = $xmlParser->getResult()) {
            return $xml;
        } else {
            throw new Exception("[$data] is not an XML");
        }

    }

    public function paymentOrderConsult($codeNotification){

        $params = $this->pagSeguroData->getCredentials(); // add credentials
        // treat parameters here!
        $httpConnection = new HttpConnection();
        $httpConnection->get($this->pagSeguroData->getNotificationsURL().$codeNotification, $params);

        // Get Xml From response body
        $xmlArray = $this->paymentResultXml($httpConnection->getResponse());

        // Setting http status and show json as result
        //http_response_code($httpConnection->getStatus());
        header("HTTP/1.1 ".$httpConnection->getStatus());

        return $xmlArray;
    }

} 