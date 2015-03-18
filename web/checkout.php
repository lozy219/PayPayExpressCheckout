<?php
include_once("utilities.php");

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
			   '&RETURNURL=' . urlencode($PayPalReturnURL).
			   '&CANCELURL=' . urlencode($PayPalCancelURL).
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
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode) .
				'&LOCALECODE=SG' . 
				'&LOGOIMG=http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Max_Planck_1933.jpg/450px-Max_Planck_1933.jpg' . 
				'&CARTBORDERCOLOR=FFFFFF' . 
				'&ALLOWNOTE=1';
	
	$response = postRequest('SetExpressCheckout', $request);
	
	if (checkAck($response["ACK"])) {
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
				 '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode);
	
	$response = postRequest('DoExpressCheckoutPayment', $request);
	
	if ((checkAck($response["ACK"]))) {
		echo '<h2>Success</h2>';
		echo 'Your Transaction ID : '.urldecode($response["PAYMENTINFO_0_TRANSACTIONID"]);
				
		if('Completed' == $response["PAYMENTINFO_0_PAYMENTSTATUS"]) {
			echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
		}
		elseif('Pending' == $response["PAYMENTINFO_0_PAYMENTSTATUS"]) {
			echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
			'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
		}

		// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
		// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
		$request = 	'&TOKEN='.urlencode($token);
		$paypal= new MyPayPal();
		$response = $paypal->PPHttpPost('GetExpressCheckoutDetails', $request, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

		if (checkAck($response["ACK"])) {
			
			echo '<br /><b>Stuff to store in database :</b><br /><pre>';
			/*
			#### SAVE BUYER INFORMATION IN DATABASE ###
			//see (http://www.sanwebe.com/2013/03/basic-php-mysqli-usage) for mysqli usage
			
			$buyerName = $response["FIRSTNAME"].' '.$response["LASTNAME"];
			$buyerEmail = $response["EMAIL"];
			
			//Open a new connection to the MySQL server
			$mysqli = new mysqli('host','username','password','database_name');
			
			//Output any connection error
			if ($mysqli->connect_error) {
				die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
			}		
			
			$insert_row = $mysqli->query("INSERT INTO BuyerTable 
			(BuyerName,BuyerEmail,TransactionID,ItemName,ItemNumber, ItemAmount,ItemQTY)
			VALUES ('$buyerName','$buyerEmail','$transactionID','$ItemName',$ItemNumber, $ItemTotalPrice,$ItemQTY)");
			
			if($insert_row){
				print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />'; 
			}else{
				die('Error : ('. $mysqli->errno .') '. $mysqli->error);
			}
			
			*/
			
			echo '<pre>';
			print_r($response);
			echo '</pre>';
		} else  {
			echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($response["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($response);
			echo '</pre>';

		}
		
	}else{
		echo '<div style="color:red"><b>Error : </b>'.urldecode($response["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($response);
		echo '</pre>';
	}
}
?>
