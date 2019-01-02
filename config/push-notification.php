<?php

return array(

    'ios' => [
        'environment' =>'development',
        'certificate' => base_path().'/storage/apns/pushcert_prod.pem',
//        'certificate' => base_path().'/storage/apns/pushcert_dev.pem',
        'passPhrase'  =>'321',
        'service'     =>'apns'
    ],
    'android' => [
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    ]

);
