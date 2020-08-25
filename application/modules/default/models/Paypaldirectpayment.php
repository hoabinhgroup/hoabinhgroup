<?php

	
	class Model_PaypalDirectPayment extends Model_PaypalPaymentPro
	{
		public $email;
		public $firstname;
		public $lastname;
		public $cardtype;	// 'Visa', 'MasterCard', 'Discover', 'Amex' 
		public $cardnumber;
		public $expireMonth;
		public $expireYear;
		public $cvv2;      //security code from credit/debit card
		
		
		/**
		* Tien hanh request den paypal de check va reponse
		* Thong tin chi tiet cua response co trong property $httpResponseArray
		* 
		* @return true/false: de biet thanh toan co success hay khong
		* 
		*/               
		public function checkout($isDebug = false)
		{
			$this->cardnumber = str_replace('-', '', $this->cardnumber);
			$this->expireMonth = str_pad($this->expireMonth, 2, '0', STR_PAD_LEFT);   
			
			$nvpStr ="&PAYMENTACTION=".urlencode($this->type)."&IPADDRESS=".urlencode($this->ipaddress)."&CREDITCARDTYPE=".urlencode($this->cardtype)."&ACCT=".urlencode($this->cardnumber)."".
					"&EXPDATE=".urlencode($this->expireMonth.$this->expireYear)."&CVV2=".urlencode($this->cvv2)."&FIRSTNAME=".urlencode($this->firstname)."&LASTNAME=".urlencode($this->lastname)."".
					"&EMAIL=".urlencode($this->email)."&CURRENCYCODE=".urlencode($this->currency)."".
					"&AMT=".urlencode($this->priceTotalAmount)."&ITEMAMT=".urlencode($this->priceTotalItem)."&SHIPPINGAMT=".urlencode($this->priceShipping)."&TAXAMT=".urlencode($this->priceTax)."&INVNUM=".urlencode($this->invoiceNum)."";
			
			if($isDebug)
			{
				die($nvpStr);	
			}

			// Execute the API operation; see the PPHttpPost function above.
			$this->httpResponseArray = $this->Paypal_PPHttpPost('DoDirectPayment', '51.0', $nvpStr);
			
			if(substr_count($this->httpResponseArray["ACK"], 'Success') > 0)
			{
				return true;				
			} 
			else  
			{
				return false;
			}
		}
		
		
		
		
		
	}
?>