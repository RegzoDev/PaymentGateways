<?php

namespace core\interfaces;

interface GatewayInterface {

    public function setPaymentCredentials(array $credentialsArray = []);

    public function approvalRequest();

    public function captureRequest();

    public function cancelTransaction();

    public function refundCallback();

    public function refundResult();

}