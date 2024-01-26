<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => env('PAYPAL_SANDBOX_API_USERNAME', 'aman.seller_api1.gmail.com'),
        'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', '4RWUPXG9DAFLXHSG'),
        'secret'      => env('PAYPAL_SANDBOX_API_SECRET', 'AVfSQ5PLCwiNa3zy.rOy.Na-EbloAtKdhuYTdgU2nn9r9SHcJO16Yb8V'),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
        'app_id'      => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => env('PAYPAL_LIVE_API_USERNAME', 'laurearambeau_api1.gmail.com'),
        'password'    => env('PAYPAL_LIVE_API_PASSWORD', '44XQEH7WQ9KF7EVD'),
        'secret'      => env('PAYPAL_LIVE_API_SECRET', 'AYQXzx37eXj5pJnyujlI8iSLjTfWAk-EFP7eKOS8LPkRVDT2JlsT5E01'),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id'      => '', // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'billing_type'   => 'MerchantInitiatedBilling',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => true, // Validate SSL when creating api client.
    'invoice_prefix' => strtolower(config('app.name'))
];