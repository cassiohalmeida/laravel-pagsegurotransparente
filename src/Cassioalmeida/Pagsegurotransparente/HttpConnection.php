<?php
/**
 * Created by PhpStorm.
 * User: cassio
 * Date: 13/11/14
 * Time: 11:31
 */

namespace Cassioalmeida\Pagsegurotransparente;


class HttpConnection {
    private $status;
    private $response;

    public function __construct() {
        if (!function_exists('curl_init')) {
            throw new Exception('CURL library is required.');
        }
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $this->response = $response;
    }

    public function post($url, array $data = null, $timeout = null, $charset = null) {
        return $this->curlConnection('POST', $url, $data, $timeout, $charset);
    }

    public function get($url, array $data = null, $timeout = null, $charset = null) {
        return $this->curlConnection('GET', $url, $data, $timeout, $charset);
    }

    private function curlConnection($method, $url, array $data = null, $timeout = 20, $charset = 'ISO-8859-1') {

        if (strtoupper($method) === 'POST') {
            $postFields = ($data ? http_build_query($data, '', '&') : "");
            $contentLength = "Content-length: " . strlen($postFields);
            $methodOptions = array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
            );
        } else {

            $url = $url.'?'.http_build_query($data, '', '&');

            $contentLength = null;
            $methodOptions = array(
                CURLOPT_HTTPGET => true
            );
        }

        $options = array(
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded; charset=" . $charset,
                $contentLength
            ),
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => $timeout
        );

        $options = ($options + $methodOptions);

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $resp = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_errno($curl);
        $errorMessage = curl_error($curl);

        curl_close($curl);

        $this->setStatus((int) $info['http_code']);
        $this->setResponse((String) $resp);



        if ($error) {
            throw new Exception("CURL can't connect: $errorMessage");

        } else {
            return true;
        }

    }
} 