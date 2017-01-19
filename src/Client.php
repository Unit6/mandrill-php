<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill;

/**
 * Client class for sending and receiving Mandrill headers.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Client implements ClientInterface
{
    const USER_AGENT = 'Unit6; Mandrill/1.0.0';

    const API_VERSION = '1.0';
    const API_ROOT = 'https://mandrillapp.com/api';

    protected $key;
    protected $debug = false;

    public function __construct(array $config = array())
    {
        $key = $this->getKeyFile();

        if ( ! is_null($key)) {
            $config['key'] = $key;
        }

        if ( ! isset($config['key'])) {
            throw new Exception\ClientInvalidKey('You must provide a Mandrill API key.');
        }

        $this->setKey($config['key']);
    }

    public function __call($name, $arguments)
    {
        if (preg_match('/(getService)([A-Z]{1}[\S]+)/', $name, $matches)) {
            list($name, $prefix, $key) = $matches;

            $service = __NAMESPACE__ . '\\Service\\' . $key;

            if (class_exists($service))
            {
                return new $service($this);
            }
        }

        throw new Exception\ModelUndefined('Undefined method in Client class.');
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function isDebugMode()
    {
        return ($this->debug === true);
    }

    public function getEndpoint($uri)
    {
        return self::API_ROOT . '/' . self::API_VERSION
            . '/' . $uri . '.json';
    }

    public function request($uri, array $params = array())
    {
        $params['key'] = $this->getKey();

        $json = json_encode($params);

        $url = $this->getEndpoint($uri);

        $headers = array(
            'Content-Type: application/json'
        );

        $start = microtime(true);

        $this->log('Request: ' . $url . ' ' . $json);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        if ($this->isDebugMode()) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        $time = microtime(true) - $start;

        if ($this->isDebugMode()) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }

        $this->log('Duration: ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Response: ' . $response);

        if (curl_error($ch)) {
            throw new Exception\ClientRequestFailed($url, $ch);
        }

        $result = json_decode($response, true);

        if (is_null($result)) {
            throw new Exception\ServerResponseInvalid($response);
        }

        if (floor($info['http_code'] / 100) >= 4) {
            throw $this->toException($result);
        }

        return $result;
    }

    public function getKeyFile()
    {
        $key = NULL;

        $paths = array('~/.mandrill.key', '/etc/mandrill.key');

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $key = trim(file_get_contents($path));
                break;
            }
        }

        return $key;
    }

    public function toException($result)
    {
        if ($result['status'] !== 'error' || ! $result['name']) {
            throw new Exception\ServerUnexpectedError(json_encode($result));
        }


        $exception = __NAMESPACE__ . '\\Exception\\ServerUnexpectedError';

        $name = $result['name'];

        if (isset(Exception::$map[$name])) {
            $e = Exception::$map[$name];
            $exception = __NAMESPACE__ . '\\Exception\\' . $e;
        }

        return new $exception($result['message'], $result['code']);
    }

    public function log($msg)
    {
        if ($this->isDebugMode()) {
            error_log($msg);
        }
    }
}