<?php

namespace core;

class Config {
    private $configDir = './config/';
    private $configExt = '.php';

    private $configArray = [];


    public function get($varName, $configFile = 'core') {
        if(!array_key_exists($configFile, $this->configArray)) {
            $this->configArray[$configFile] = include($this->configDir . $configFile . $this->configExt);
        }
        if(!array_key_exists($varName, $this->configArray[$configFile])) {
            throw new \Exception($varName. ' config variable is not defined');
        }
        return $this->configArray[$configFile][$varName];
    }
}