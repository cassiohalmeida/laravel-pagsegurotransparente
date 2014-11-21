<?php
/**
 * Created by PhpStorm.
 * User: cassio
 * Date: 13/11/14
 * Time: 11:32
 */

namespace Cassioalmeida\Pagsegurotransparente;


class PagSeguroData {

    private $sandbox;

    private $sandboxData;

    private $productionData;

    public function __construct($sandbox = false, $sandboxData, $productionData) {
        $this->sandbox = (bool)$sandbox;
        $this->sandboxData = $sandboxData;
        $this->productionData = $productionData;
    }

    private function getEnviromentData($key) {
        if ($this->sandbox) {
            return $this->sandboxData[$key];
        } else {
            return $this->productionData[$key];
        }
    }

    public function getSessionURL() {
        return $this->getEnviromentData('sessionURL');
    }

    public function getTransactionsURL() {
        return $this->getEnviromentData('transactionsURL');
    }

    public function getNotificationsURL(){
        return $this->getEnviromentData('notificationURL');
    }

    public function getJavascriptURL() {
        return $this->getEnviromentData('javascriptURL');
    }

    public function getCredentials() {
        return $this->getEnviromentData('credentials');
    }

    public function isSandbox() {
        return (bool)$this->sandbox;
    }
} 