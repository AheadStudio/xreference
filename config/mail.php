<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mailgun", "mandrill", "ses",
    |            "sparkpost", "log", "array"
    |
    */

    'driver' => env('MAIL_DRIVER', 'mail'),
	    'host' => env('MAIL_HOST', 'localhost'),
	    'port' => env('MAIL_PORT', 25),
	    'from' => [
	        'address' => 'info@goodcode.ru',
	        'name' => 'Xreferences.info',
	    ],
	    'encryption' => env('MAIL_ENCRYPTION', 'null'),





];
