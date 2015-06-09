<?php

return [
    'host' => 'https://aprovafacil.com/cgi-bin/APFW/',
    'testHost' => 'https://teste.aprovafacil.com/cgi-bin/APFW/',
    'approvalRequestUrl' => 'APC',
    'requestParameters' => [
        'approvalRequest' => [
            'mandatory' => [
                'NumeroDocumento' => 'string', // Company Order ID
                'ValorDocumento' => 'float', // amount
                'QuantidadeParcelas' => 'int',
                'NumeroCartao' => 'int', // string
                'MesValidade' => 'date', // card expiration month
                'AnoValidade' => 'date', // card expiration year
                'CodigoSeguranca' => 'int', // cvv/cvv2
            ],
            'optional' => [
                'NumeroDocumento' => 'int', // company order id
                'ValorEntrada' => 'float', // boarding fee
                'PreAutorizacao' => ['S', 'N'],
                'NomePortadorCartao' => 'string', // name written on card
                'Bandeira' => [ //Credit Card Banner
                    'VISA',
                    'MASTERCARD',
                    'DINERS',
                    'AMEX',
                    'HIPERCARD',
                    'JCB',
                    'SOROCRED',
                    'AURA'
                ],
            ]
        ]
    ]
];