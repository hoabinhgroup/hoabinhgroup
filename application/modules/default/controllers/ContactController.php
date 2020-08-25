<?php
class ContactController extends Louis_Controller_Action
   {
	    public function init()
	   {
		   parent::init();
        $this->_helper->layout->setLayout('contact');
    	}
      
		public function indexAction()
		{
			$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/review.css');
			//  $this->view->headLink()->appendStylesheet('/public/js/loadding/jquery.loading-indicator.css'); 
		// $this->view->headScript()->appendFile('/public/js/loadding/jquery.loading-indicator.js');
		// $this->view->headScript()->appendFile('/public/js/rec.js');
			
			 $this->view->headScript()->appendFile('/application/modules/default/views/scripts/contact/contact.js');
			//echo $this->_lang;
			//echo __METHOD__;
			$param = $this->_request->getParams();
			$lang = $param['lang'];
			$ident = $param['ident'];
	
			$menu = new Model_MenuItem();
		    $options = array(
			    'link' => $ident,
			    'lang' => $lang,
		    );
		    
		    	if($this->_lang != $lang)
		    {
		 $this->_helper->viewRenderer->setNoRender(true);
		 $domain = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost();
		  $session = new Zend_Session_Namespace();
		  $session->currentUrl = $domain. $this->getRequest()->getRequestUri();
			
		 if($this->_lang == 'vi'){
			
			$this->_redirect($domain . '/translate/filters/change/en');
		 }else{
		
			$this->_redirect($domain . '/translate/filters/change/vi'); 
		 }
		}

		$this->view->doctype('XHTML1_RDFA');  // controller

		$this->view->headTitle('Liên hệ Hòa Bình Group');
		    
			$menu_obj = $menu->get_one_where($options);
			if($menu_obj){
			$this->view->description = $menu_obj->description;
			}
			if($lang == 'vi'){
				$this->renderScript('contact/index.phtml');
			}else{
				$this->renderScript('contact/index_en.phtml');
			}
		}
		
	/*	public function submitAction()
		{
		
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			// if($this->getRequest()->isXmlHttpRequest())
	      // {
		  //  $this->_helper->json($_POST);
		  //  die();
			$name = $this->_request->getPost('name');
			$email = $this->_request->getPost('email');
			$phone = $this->_request->getPost('phone');
			$company = $this->_request->getPost('company');
			$request = $this->_request->getPost('request');
			$message = $this->_request->getPost('message');
			
			
	        $html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
			// assign values
	 		 $html->assign('name', $name);
	 		 $html->assign('email', $email);
	 		 $html->assign('phone', $phone);
	 		 $html->assign('company', $company);
	 		 $html->assign('request', $request);
	 		 $html->assign('message', $message);
	  
	  $bodyText = $html->render('contact_template.phtml');
			
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'hoabinh@hoabinhtourist.com', 'password' => 'zjqkwafztgntpsol', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			$mail->setBodyHtml($bodyText);
			$mail->setFrom ( 'Tập đoàn Hòa Bình Group');
			$mail->addTo ( 'hoabinhwebmaster@gmail.com', 'Hòa Bình Group' );
			//$mail->addTo ( 'info@hoabinhtourist.com' );
			//$mail->addCc('hoabinhwebmaster@gmail.com', $name);				
			$mail->addCc($email);	
			$mail->setSubject ( $name );
			$mail->send ();
		
		//	echo $id;
		return true;
		//	}
	
	
		}*/
		
		public function submitAction()
		{
		 
		
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$name = $this->_request->getPost('name');
			$email = $this->_request->getPost('email');
			$phone = $this->_request->getPost('phone');
			$company = $this->_request->getPost('company');
			$request = $this->_request->getPost('request');
			$message = $this->_request->getPost('message');
			
			
	        $html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
			// assign valeues
	 		 $html->assign('name', $name);
	 		 $html->assign('email', $email);
	 		 $html->assign('phone', $phone);
	 		 $html->assign('company', $company);
	 		 $html->assign('request', $request);
	 		 $html->assign('message', $message);
	  
	  $bodyText = $html->render('contact_template.phtml');
			
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'hoabinhwebmaster@gmail.com', 'password' => 'whlfemisfxmhwewt', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			
			$mail->setBodyHtml($bodyText);
			$mail->setFrom ( 'mkt@hoabinh-group.com','Hòa Bình Group');
			$mail->addTo ( 'info@hoabinh-group.com' );
			$mail->addCc ( 'mkt@hoabinh-group.com' );
			//$mail->addTo ( 'louis.standbyme@gmail.com' );
			$mail->addCc($email);
			$mail->setSubject ( $name );
						
			$mail->send();
		
		//	echo $id;
		return true;
		
	
	
		}
		
}
