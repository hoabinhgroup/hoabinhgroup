<?php 
class PagesController extends Louis_Controller_Action{ 
   
		public function init(){


      }
      
    public function indexAction(){ 
		
	$this->_helper->layout->disableLayout();
	$params = $this->getRequest()->getParams();
	$this->render($params['ident']);
		
    }
    

 
    public function submitAction()
    {
	   	 $this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
	
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$name = $this->_request->getPost('FULL_NAME');
			$email = $this->_request->getPost('EMAIL');
			$phone = $this->_request->getPost('PHONE');
			$message = $this->_request->getPost('DESCRIPTION');
			
			
			
	        $html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
			// assign valeues
	 		 $html->assign('name', $name);
	 		 $html->assign('email', $email);
	 		 $html->assign('phone', $phone);
	 		 $html->assign('message', $message);
	  
	  $bodyText = $html->render('request_template.phtml');
			
				$connmail = new Zend_Mail_Transport_Smtp ( 'email-smtp.us-west-2.amazonaws.com', array ('auth' => 'login', 'username' => 'AKIAIRWZAPGPU6CW7FDA', 'password' => 'Ai4h2VKXzNXfjgSXJMWdY9D3oM2F7oRb1NEr/gFprOhe', 'ssl' => 'ssl', 'port' => 465 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			
			$mail->setBodyHtml($bodyText);
			$mail->setFrom ( 'hoabinhwebmaster@gmail.com','Hòa Bình Group'); // ko dấu
			$mail->addTo ( 'hoabinhwebmaster@gmail.com' );
			//$mail->addTo ( 'meeting@hoabinh-group.com' );
			//$mail->addTo ( 'info@vietnamevents.com' );
			//$mail->addTo ( 'info@hoabinh-group.com' );
			$mail->addCc($email);
			$mail->setSubject ( $name );
			
	
			
			$mail->send();
		
		//	echo $id;
		return true;
    }
    
    
        public function submit2Action()
    {
	   	 $this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
	
			$db = Zend_Db_Table::getDefaultAdapter();
			$name = $this->_request->getPost('name');
			//$email = $this->_request->getPost('email');
			$mobile= $this->_request->getPost('mobile');
			$message = $this->_request->getPost('message');
			
			
			
	        $html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
			// assign valeues
	 		 $html->assign('name', $name);
	 		// $html->assign('email', $email);
	 		 $html->assign('mobile', $mobile);
	 		 $html->assign('message', $message);
	  
	  $bodyText = $html->render('contact_template2.phtml');
			
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'hoabinh@hoabinhtourist.com', 'password' => 'zjqkwafztgntpsol', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			
			$mail->setBodyHtml($bodyText);
			$mail->setFrom ( 'trang.tran@vietnamevents.com','Hòa Bình Events'); // ko dấu
			$mail->addTo ( 'trang.tran@vietnamevents.com' );
			//$mail->addTo ( 'meeting@hoabinh-group.com' );
			//$mail->addTo ( 'info@vietnamevents.com' );
			//$mail->addTo ( 'info@hoabinh-group.com' );
			//$mail->addCc($email);
			$mail->setSubject ( $name );
			
	
			
			$mail->send();
		
		//	echo $id;
		return true;
    }

    
   
}