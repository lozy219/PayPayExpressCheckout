<?php
include_once("utilities.php");
include_once("constant.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}

if ($_POST) {
	unset($_SESSION['item']);
	$item = array();

	foreach ($_POST['selected-book'] as $value) {
		$item[$_POST['item-' . $value]] = $_POST['price-' . $value];
	}
	$_SESSION['item'] = $item;
	
	$request = '&METHOD=SetExpressCheckout'.
			   '&RETURNURL=' . urlencode(RETURN_URL) .
			   '&CANCELURL=' . urlencode(CANCEL_URL) .
			   '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE");
	
	$current = 0;
	$total = 0;
	foreach ($_SESSION['item'] as $key => $value) {
		$request .= '&L_PAYMENTREQUEST_0_NAME' . $current . '=' . urlencode($key).
					'&L_PAYMENTREQUEST_0_NUMBER' . $current . '=1' . 
					'&L_PAYMENTREQUEST_0_AMT' . $current . '=' . urlencode($value).
					'&L_PAYMENTREQUEST_0_QTY' . $current . '=1';
		$current ++;
		$total += $value;
	}			
	
	$request .= '&NOSHIPPING=1' .
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($total).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($total * 0.07).
				'&PAYMENTREQUEST_0_SHIPPINGAMT=0'.
				'&PAYMENTREQUEST_0_HANDLINGAMT=0'.
				'&PAYMENTREQUEST_0_SHIPDISCAMT=0'.
				'&PAYMENTREQUEST_0_INSURANCEAMT=0'.
				'&PAYMENTREQUEST_0_AMT='.urlencode($total * 1.07).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode(CURRENCY) .
				'&LOCALECODE=SG' . 
				'&LOGOIMG=http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Max_Planck_1933.jpg/450px-Max_Planck_1933.jpg' . 
				'&CARTBORDERCOLOR=FFFFFF' . 
				'&ALLOWNOTE=1';
	
	$response = postRequest('SetExpressCheckout', $request);
	
	if (checkAck($response)) {
		$paypalurl ='https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $response["TOKEN"];
		header('Location: '.$paypalurl);
		
	} else {
		echo '<div style="color:red"><b>Error : </b>'.urldecode($response["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($response);
		echo '</pre>';
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
	foreach ($_SESSION['item'] as $key => $value) {
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

	$response = postRequest('DoExpressCheckoutPayment', $request);

	if ((checkAck($response)) && (checkCompleted($response))) {
		// modify database
		header("Location: success");
	} else {
		
	}
}
?>
