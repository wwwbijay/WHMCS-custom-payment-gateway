<?php

/**
 * MyPay Payment Gateway WHMCS Module
 * * @copyright Copyright (c) MyPay Private Limited
 * * @author : 
 */

function mypaygateway_validate_currency($currency_code)
{
    return $currency_code == "NPR";
}

function mypaygateway_convert_currency($currency_code, $amount)
{
    $payment_currency_id = WHMCS\Database\Capsule::table("tblcurrencies")->where("code", $currency_code)->value("id");
    $npr_currency_id = WHMCS\Database\Capsule::table("tblcurrencies")->where("code", "NPR")->value("id");
    if (is_null($payment_currency_id)) {
        return FALSE;
    }
    return convertCurrency($amount, $payment_currency_id, $npr_currency_id); //Here the result is the same amount sent
}

function mypaygateway_convert_from_npr_to_basecurrency($npr_amount)
{
    $base_currency_id = WHMCS\Database\Capsule::table("tblcurrencies")->where("default", "1")->value("id");
    $npr_currency_id = WHMCS\Database\Capsule::table("tblcurrencies")->where("code", "NPR")->value("id");
    if ($base_currency_id) {
        if ($base_currency_id == $npr_currency_id) {
            return $npr_amount;
        } else {
            return convertCurrency($npr_amount, $npr_currency_id, $base_currency_id);
        }
    } else {
        return FALSE;
    }
}

function mypaygateway_whmcs_current_page()
{
    $filename = basename($_SERVER['SCRIPT_FILENAME']);
    return str_replace(".PHP", "", strtoupper($filename));
}

function mypaygateway_get_production_mode($params)
{
    $is_test_mode = $params['is_test_mode'] == "on";

    # more explicit check for live mode
    if($is_test_mode) {
        return MYPAYGATEWAY_TEST_MODE;
    } else {
        return MYPAYGATEWAY_LIVE_MODE;
    }
}

function mypaygateway_debug($params, $data)
{
    $is_debug_mode = $params['is_debug_mode'] == "on";

    if ($is_debug_mode) {
        echo <<<EOT
        <div class='alert alert-warning' style='margin:0 10%; border-left:10px solid #5E338D;'>
        <strong>Debug Information for MyPay Payment Gateway</strong>
        EOT;
        ndie($data);
        echo "</div>";
    }
}

function mypaygateway_api_endpoint($params)
{
    $mode_name = mypaygateway_get_production_mode($params);
    return constant("MYPAYGATEWAY_" . strtoupper($mode_name) . "_ENDPOINT");
}

function mypaygateway_initiate($params, $checkout_params)
{
    return mypaygateway_make_api_call($params, MYPAYGATEWAY_INITIATE_API, $checkout_params);
}

function mypaygateway_make_api_call($params, $api, $payload)
{
    if (!$api) {
        return null;
    }

    $testMode = $params['testMode'];

    if($testMode == 'yes'){
            $base_url = 'https://testapi.mypay.com.np';
        }else{
            $base_url = 'https://smartdigitalnepal.com';
        }

    $url = $base_url. '/api/use-mypay-payments';
    $api_key = $params['api_key'];

    $post_data = json_encode($payload);

    // Call the API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type' => 'application/json',
		'API_KEY' => $api_key,
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        mypaygateway_debug($params, $ch);
        return null;
    }
    curl_close($ch);

    mypaygateway_debug($params, $response);

    return json_decode($response, true);
}

function mypaygateway_whmcs_local_api($command, $args){
    return localAPI($command, $args);
}

function mypaygateway_whmcs_get_invoice($invoice_id){
    return  mypaygateway_whmcs_local_api("GetInvoice", array("invoiceid" => $invoice_id));
}


function mypaygateway_whmcs_get_client($userid){
    return mypaygateway_whmcs_local_api("GetClientsDetails", ["clientid" => $userid, "stats" => true]);
}

