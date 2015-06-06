<?php

namespace RezgoPayment;

use core\interfaces\GatewayInterface;
use core\BaseGateway;

class Securepay extends BaseGateway implements  GatewayInterface{

    protected $configName = 'securepay';

    protected $timeout = 60;
    protected $merchantId = 'ABC0001';
    protected $merchantPassword = 'abc123';

    const TRANSACTION_TYPE_PAYMENT = 0;
    const TRANSACTION_TYPE_AUTHORIZE = 10;
    const TRANSACTION_TYPE_AUTHORIZE_FINISH = 11;
    const TRANSACTION_TYPE_REFUND = 4;
    const TRANSACTION_TYPE_VOID = 6;


    public function setPaymentCredentials(array $credentialsArray = array()) {
        var_dump($this->apiClient);
    }

    /**
     * @param int $type one of TRANSACTION_TYPE_* constant
     * @param array $data
     * @return mixed
     */
    protected function generateXml($type, array $data = []) {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><SecurePayMessage/>');

        $messageInfo = $xml->addChild('MessageInfo');
        $messageInfo->addChild('messageID', $this->generateMessageId());
        $messageInfo->addChild('messageTimestamp', $this->getMessageTimestamp());
        $messageInfo->addChild('timeoutValue', $this->timeout);
        $messageInfo->addChild('apiVersion', 'xml-4.2');

        $merchantInfo = $xml->addChild('MerchantInfo');
        $merchantInfo->addChild('merchantID', $this->merchantId);
        $merchantInfo->addChild('password', $this->merchantPassword);

        $xml->addChild('RequestType', 'Payment');

        $payment = $xml->addChild('Payment');

        $txnList = $payment->addChild('TxnList');
        $txnList->addAttribute('count', 1);

        $txn = $txnList->addChild('Txn');
        $txn->addAttribute('ID', 1);
        $txn->addChild('txnType', $type);
        $txn->addChild('txnSource', 23);

        if (!isset($data['payment_exp']) && isset($data['payment_exp_month']) && isset($data['payment_exp_year'])) {
            $year = $data['payment_exp_year'];
            // In case of 4 digits year use only last 2
            if (strlen($year) == 4) {
                $year = substr($year, 2, 2);
            }
            $data['payment_exp'] = $data['payment_exp_month'] . '/'. $year;
        }

        if (isset($data['amount'])) $txn->addChild('amount', intval($data['amount'] * 100));
        if (isset($data['currency'])) $txn->addChild('currency', $data['currency']);
        if (isset($data['order_id'])) $txn->addChild('purchaseOrderNo', $data['order_id']);
        if (isset($data['transaction_id'])) {
            if ($type == self::TRANSACTION_TYPE_AUTHORIZE_FINISH) {
                $txn->addChild('preauthID', $data['transaction_id']);
            } else {
                $txn->addChild('txnID', $data['transaction_id']);
            }
        }

        if (isset($data['payment_number']) || isset($data['payment_exp']) || isset($data['payment_cvv'])) {
            $cci = $txn->addChild('CreditCardInfo');
            if (isset($data['payment_number'])) $cci->addChild('cardNumber', $data['payment_number']);
            if (isset($data['payment_exp'])) $cci->addChild('expiryDate', $data['payment_exp']);
            if (isset($data['payment_cvv'])) $cci->addChild('cvv', $data['payment_cvv']);
        }

        return $xml->asXML();
    }

    /**
     * Returns date in format specified by SecurePay
     * @return string
     */
    protected function getMessageTimestamp() {
        $date = new \DateTime();

        $ret = $date->format('YmdHisu');
        $offset = $date->getOffset();

        if ($offset > 0) {
            $offset /= 60;
            $ret .= '+'.$offset;
        } else {
            $offset /= 60;
            $ret .= '-'.$offset;
        }

        return $ret;
    }

    /**
     * Generate the message ID (30 characters)
     * @return string
     */
    protected function generateMessageId() {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%02x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xff)
        );
    }

    protected function processResponse($type, $response) {
        $xml = simplexml_load_string($response);

        // Check status first
        $statusCode = (string)$xml->Status->statusCode;
        if ($statusCode != '000') {
            throw new BasicException((string)$xml->Status->statusDescription);
        }

        $messageId = (string)$xml->MessageInfo->messageID;

        $approved = (string)$xml->Payment->TxnList->Txn->approved;
        $responseCode = (string)$xml->Payment->TxnList->Txn->responseCode;
        $responseText = (string)$xml->Payment->TxnList->Txn->responseText;
        if ($approved == 'No') {
            throw new PaymentException($responseText);
        }

        $transactionId = (string)$xml->Payment->TxnList->Txn->txnID;

        // According to Secure Pay Bank and SecurePay Response Codes
        if (!in_array($responseCode, ['00', '11', '77'])) {
            throw new PaymentException($responseText);
        }

        return [
            'message_id' => $messageId,
            'transaction_id' => $transactionId,
            'raw_response' => (string)$response,
        ];
    }

    protected function sendRequest($xml) {
        return $this->apiClient->post('',array(
            'content-type' => 'application/xml',
            'body' => $xml,
            'timeout'         => $this->timeout,
            'connect_timeout' => 1.5
        ));
    }

    protected function process($type, array $data = []) {
        $xmlStr = $this->generateXml($type, $data);

        $response = $this->sendRequest($xmlStr);

        return $this->processResponse($type, $response->getBody());
    }

    public function charge(array $data = []) {
        return $this->process(self::TRANSACTION_TYPE_PAYMENT, $data);
    }

    public function refund(array $data = []) {
        return $this->process(self::TRANSACTION_TYPE_REFUND, $data);
    }

    public function authorize(array $data = []) {
        return $this->process(self::TRANSACTION_TYPE_AUTHORIZE, $data);
    }

    public function authorizeFinish(array $data = []) {
        return $this->process(self::TRANSACTION_TYPE_AUTHORIZE_FINISH, $data);
    }

    public function void(array $data = []) {
        // @todo For some reason not working :(
        return $this->process(self::TRANSACTION_TYPE_VOID, $data);
    }


}

class Exception extends \Exception {}
class BasicException extends Exception {}
class PaymentException extends Exception {};