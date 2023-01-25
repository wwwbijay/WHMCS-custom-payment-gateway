<div class='row' id='mypaygateway-button-wrapper'>
    <div class='col-sm-12' style='padding:2em; border:1px solid #cccccc; background:#eeeeee'>
        <div class='row' id='mypaygateway-button-content'>
            <div class='col-sm-5'>
                <div class='thumbnail' style='border:0px;box-shadow:none; margin-top:2em;'>
                    <img src='<?php echo $_inc_vars['mypay_logo_url']; ?>' alt='MyPay Digital Wallet' />
                </div>
            </div>
            <div class='col-sm-7 text-left' style='border-left:1px solid #f9f9f9'>
                <small>You can pay with MyPay account or other e-Banking Options</small>
                <br />
                <br />
                <a id='mypay-payment-button' href='<?php echo $_inc_vars['pidx_url']; ?>' class='btn btn-primary btn-large' 
                style='<?php echo $_inc_vars['button_css']; ?>'>
                    <?php echo $_inc_vars['gateway_params']['langpaynow']; ?>
                </a>
                <br />
                <small>NPR <?php echo $_inc_vars['npr_amount']; ?></small>
            </div>
        </div>
    </div>
</div>