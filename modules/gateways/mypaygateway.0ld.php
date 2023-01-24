<?php
/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "gatewaymodule" and therefore all functions
 * begin "mypaygateway_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function mypaygateway_MetaData()
{
    return array(
        'DisplayName' => 'MyPay Payment Gateway Module',
        'APIVersion' => '1.1', // Use API Version 1.1
        'DisableLocalCreditCardInput' => true,
        'TokenisedStorage' => false,
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function mypaygateway_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'MyPay Payment Gateway Module',
        ),
        'testMode' => array(
            'FriendlyName' => 'Test Mode',
            'Type' => 'yesno',
            'Description' => 'Tick to enable test mode',
        ),
        // a text field type allows for single line text input
        'merchant_id' => array(
            'FriendlyName' => 'Merchant ID',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your merchant ID here',
        ),
        // a text field type allows for single line text input
        'user_name' => array(
            'FriendlyName' => 'User Name',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your merchant username here',
        ),
        // a text field type allows for single line text input
        'password' => array(
            'FriendlyName' => 'Password',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your merchant password here',
        ),
        // a password field type allows for masked text input
        'api_key' => array(
            'FriendlyName' => 'API Key',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter merchant API key here',
        ),        
    );
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/third-party-gateway/
 *
 * @return string
 */

function mypaygateway_link($params)
{
    $currentPage = khaltigateway_whmcs_current_page();
    if ($currentPage !== KHALTIGATEWAY_WHMCS_VIEWINOVICE_PAGE) {
        return khaltigateway_noinvoicepage_code();
    }
    return  khaltigateway_invoicepage_code($gateway_params);

    // // Gateway Configuration Parameters
    // $merchantID = $params['merchant_id'];
    // $user_name = $params['user_name'];
    // $testMode = $params['testMode'];
    // $password = $params['password'];
    // $api_key = $params['api_key'];

    // // Invoice Parameters
    // $invoiceId = $params['invoiceid'];
    // $description = $params["description"];
    // $amount = $params['amount'];
    // $currencyCode = $params['currency'];

    // // Client Parameters
    // $firstname = $params['clientdetails']['firstname'];
    // $lastname = $params['clientdetails']['lastname'];
    // $email = $params['clientdetails']['email'];
    // $address1 = $params['clientdetails']['address1'];
    // $address2 = $params['clientdetails']['address2'];
    // $city = $params['clientdetails']['city'];
    // $state = $params['clientdetails']['state'];
    // $postcode = $params['clientdetails']['postcode'];
    // $country = $params['clientdetails']['country'];
    // $phone = $params['clientdetails']['phonenumber'];

    // // System Parameters
    // $companyName = $params['companyname'];
    // $systemUrl = $params['systemurl'];
    // $returnUrl = $params['returnurl'];
    // $langPayNow = $params['langpaynow'];
    // $moduleDisplayName = $params['name'];
    // $moduleName = $params['paymentmethod'];
    // $whmcsVersion = $params['whmcsVersion'];

    // if($testMode == 'yes'){
    //     $base_url = 'https://testapi.mypay.com.np';
    // }else{
    //     $base_url = 'https://smartdigitalnepal.com';
    // }
    
    // $endpoint = $base_url. '/api/use-mypay-payments';
    
    // $postfields = array();
    // $postfields['username'] = $username;
    // $postfields['invoice_id'] = $invoiceId;
    // $postfields['description'] = $description;
    // $postfields['amount'] = $amount;
    // $postfields['currency'] = $currencyCode;
    // $postfields['first_name'] = $firstname;
    // $postfields['last_name'] = $lastname;
    // $postfields['email'] = $email;
    // $postfields['address1'] = $address1;
    // $postfields['address2'] = $address2;
    // $postfields['city'] = $city;
    // $postfields['state'] = $state;
    // $postfields['postcode'] = $postcode;
    // $postfields['country'] = $country;
    // $postfields['phone'] = $phone;
    // $postfields['callback_url'] = $systemUrl . '/modules/gateways/callback/' . $moduleName . '.php';
    // $postfields['return_url'] = $returnUrl;

    // $constant = date_create()->getTimestamp();
	// $order_id= $invoiceId . $constant;

	// 	$body = [
	// 	  'Amount'  => $amount,
	// 	  'OrderId' => $order_id,
	// 	  'UserName' => $user_name,
	// 	  'Password' => $password,
	// 	  'MerchantId' => $merchantID,
	// 	];

	// 	$body = wp_json_encode( $body );

	// 	$options = [
	// 	  'body'        => $body,
	// 	  'headers'     => [
	// 		'Content-Type' => 'application/json',
	// 		'API_KEY' => $api_key,
	// 	  ],
	// 	  'data_format' => 'body',
	// 	];
	  

    //     $htmlOutput = '<form method="post" action="' . $url . '">';
    //     foreach ($postfields as $k => $v) {
    //         $htmlOutput .= '<input type="hidden" name="' . $k . '" value="' . urlencode($v) . '" />';
    //     }
    //     $htmlOutput .= '<input type="submit" value="' . $langPayNow . '" />';
    //     $htmlOutput .= '</form>';

    //     return $htmlOutput;     
}

