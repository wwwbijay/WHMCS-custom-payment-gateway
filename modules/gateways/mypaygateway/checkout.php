<?php
/**
 * MyPay Payment Gateway WHMCS Module
 * @copyright Copyright (c) MyPay Digital Wallet
 * @author : MyPay Digital Wallet
 */

function mypaygateway_noinvoicepage_code()
{
    return file_get_contents(__DIR__ . "/templates/noninvoice_page.html");
}

function mypaygateway_invoicepage_code($params)
{
    $system_url = $params['systemurl'];
    $invoice_id = $params['invoiceid'];

    $description = htmlspecialchars(strip_tags($params["description"]));
    $amount = $params['amount'];
    $currency_code = $params['currency'];

    $user_name = $params['user_name'];
    $password = $params['password'];
    $merchantID = $params['merchantID'];


    if ($currency_code != "NPR") {
        return mypaygateway_invalid_currency_page();
        // $npr_amount = mypaygateway_convert_currency($currency_code, $amount);
    } else {
        $npr_amount = $amount;
    }

    $invoice = mypaygateway_whmcs_get_invoice($invoice_id);
    $userid = $invoice["userid"];

    $customer_details = mypaygateway_whmcs_get_client($userid);
    $customer_name = $customer_details["fullname"];
    $customer_email = $customer_details["email"];
    $customer_phone_number = $customer_details["phonenumber"];

    $npr_amount_in_paisa = $npr_amount * 100;
    $module_url = "modules/gateways/mypaygateway/";

    $callback_url = "{$system_url}{$module_url}callback.php";
    $invoice_url = "{$system_url}viewinvoice.php?id={$invoice_id}";
    $successUrl = "{$invoice_url}&paymentsuccess=true";

    $cart = array();
    foreach ($params["cart"]->items as $item) {
        $amount = $item->getAmount()->getValue();
        $currency_code = $item->getAmount()->getCurrency()['code'];
        if (!mypaygateway_validate_currency($currency_code)) {
            return mypaygateway_invalid_currency_page();
        }

        $item_amount_in_paisa = intval($amount * 100);

        $qty = $item->getQuantity();
        $cart[] = array(
            "name" => $item->getName(),
            "identity" => $item->getUuid(),
            "total_price" => $item_amount_in_paisa,
            "quantity" => $qty,
            "unit_price" => $item_amount_in_paisa / $qty
        );
    }

    $payment_constant = 10000000;

    $checkout_args = array(
        "Amount"  => $npr_amount,
        "OrderId" => ($invoice_id + $payment_constant),
        "UserName" => $user_name,
        "Password" => $password,
        "MerchantId" => $merchantID,
  );
    // $checkout_args = array(
    //     "return_url" => "{$callback_url}",
    //     "website_url" => "{$system_url}",
    //     "amount" => $npr_amount_in_paisa,
    //     "purchase_order_id" => "{$invoice_id}",
    //     "purchase_order_name" => "{$description}",
    //     "customer_info" => array(
    //         "name" => $customer_name,
    //         "email" => $customer_email,
    //         "phone" => $customer_phone_number
    //     ),
    //     "amount_breakdown" => array(
    //         array(
    //             "label" => "Invoice Number - {$invoice_id}",
    //             "amount" => $npr_amount_in_paisa
    //         ),
    //     ),
    //     "product_details" => $cart
    // );

    

    return mypaygateway_pidx_page($params, $npr_amount, $checkout_args);
}

function mypaygateway_pidx_page($params, $npr_amount, $checkout_args){
    $payment_initiate = mypaygateway_initiate($params, $checkout_args);

    $response_body =  $payment_initiate['body'];

    if (!$response_body['status']) {
        return file_get_contents(__DIR__ . "/templates/initiate_failed.html");
    }

    /*
     * Variables required for the template
     * pidx_url
     * button_css
     * gateway_params
     * npr_amount
     */
    $pidx_url = $response_body['RedirectURL'];
    // $pidx_url = $payment_initiate["payment_url"];

    return file_include_contents(__DIR__ . "/templates/invoice_payment_button.php", array(
        'mypay_logo_url' => 'https://mypay.com.np/frontend/images/logo.png',
        "pidx_url" => $pidx_url,
        "button_css" => "",
        "gateway_params" => $params,
        "npr_amount" => $npr_amount
    ));
}

function mypaygateway_invalid_currency_page(){
    return file_get_contents(__DIR__ . '/templates/invalid_currency.html');
}