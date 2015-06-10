<?php

namespace core;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class BaseGateway {

    protected $testMode;
    protected $configName;
    protected $testHost;
    protected $mainHost;
    protected $requestParameters;
    protected $validator;

    protected $apiClient;

    protected $apiTimeout = 0;

    protected $logger;

    public function __construct($parameters) {
        if(!empty($parameters['testMode'])) {
            $this->testMode = $parameters['testMode'];
        }
        $this->config = new Config();

        $this->testHost = $this->config->get('testHost', $this->configName);
        $this->mainHost = $this->config->get('host', $this->configName);

        if($this->testMode) {
            $this->host = $this->testHost;
        } else {
            $this->host = $this->mainHost;
        }

        $this->setApiClient();
        $this->requestParameters = $this->config->get('requestParameters', $this->configName);

        $this->validator = new Validator();
        $this->logger = new Logger();
    }

    private function setApiClient() {
        $this->apiClient = new Client([
            'base_uri' => $this->host,
            'timeout' => $this->apiTimeout,
            'verify' => false
        ]);
    }

    protected function sendPostRequest($path, $parameters) {
        $response = $this->apiClient->post($path, [
            'form_params' => $parameters
        ]);
        $body = $response->getBody();
        $this->logger->logRequest($path, $parameters, $body);
        return $body;
    }

    protected function validateRequestParameters($parameters = [], $requestName = '') {
        if(count($parameters) === 0 || $requestName === '') {
            throw new \Exception('Invalid number of parameters provided');
        }

        if(!$this->requestParameters[$requestName]) {
            throw new \Exception('Configuration for request not found');
        }

        /**
         * Mandatory parameters validation
         */
        foreach($this->requestParameters[$requestName]['mandatory'] AS $parameterName => $parameterValidation) {
            if(!array_key_exists($parameterName, $parameters)) {
                throw new \Exception('Mandatory parameter not found: '. $parameterName);
            }
        }

        foreach($parameters AS $parameterName => $parameterValue) {
            $parameterValidation = '';
            if(array_key_exists($parameterName, $this->requestParameters[$requestName]['mandatory'])) {
                $parameterValidation = $this->requestParameters[$requestName]['mandatory'][$parameterName];
            }
            if(array_key_exists($parameterName, $this->requestParameters[$requestName]['optional'])) {
                $parameterValidation = $this->requestParameters[$requestName]['optional'][$parameterName];
            }
            if($parameterValidation) {
                $this->validator->validate($parameterValidation, $parameters[$parameterName], $parameterName);
            }
        }
    }
}