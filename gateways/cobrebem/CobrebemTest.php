<?php

class Cobrebem {

    private $testMode = false;
    private $testHost = 'https://teste.aprovafacil.com/';
    private $host = 'https://aprovafacil.com/';

    public function __construct($testMode = false) {
        $this->testMode = $testMode;
    }
}