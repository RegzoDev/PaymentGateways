<?php
namespace core;

class Logger {

    private $config;
    private $logsPath;
    private $logsEnabled;

    public function __construct() {
        $this->config = new Config();
        $this->logsPath = $this->config->get('logsPath');
        $this->logsEnabled = $this->config->get('enableLogs');
        $this->requestLogPath = $this->logsPath . DIRECTORY_SEPARATOR . $this->config->get('requestLogFilename');
    }

    public function logRequest($requestName, $requestParameters, $responseParameters) {
        if($this->logsEnabled) {
            $logString = date('H:i:s d.m.Y');
            $logString .= ' Request: ' . $requestName . ' sent with parameters:';
            $logString .= json_encode($requestParameters);
            $logString .= '; Response: ' . json_encode($responseParameters);
            $this->updateLogFile($this->requestLogPath, $logString);
        }
    }

    private function updateLogFile($path, $text) {
        $file = fopen($path, 'a+');
        if (fwrite($file, $text) === false) {
            throw new \Exception('Unable to write to log file '. $path);
        }
        fclose($file);
    }

}