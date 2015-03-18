<?php
include_once("config.php");

function checkAck($response) {
	$ack = strtoupper($response["ACK"]);
	return $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING";
}

function checkCompleted($response) {
	$status = strtoupper($response["PAYMENTINFO_0_PAYMENTSTATUS"]);
	return $status == "COMPLETED" || $status == "PENDING";
}

function postRequest($method, $nvp) {
	$toCurl = curl_init();
	curl_setopt($toCurl, CURLOPT_URL, "https://api-3t.sandbox.paypal.com/nvp");
	curl_setopt($toCurl, CURLOPT_PORT , 443);
	curl_setopt($toCurl, CURLOPT_VERBOSE, 1);// to be changed
	curl_setopt($toCurl, CURLOPT_VERBOSE, TRUE);
	curl_setopt($toCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($toCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($toCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($toCurl, CURLOPT_POST, 1);
	
	$request = "METHOD=" . $method .
			   "&VERSION=" . urlencode('121') . 
			   "&PWD=" . urlencode(API_PASSWORD) . 
			   "&USER=" . urlencode(API_USERNAME) . 
			   "&SIGNATURE=" . urlencode(API_SIGNATURE) . 
			   $nvp;
	
	curl_setopt($toCurl, CURLOPT_POSTFIELDS, $request);
	
	$response = curl_exec($toCurl);
	
	if (!$response) {
		exit("$method failed: ".curl_error($toCurl).'('.curl_errno($toCurl).')');
	} else {
		$responseArray = explode("&", $response);
	
		$parsedResponse = array();
		foreach ($responseArray as $value) {
			$responseNvp = explode("=", $value);
			if (sizeof($responseNvp) > 1) {
				$parsedResponse[$responseNvp[0]] = $responseNvp[1];
			}
		}
	
		if (!array_key_exists('ACK', $parsedResponse) || (sizeof($parsedResponse) == 0)) {
			exit("Invalid HTTP Response for POST request($request) to $API_Endpoint.");
		}
	
		return $parsedResponse;
	}
}
?>