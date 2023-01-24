<?php

/**
 * MyPay Payment Gateway WHMCS Module
 * @copyright Copyright (c) MyPay Digital Wallet
 * @author : MyPay Digital Wallet
 */

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/checkout.php";

// Build the constants
if (!defined("MYPAYGATEWAY_WHMCS_MODULE_NAME")) {
    define("MYPAYGATEWAY_WHMCS_MODULE_NAME", "mypaygateway");

    define("MYPAYGATEWAY_PAYMENT_GATEWAY_ROOT_DIR", dirname(__FILE__));
    define("MYPAYGATEWAY_HELPERS_DIR", MYPAYGATEWAY_PAYMENT_GATEWAY_ROOT_DIR . "/" . MYPAYGATEWAY_WHMCS_MODULE_NAME);

    define("MYPAYGATEWAY_LIVE_MODE", "live");
    define("MYPAYGATEWAY_TEST_MODE", "test");

    define('MYPAYGATEWAY_INITIATE_API', "/api/use-mypay-payments");

    define('MYPAYGATEWAY_TEST_ENDPOINT', "https://testapi.mypay.com.np"); # @TODO: This shall be updated later
    define('MYPAYGATEWAY_LIVE_ENDPOINT', "https://smartdigitalnepal.com");

    define('MYPAYGATEWAY_WHMCS_VIEWINOVICE_PAGE', "VIEWINVOICE");
}

// Fetch gateway configuration parameters if GatewayModule is activated
try {
    $mypaygateway_gateway_params = getGatewayVariables(MYPAYGATEWAY_WHMCS_MODULE_NAME);
} catch (Exception $e) {
    // Module is probably not activated yet. 
    // simply ignore the error and return empty array.
}