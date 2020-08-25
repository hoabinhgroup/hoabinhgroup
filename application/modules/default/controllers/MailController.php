<?php
class MailController extends Louis_Controller_Action
   {
      
		
		public function indexAction()
		{
			$host = 'mail.louis-intelli.com';
			$config = array('auth' => 'login', 'username' => 'tuan@louis-intelli.com', 'password' => '123456');
			$transport = new Zend_Mail_Transport_Smtp($host, $config);
			$mail = new Zend_Mail();
			$mail->setFrom('tuan@louis-intelli.com', 'Tuan louis demo');
			$mail->addTo('louis.standbyme@gmail.com', 'Tuan louis gmail');
			$mail->addCc('hoabinhwebmaster@gmail.com', 'Tuan louis gmail');
			$mail->setSubject('This is a test');
			$mail->setBodyHtml('Tuấn Louis <br/> Tôi đã nhận được mail của bạn', 'utf-8');
			
			$mail->send($transport);
		}	
		
		
		public function gmailAction()
		{
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'iloveuforever1212@gmail.com', 'password' => 'imissyou1212', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			$mail->setBodyHtml('Tuấn Louis <br/> Tôi đã nhận được mail của bạn');
			$mail->addTo ( 'louis.standbyme@gmail.com' );
			$mail->addCc('hoabinhwebmaster@gmail.com', 'Tuan louis gmail');
			$mail->setSubject ( 'This is a test' );
			$mail->setFrom ( 'iloveuforever1212@gmail.com', 'Tuan louis demo');
			$mail->send ();
		}
		

		
}
