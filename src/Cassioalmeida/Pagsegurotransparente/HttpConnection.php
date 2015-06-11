<?php

namespace Cassioalmeida\Pagsegurotransparente;

class HttpConnection
{

    /**
     * Status Http Connection
     *
     * @var
     */
    private $status;

    /**
     * Response
     *
     * @var
     */
    private $response;

    /**
     * Constructor
     *
     * @throws Exception
     */
    public function __construct() {
        if (!function_exists('curl_init')) {
            throw new Exception('CURL library is required.');
        }
    }

    /**
     * Get connection Status
     *
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set connection Status
     *
     * @param $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Get response
     *
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Set response
     *
     * @param $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }

    /**
     * Make a POST Request
     *
     * @param $url
     * @param array $data
     * @param null $timeout
     * @param null $charset
     * @return bool
     * @throws Exception
     */
    public function post($url, array $data = null, $timeout = null, $charset = null) {
        return $this->curlConnection('POST', $url, $data, $timeout, $charset);
    }

    /**
     * Make a GET resquest
     *
     * @param $url
     * @param array $data
     * @param null $timeout
     * @param null $charset
     * @return bool
     * @throws Exception
     */
    public function get($url, array $data = null, $timeout = null, $charset = null) {
        return $this->curlConnection('GET', $url, $data, $timeout, $charset);
    }

    /**
     * Create a CURL Connection
     *
     * @param $method
     * @param $url
     * @param array $data
     * @param int $timeout
     * @param string $charset
     * @return bool
     * @throws Exception
     */
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
        }
        return true;
    }
} 