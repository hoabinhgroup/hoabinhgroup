<?php
	
	class Model_PaypalExpressCheckout extends Model_PaypalPaymentPro
	{
		public $fullname;
		public $returnUrl;	// URL to redirect if on paypal, user click pay
		public $cancelUrl;	// URL to redirect if on paypal, user click cancel and return your shoop
		public $logoUrl;	// URL of your logo will be displayed on top of page in paypal. Must be absolute url (Ex: http://myshop.com/image/logo.jpg). Size: 760x90px
		public $nvpStrItems;	//	STRING OF ALL CART PRODUCTS DETAIL
		
		public $payerId;
		public $token;
		
		/**
		* request den paypal truoc khi tien hanh request de thanh toan o buoc 2
		* 
		*/
		public function setexpress($isDebug = false)
		{
		  /*	
                    $nvpStr =	'&PAYMENTACTION='.urlencode($this->type).'&CURRENCYCODE='.urlencode($this->currency).'&RETURNURL='.urlencode($this->returnUrl).'&CANCELURL='.urlencode($this->cancelUrl).
						'&AMT='.urlencode($this->priceTotalAmount).'&ALLOWNOTE=1&NAME='.urlencode($this->fullname).''.
						'&HDRIMG='.urlencode($this->logoUrl). $this->nvpStrItems. 
						'&ITEMAMT='.urlencode($this->priceTotalItem).'&TAXAMT='.urlencode($this->priceTax).'&SHIPPINGAMT='.urlencode($this->priceShipping).'&INVNUM=' . urlencode($this->invoiceNum);
		*/
                    $nvpStr =	'&PAYMENTACTION='.urlencode($this->type).'&RETURNURL='.urlencode($this->returnUrl).'&CANCELURL='.urlencode($this->cancelUrl).
						'&AMT='.urlencode($this->priceTotalAmount).'&ALLOWNOTE=1&NAME='.urlencode($this->fullname).''.
						'&HDRIMG='.urlencode($this->logoUrl). $this->nvpStrItems. 
						'&ITEMAMT='.urlencode($this->priceTotalItem).'&TAXAMT='.urlencode($this->priceTax).'&SHIPPINGAMT='.urlencode($this->priceShipping).'&INVNUM=' . urlencode($this->invoiceNum);
                     //exit(print_r($nvpStr,true));
			if($isDebug)
			{
				die($nvpStr);	
			}

			// Execute the API operation; see the PPHttpPost function above.
			$this->httpResponseArray = $this->Paypal_PPHttpPost('SetExpressCheckout', '53.0', $nvpStr); 
			
			if(substr_count($this->httpResponseArray["ACK"], 'Success') > 0)
			{
				return true;				
			} 
			else  
			{
				return false;
                               
	
                        }
			
                        
		}
		
		
		public function checkout($isDebug = false)
		{
			
                    $nvpStr =	'&PAYMENTACTION='.urlencode($this->type).'&CURRENCYCODE='.urlencode($this->currency).'&RETURNURL='.urlencode($this->returnUrl).'&CANCELURL='.urlencode($this->cancelUrl).
						'&AMT='.urlencode($this->priceTotalAmount).'&ALLOWNOTE=1&NAME='.urlencode($this->fullname).''.
						'&HDRIMG='.urlencode($this->logoUrl). $this->nvpStrItems. 
						'&ITEMAMT='.urlencode($this->priceTotalItem).'&TAXAMT='.urlencode($this->priceTax).'&SHIPPINGAMT='.urlencode($this->priceShipping).'&INVNUM=' . urlencode($this->invoiceNum) .
						'&PAYERID='.$this->payerId.'&TOKEN=' . $this->token;
                /*
                        $nvpStr =	'&PAYMENTACTION='.urlencode($this->type).'&CURRENCYCODE='.urlencode($this->currency).'&RETURNURL='.urlencode($this->returnUrl).'&CANCELURL='.urlencode($this->cancelUrl).
						'&AMT=10&ALLOWNOTE=1&NAME='.urlencode($this->fullname).''.
						'&HDRIMG='.urlencode($this->logoUrl). $this->nvpStrItems. 
						'&ITEMAMT='.urlencode($this->priceTotalItem).'&TAXAMT='.urlencode($this->priceTax).'&SHIPPINGAMT='.urlencode($this->priceShipping).'&INVNUM=' . urlencode($this->invoiceNum) .
						'&PAYERID='.$this->payerId.'&TOKEN=' . $this->token;
                 * 
                 */
                        //exit(print_r($nvpStr,true));
			if($isDebug)
			{
				die($nvpStr);	
			}

			// Execute the API operation; see the PPHttpPost function above.
			$this->httpResponseArray = $this->Paypal_PPHttpPost('DoExpressCheckoutPayment', '52.0', $nvpStr);
			
			if(substr_count($this->httpResponseArray["ACK"], 'Success') > 0)
			{
				return true;				
			} 
			else  
			{
				//return false;
                            exit('SetExpressCheckout failed: ' . print_r($this->httpResponseArray, true));
			}
		}
		
		
	
}
?>