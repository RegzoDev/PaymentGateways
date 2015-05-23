<?php

namespace core\interfaces;

interface GatewayInterface {
    public function setPaymentCredentials(array $credentialsArray = []);
}