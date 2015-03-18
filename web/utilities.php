<?php
include_once("config.php");

// check ack for response
function checkAck($response) {
	$ack = strtoupper($response["ACK"]);
	return $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING";
}

// check complete for response
function checkCompleted($response) {
	$status = strtoupper($response["PAYMENTINFO_0_PAYMENTSTATUS"]);
	return $status == "COMPLETED" || $status == "PENDING";
}

// post request using curl
function postRequest($method, $nvp) {
	$toCurl = curl_init();

	// curl set up
	curl_setopt($toCurl, CURLOPT_URL, "https://api-3t.sandbox.paypal.com/nvp");
	curl_setopt($toCurl, CURLOPT_PORT , 443);
	curl_setopt($toCurl, CURLOPT_VERBOSE, 0);
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
	
	// execute
	$response = curl_exec($toCurl);
	
	if (!$response) {
		// failed
		header("Location: failed");
	} else {
		// store the response
		$responseArray = explode("&", $response);
	
		$parsedResponse = array();
		foreach ($responseArray as $value) {
			$responseNvp = explode("=", $value);
			if (sizeof($responseNvp) > 1) {
				$parsedResponse[$responseNvp[0]] = $responseNvp[1];
			}
		}
	
		if (!array_key_exists('ACK', $parsedResponse) || (sizeof($parsedResponse) == 0)) {
			// invalid response
			header("Location: failed");
		}
	
		return $parsedResponse;
	}
}
?>