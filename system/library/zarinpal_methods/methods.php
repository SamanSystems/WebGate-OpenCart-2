<?php
    function Request($MerchantID,$Amount,$Description,$CallbackURL){
		// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
		$client = new SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
		
		$result = $client->PaymentRequest(
							array(
									'MerchantID' 	=> $MerchantID,
									'Amount' 	=> $Amount,
									'Description' 	=> $Description,
									'Email' 	=> @$Email,
									'Mobile' 	=> @$Mobile,
									'CallbackURL' 	=> $CallbackURL
								)
		);
		
		return $result;
		}
		
  
    function Verification($MerchantID,$Amount,$Authority){
		// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
		$client = new SoapClient('https://ir.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
		
		$result = $client->PaymentVerification(
						  	array(
									'MerchantID'	 => $MerchantID,
									'Authority' 	 => $Authority,
									'Amount'	 => $Amount
								)
		);
		
		return $result;

    }
?>
