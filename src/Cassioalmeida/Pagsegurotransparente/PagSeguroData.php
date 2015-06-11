<?php

namespace Cassioalmeida\Pagsegurotransparente;

class PagSeguroData
{

    /**
     * Sandnox
     *
     * @var bool
     */
    private $sandbox;

    /**
     * Sandbox data
     *
     * @var
     */
    private $sandboxData;

    /**
     * Production data
     *
     * @var
     */
    private $productionData;

    /**
     * Constructor
     *
     * @param bool $sandbox
     * @param $sandboxData
     * @param $productionData
     */
    public function __construct($sandbox = false, $sandboxData, $productionData)
    {
        $this->sandbox = (bool)$sandbox;
        $this->sandboxData = $sandboxData;
        $this->productionData = $productionData;
    }

    /**
     * Get enviroment.
     * @param $key
     * @return mixed
     */
    private function getEnviromentData($key) {
        if ($this->sandbox) {
            return $this->sandboxData[$key];
        }
        return $this->productionData[$key];
    }

    /**
     * Get session URL accord with the environment
     *
     * @return mixed
     */
    public function getSessionURL() {
        return $this->getEnviromentData('sessionURL');
    }

    /**
     * Get transaction data accord with the environment
     *
     * @return mixed
     */
    public function getTransactionsURL() {
        return $this->getEnviromentData('transactionsURL');
    }

    /**
     * Get notifications URL accord with the environment
     *
     * @return mixed
     */
    public function getNotificationsURL(){
        return $this->getEnviromentData('notificationURL');
    }

    /**
     * Get javascript URL accord with the environment
     *
     * @return mixed
     */
    public function getJavascriptURL() {
        return $this->getEnviromentData('javascriptURL');
    }

    /**
     * Get credential accord with the environment
     *
     * @return mixed
     */
    public function getCredentials() {
        return $this->getEnviromentData('credentials');
    }

    /**
     * Check if it is Sandbox enviroment
     *
     * @return bool
     */
    public function isSandbox() {
        return (bool)$this->sandbox;
    }
} 