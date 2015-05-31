<?php

namespace core;

use GuzzleHttp\Client;

class BaseGateway {

    protected $testMode;
    protected $configName;
    protected $testHost;
    protected $mainHost;

    protected $apiClient;

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
    }

    private function setApiClient() {
        $this->apiClient = new Client([
            'base_uri' => $this->host,
            'timeout' => 2,
        ]);
    }
}