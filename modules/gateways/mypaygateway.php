<?php
/**
 * MyPay Payment Gateway WHMCS Module
 * @copyright Copyright (c) MyPay Digital Wallet
 * @author : MyPay Digital Wallet
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . "/mypaygateway/init.php";

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
            'Size' => '100',
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
    $currentPage = mypaygateway_whmcs_current_page();
    if ($currentPage !== MYPAYGATEWAY_WHMCS_VIEWINOVICE_PAGE) {
        return mypaygateway_noinvoicepage_code();
    }
    return  mypaygateway_invoicepage_code($params);

}

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
