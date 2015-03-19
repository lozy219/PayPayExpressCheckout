<?php
include_once("../include/utilities.php");
include_once("../include/constant.php");
include_once("../controller/TextbookController.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

if ($_POST) {
    // unset the value stored in session
    unset($_SESSION[ITEM_IDENTIFIER]);
    unset($_SESSION[ITEM_ID_IDENTIFIER]);
    // array for the item name and price
    // this is the name value pairs for using the API
    $item = array();
    // array for the item id
    // this is for updating database later 
    $item_id = array();

    // store item information
    foreach ($_POST['selected-book'] as $value) {
        $item[$_POST['item-' . $value]] = $_POST['price-' . $value];
        $item_id[] = $value;
    }

    // put the two array in session
    $_SESSION[ITEM_IDENTIFIER] = $item;
    $_SESSION[ITEM_ID_IDENTIFIER] = $item_id;
    
    // first part of the request: method, urls, and action
    $request = '&METHOD=' . urlencode(SET_METHOD) . 
               '&RETURNURL=' . urlencode(RETURN_URL) .
               '&CANCELURL=' . urlencode(CANCEL_URL) .
               '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE");
    
    // since we can buy multiple items in one go, store the current index
    $current = 0;
    // total price for the items
    $total = 0;

    // second part of the request: item information
    foreach ($_SESSION[ITEM_IDENTIFIER] as $key => $value) {
        // we interate through the item array we stored
        $request .= '&L_PAYMENTREQUEST_0_NAME' . $current . '=' . urlencode($key).
                    '&L_PAYMENTREQUEST_0_NUMBER' . $current . '=1' . 
                    '&L_PAYMENTREQUEST_0_AMT' . $current . '=' . urlencode($value).
                    '&L_PAYMENTREQUEST_0_QTY' . $current . '=1';
        $current ++;
        $total += $value;
    }            
    
    // calculate the tax and total price
    $request .= '&NOSHIPPING=1' .
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($total).
                '&PAYMENTREQUEST_0_TAXAMT='.urlencode($total * GST_AMOUNT).
                '&PAYMENTREQUEST_0_SHIPPINGAMT=0'.
                '&PAYMENTREQUEST_0_HANDLINGAMT=0'.
                '&PAYMENTREQUEST_0_SHIPDISCAMT=0'.
                '&PAYMENTREQUEST_0_INSURANCEAMT=0'.
                '&PAYMENTREQUEST_0_AMT='.urlencode($total * (GST_AMOUNT + 1)).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode(CURRENCY) .
                '&LOCALECODE=SG' . 
                '&LOGOIMG=' . urlencode(LOGO_URL) .
                '&CARTBORDERCOLOR=FFFFFF' . 
                '&ALLOWNOTE=1';
    
    // post the request
    $response = postRequest(SET_METHOD, $request);
    
    if (checkAck($response)) {
        // Acked
        $paypalurl ='https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $response["TOKEN"];
        header('Location: ' . $paypalurl);
    } else {
        // transaction failed
        header("Location: failed");
    }
}

if (isset($_GET["token"]) && isset($_GET["PayerID"])) {
    $token = $_GET["token"];
    $payer_id = $_GET["PayerID"];

    $request = '&TOKEN=' . urlencode($token) .
               '&PAYERID=' . urlencode($payer_id).
               '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE");

    $current = 0;
    $total = 0;
    foreach ($_SESSION[ITEM_IDENTIFIER] as $key => $value) {
        $request .= '&L_PAYMENTREQUEST_0_NAME' . $current . '=' . urlencode($key).
                    '&L_PAYMENTREQUEST_0_NUMBER' . $current . '=1' . 
                    '&L_PAYMENTREQUEST_0_AMT' . $current . '=' . urlencode($value).
                    '&L_PAYMENTREQUEST_0_QTY' . $current . '=1';
        $current ++;
        $total += $value;
    }

    $request .=  '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($total).
                 '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($total * 0.07).
                 '&PAYMENTREQUEST_0_SHIPPINGAMT=0' .
                 '&PAYMENTREQUEST_0_HANDLINGAMT=0' .
                 '&PAYMENTREQUEST_0_SHIPDISCAMT=0' .
                  '&PAYMENTREQUEST_0_INSURANCEAMT=0' .
                 '&PAYMENTREQUEST_0_AMT=' . urlencode($total * 1.07) .
                 '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode(CURRENCY);

    $response = postRequest(DO_METHOD, $request);

    if ((checkAck($response)) && (checkCompleted($response))) {
        // mark the items as sold
        header("Location: mark/" . json_encode($_SESSION[ITEM_ID_IDENTIFIER]));
    } else {
        header("Location: failed");
    }
}
?>
