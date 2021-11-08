<?php

return [

    /**
     * Twilio SID
     *
     */
    'twilioSID' => getenv('TWILIO_ACCOUNT_SID'),

    /**
     * Twilio Token
     *
     */
    'twilioToken' => getenv('TWILIO_AUTH_TOKEN'),

    /**
     * Twilio Number
     *
     */
    'twilioNumber' => getenv('TWILIO_NUMBER'),

    /**
     * Application url
     *
     */
    'appUrl' => getenv('APP_URL'),
];
