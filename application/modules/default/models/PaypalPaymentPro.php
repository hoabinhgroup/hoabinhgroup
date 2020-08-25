<?php
	/**
	* Process payment from Paypal
	* 
	* @author Vo Duy Tuan 
	* @author tuanmaster2002@yahoo.com
	* @version 1.0
	* @copyright Free for Edit
	*/
	
	class Model_PaypalPaymentPro extends Zend_Db_Table_Abstract
	{
		public $apiUsername; 		// Payment Pro - Username of API
		public $apiPassword;		// Payment Pro - Password of API
		public $apiSignature;		// Payment Pro - Signature of API
		public $apiEndpoint;		// Payment Pro - URL to request to API
		public $type = 'Sale';		// Can be: 'Sale' or 'Authorization'
		public $currency = 'USD';
                public $ipaddress;
		public $invoiceNum;			// your customer invoice id to track your order in paypal transaction
		public $priceTotalAmount;	// total price after all (tax, ship). = totalItem + shipping + tax
		public $priceTotalItem; 	// total price of item 
		public $priceShipping;		// shipping price
		public $priceTax;           // tax price
		
		
		
		public $httpResponseArray;	// Array contains info about response after request to paypal server to checkout
			
		public function __construct($apiUsername, $apiPassword, $apiSignature, $apiEndpoint)
		{
			$this->apiUsername = $apiUsername;
			$this->apiPassword = $apiPassword;
			$this->apiSignature = $apiSignature;
			$this->apiEndpoint = $apiEndpoint;
		}	
		
		/**
		* Goi request len server cua paypal de xu ly 
		* 
		* @param mixed $methodName_
		* @param mixed $version_
		* @param mixed $nvpStr_
		* @return mixed
		*/
		protected function Paypal_PPHttpPost($methodName_, $version_, $nvpStr_) 
		{
			//simulator error return form paypal
			
			                     					
			
			$version = urlencode($version_);

			// Set the curl parameters.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);

			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);

			// Set the API operation, version, and API signature in the request.
			$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=".$this->apiPassword."&USER=".$this->apiUsername."&SIGNATURE=".$this->apiSignature."$nvpStr_";
			//echo '<p>'.$nvpreq.'</p';

			// Set the request as a POST FIELD for curl.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

			// Get response from the server.
			$httpResponse = curl_exec($ch);

			if(!$httpResponse) 
			{
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}

			// Extract the response details.
			$httpResponseAr = explode("&", $httpResponse);
			
			

			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $i => $value) 
			{
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) 
				{
					$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
				}
			}

			if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
				exit("Invalid HTTP Response for POST request($nvpreq) to ".$this->apiEndpoint.".");
			}

			return $httpParsedResponseAr;
		}
		
	}
?>