/**
 * Refund transaction.
 *
 * Called when a refund is requested for a previously successful transaction.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/refunds/
 *
 * @return array Transaction response status
 */
// function mypaygateway_refund($params)
// {
//     // Gateway Configuration Parameters
//     $merchantID = $params['merchant_id'];
//     $user_name = $params['user_name'];
//     $testMode = $params['testMode'];
//     $password = $params['password'];
//     $api_key = $params['api_key'];

//     // Transaction Parameters
//     $transactionIdToRefund = $params['transid'];
//     $refundAmount = $params['amount'];
//     $currencyCode = $params['currency'];

//     // Client Parameters
//     $firstname = $params['clientdetails']['firstname'];
//     $lastname = $params['clientdetails']['lastname'];
//     $email = $params['clientdetails']['email'];
//     $address1 = $params['clientdetails']['address1'];
//     $address2 = $params['clientdetails']['address2'];
//     $city = $params['clientdetails']['city'];
//     $state = $params['clientdetails']['state'];
//     $postcode = $params['clientdetails']['postcode'];
//     $country = $params['clientdetails']['country'];
//     $phone = $params['clientdetails']['phonenumber'];

//     // System Parameters
//     $companyName = $params['companyname'];
//     $systemUrl = $params['systemurl'];
//     $langPayNow = $params['langpaynow'];
//     $moduleDisplayName = $params['name'];
//     $moduleName = $params['paymentmethod'];
//     $whmcsVersion = $params['whmcsVersion'];

//     // perform API call to initiate refund and interpret result

//     return array(
//         // 'success' if successful, otherwise 'declined', 'error' for failure
//         'status' => 'success',
//         // Data to be recorded in the gateway log - can be a string or array
//         'rawdata' => $responseData,
//         // Unique Transaction ID for the refund transaction
//         'transid' => $refundTransactionId,
//         // Optional fee amount for the fee value refunded
//         'fees' => $feeAmount,
//     );
// }

/**
 * Cancel subscription.
 *
 * If the payment gateway creates subscriptions and stores the subscription
 * ID in tblhosting.subscriptionid, this function is called upon cancellation
 * or request by an admin user.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see https://developers.whmcs.com/payment-gateways/subscription-management/
 *
 * @return array Transaction response status
 */
function mypaygateway_cancelSubscription($params)
{
    // Gateway Configuration Parameters
    $merchantID = $params['merchant_id'];
    $user_name = $params['user_name'];
    $testMode = $params['testMode'];
    $password = $params['password'];
    $api_key = $params['api_key'];

    // Subscription Parameters
    $subscriptionIdToCancel = $params['subscriptionID'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // perform API call to cancel subscription and interpret result

    return array(
        // 'success' if successful, any other value for failure
        'status' => 'success',
        // Data to be recorded in the gateway log - can be a string or array
        'rawdata' => $responseData,
    );
}
