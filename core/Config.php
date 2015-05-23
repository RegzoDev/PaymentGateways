<?php

namespace core;

class Config {
    private $configDir = './config/';
    private $configExt = '.php';


    public function get($varName, $configFile = 'core') {
        $configArray = include_once($this->configDir . $configFile . $this->configExt);

        if(array_key_exists($varName, $configArray)) {
            return $configArray[$varName];
        } else {
            throw new \Exception($varName. ' config variable is not defined');
        }
    }
}