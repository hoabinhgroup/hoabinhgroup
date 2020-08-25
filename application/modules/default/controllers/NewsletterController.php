<?php
class NewsletterController extends Louis_Controller_Action
   {
      
		
		public function indexAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$db = Zend_Db_Table::getDefaultAdapter();
			$email = $_REQUEST['EMAIL'];
			$date = time();
			
			if ($email != null){
				
				$insert = 'insert into newsletter (email, active, date) values ("'.$email.'", 0, "'.$date.'")';
				$db->query($insert);
				$lastId = $db->lastInsertId();
				
				
				 $html = new Zend_View();
      $html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
      // assign valeues
	  $html->assign('lastid', $lastId);

	  
	  $bodyText = $html->render('newsletter_template.phtml');
			
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'iloveuforever1212@gmail.com', 'password' => 'imissyou1212', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			$mail->setBodyHtml($bodyText);
			$mail->addTo ( 'louis.standbyme@gmail.com' );
			$mail->addCc($email, 'Newsletter '.$email);
			$mail->setSubject ( $name );
			$mail->setFrom ( 'Global Mice Travel Newsletter');
			$mail->send ();
			
			}
		}
		
		public function activationAction()
		{
		     $id = $this->_request->getParam('id');
		     $db = Zend_Db_Table::getDefaultAdapter();
		     if(!empty($id) and (int) $id ){
			     
			    $update = "update newsletter set active = 1 where id = $id"; 
			    $result = $db->query($update);
			    
			   if($result){
				   echo 'Succesfully activation Email Newsletter!';
			   }
                //$this->_redirect($url_back);
		     }
		}
		
}
