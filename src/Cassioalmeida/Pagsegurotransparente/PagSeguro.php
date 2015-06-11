<?php

namespace Cassioalmeida\Pagsegurotransparente;

class PagSeguro
{

    /**
     * PagSeguro Data
     *
     * @var PagSeguroData
     */
    private $pagSeguroData;

    /**
     * Constructor
     *
     * @param bool $sandbox
     * @param $sandboxData
     * @param $productionData
     */
    public function __construct($sandbox = true, $sandboxData, $productionData)
    {
        $this->sandbox          = $sandbox;
        $this->pagSeguroData    = new PagSeguroData($sandbox, $sandboxData, $productionData);
    }

    /**
     * Return if PagSeguro is on SandBox;
     * @return bool
     */
    public function isSandbox()
    {
        if($this->sandbox){
            return true;
        }
        return false;
    }

    /**
     * Get PagSeguro data
     *
     * @return PagSeguroData
     */
    public function getPagSeguroData()
    {
        return $this->pagSeguroData;
    }

    /**
     * Print Session ID
     *
     * @throws Exception
     */
    public function printSessionId()
    {
        $httpConnection = new HttpConnection();
        $httpConnection->post($this->pagSeguroData->getSessionURL(), $this->pagSeguroData->getCredentials());
        if ($httpConnection->getStatus() === 200) {
            $data = $httpConnection->getResponse();
            $sessionId = $this->parseSessionIdFromXml($data);
            echo $sessionId;
        } else {
            throw new Exception("API Request Error: " . $httpConnection->getStatus());
        }
    }

    /**
     * Get Session ID
     *
     * @return mixed
     * @throws Exception
     */
    public function getSessionId()
    {
        $httpConnection = new HttpConnection();
        $httpConnection->post($this->pagSeguroData->getSessionURL(), $this->pagSeguroData->getCredentials());
        if ($httpConnection->getStatus() === 200) {
            $data = $httpConnection->getResponse();
            $sessionId = $this->parseSessionIdFromXml($data);
            return $sessionId;
        } else {
            throw new Exception("API Request Error: " . $httpConnection->getStatus());
        }
    }

    /**
     * Do payment
     *
     * @param $params
     * @return null|string
     * @throws Exception
     */
    public function doPayment($params)
    {
        $params += $this->pagSeguroData->getCredentials();
        $params['paymentMode'] = 'default';
        $params['currency'] = 'BRL';
        $httpConnection = new HttpConnection();
        $httpConnection->post($this->pagSeguroData->getTransactionsURL(), $params);
        $xmlArray = $this->paymentResultXml($httpConnection->getResponse());
        header("HTTP/1.1 ".$httpConnection->getStatus());
        return $xmlArray;
    }

    /**
     * Parses Session ID from XML
     * @param $data
     * @return mixed
     * @throws Exception
     * @throws \Exception
     */
    private function parseSessionIdFromXml($data)
    {
        $xmlParser = new XmlParser($data);
        if ($xml = $xmlParser->getResult("session")) {
            return $xml['id'];
        } else {
            throw new Exception("[$data] is not an XML");
        }
    }


    /**
     * Get payment result.
     * @param $data
     * @return null|string
     * @throws Exception
     * @throws \Exception
     */
    private function paymentResultXml($data)
    {
        $xmlParser = new XmlParser($data);
        if ($xml = $xmlParser->getResult()) {
            return $xml;
        } else {
            throw new Exception("[$data] is not an XML");
        }
    }

    /**
     * Payment order consult.
     * @param $codeNotification
     * @return null|string
     * @throws Exception
     */
    public function paymentOrderConsult($codeNotification)
    {
        $params = $this->pagSeguroData->getCredentials();
        $httpConnection = new HttpConnection();
        $httpConnection->get($this->pagSeguroData->getNotificationsURL().$codeNotification, $params);
        $xmlArray = $this->paymentResultXml($httpConnection->getResponse());
        header("HTTP/1.1 ".$httpConnection->getStatus());
        return $xmlArray;
    }

} 