<?php
class User_AdminUserController extends Louis_Controller_Action
{
	protected $_user_model;
	
    public function init()
    {
	   parent::init();
     $this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
     $this->_user_model = new User_Model_User();
    }
    
    
    public function showinfouserAction()
	{
	
		try {  
		         
    $auth = Zend_Auth::getInstance();
    if($auth->hasIdentity()) {
        $this->view->identity = $auth->getIdentity();
		}
	    
	       } catch (Zend_Exception $e) {
    echo "Caught exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
   
}
	}
	
	function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
	}

	public function loginAction()
	{
	    // set layout cho trang login
		 $option=array(
        "layout" => "login",
        "layoutPath" => TEMPLATE_PATH ."/admin/layouts"
          ); 
        Zend_Layout::startMvc($option);
   
    $userForm = new User_Form_User();
    $userForm->setAction('/admin/user/login');
    $userForm->removeElement('first_name');
    $userForm->removeElement('last_name');
    $userForm->removeElement('group_id');
        try {
         
	if ($this->getRequest()->isPost() && $userForm->isValid($_POST)) {
        $data = $userForm->getValues();
    
        $db = Zend_Db_Table::getDefaultAdapter();
      
        $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users',
            'username', 'password');
       
        $authAdapter->setIdentity($data['username']);
        $authAdapter->setCredential(md5($data['password']));
      
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
          
            $auth = Zend_Auth::getInstance();
            //luu dada vao session
            $storage = $auth->getStorage(); 
            $storage->write($authAdapter->getResultRowObject(
                array('id','username' , 'first_name' , 'last_name', 'group_id')));
           // $storage->write(v);
         //  $akey = new stdClass();
         $user = new User_Model_User();
           $key_sercurity = md5($data['password'].$user->randomString());
            $_SESSION['akey'] = $key_sercurity;
            
             $info = new Louis_System_Info();
			 $info->createInfo();
			
			
		    $identity = $auth->getIdentity()->id;
			 $this->_user_model->update_where(array('last_online' => time()), array('id' => $identity));
			 

			 
			 
            return $this->_redirect('/admin');     
              
            
             } else {
	        $stream = @fopen(APPLICATION_PATH. '/logs/logtext.txt', 'a', false);
			if (! $stream) {
			throw new Exception('Failed to open stream');
			}     
	             
	        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH. '/logs/logtext.txt'); 
	        $logger = new Zend_Log($writer);
	        $logger->info($data['username'].'-'.$data['password'].'-'.$this->get_client_ip());    
	             
            $this->view->loginMessage = "Xin lỗi, username hoặc mật khẩu không chính xác!";
				}	
				
				
				 }
				 
		  // Zend_Loader::loadClass('nonexistantclass');
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
            echo "Message: " . $e->getMessage() . "\n";
          
        }	
        
        	 
    $this->view->form = $userForm;
}


	public function logoutAction()
	{
    $authAdapter = Zend_Auth::getInstance();
    // clear last_online
/*    $identity = $authAdapter->getIdentity()->id;
		$this->_user_model->update_where(array('last_online' => ''), array('id' => $identity));
		*/
    $authAdapter->clearIdentity();
		 
     $info = new Louis_System_Info();
	 $info->destroyInfo();
     $this->_helper->redirector('login', 'user', 'admin');
	}


    
   public function createAction()
   	{     
   	      
    $userForm = new User_Form_User();
    if ($this->getRequest()->isPost()) {
    
        if ($userForm->isValid($_POST)) {
        
            $userModel = new User_Model_User();
            $userModel->createUser(
                $userForm->getValue('username'),
                $userForm->getValue('password'),
                $userForm->getValue('first_name'),
                $userForm->getValue('last_name'),
                $userForm->getValue('group_id')
            );
           
           return $this->_forward('list');  
              
              }
             
             // echo 21;
    }
    	
    	 //	  Zend_Loader::loadClass('nonexistantclass');
      
   	      
    $userForm->setAction('/admin/user/create');
    $this->view->form = $userForm;
    	   
	}
	
	public function listAction ()
	{
    $currentUsers = User_Model_User::getUsers();
    if ($currentUsers->count() > 0) {
        $this->view->users = $currentUsers;
    } else {
        $this->view->users = null;
      }
	 }
	 
	 public function groupAction()
	 {
		 $result = User_Model_Group::get_details();
		 
		 $this->view->result = $result;
		 
	 }
	 
	 public function groupcreateAction()
	 {
		 $groupForm = new User_Form_Group();
    if ($this->getRequest()->isPost()) {
    
        if ($groupForm->isValid($_POST)) {
        
            $groupModel = new User_Model_Group();
            $groupModel->createGroup(
                $groupForm->getValue('title'),
                $groupForm->getValue('permission')
            );
           
          // return $this->_forward('group');  
             return $this->_redirect('/admin/new/group_create');  
              }
             
             // echo 21;
    }
    	
    	 //	  Zend_Loader::loadClass('nonexistantclass');
      
   	      
    $groupForm->setAction('/admin/user/group_create');
    $this->view->form = $groupForm;
	 }
	 
	 
	 public function updateAction ()
	 {
	 $userForm = new User_Form_User();
	 $userForm->setAction('/admin/user/update');
	 $userForm->removeElement('password');
	 $userModel = new User_Model_User();
	 if ($this->getRequest()->isPost()){
		 if($userForm->isValid($_POST)){
			 $userModel->updateUser(
                $userForm->getValue('id'),
                $userForm->getValue('username'),
                $userForm->getValue('first_name'),
                $userForm->getValue('last_name'),
                $userForm->getValue('group_id')
                
				);
				
				return $this->_forward('list'); 
					}
			}else{
				$id = $this->_request->getParam('id');
				$currentUser = $userModel->find($id)->current();
				$userForm->populate($currentUser->toArray());
				
			}
			
	$this->view->form = $userForm;
	 }
	 
	 	public function passwordAction()
		{
    $passwordForm = new User_Form_User();
    $passwordForm->setAction('/admin/user/password');
    $passwordForm->removeElement('first_name');
    $passwordForm->removeElement('last_name');
    $passwordForm->removeElement('username');
    $passwordForm->removeElement('role');
    $userModel = new User_Model_User();
    if ($this->_request->isPost()) {
        if ($passwordForm->isValid($_POST)) {
            $userModel->updatePassword(
                $passwordForm->getValue('id'),
                $passwordForm->getValue('password')
				);
            return $this->_forward('list');
        }
    } else {
        $id = $this->_request->getParam('id');	 
		$currentUser = $userModel->find($id)->current();
        $passwordForm->populate($currentUser->toArray());
		}
    $this->view->form = $passwordForm;
		}
		
		public function profileAction()
		{
		
		$this->view->headLink()->setStylesheet('/public/assets/css/bootstrap-editable.css');
		$this->view->headScript()->appendFile('/public/assets/js/fuelux/fuelux.spinner.js');
		$this->view->headScript()->appendFile('/public/assets/js/x-editable/bootstrap-editable.js');
		$this->view->headScript()->appendFile('/public/assets/js/x-editable/ace-editable.js');
		$this->view->headScript()->appendFile('/public/assets/js/date-time/moment.js');
		$this->view->headScript()->appendFile('/public/assets/js/livestamp.js');		
		$this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');			$this->view->headLink()->appendStylesheet('/public/css/slim.min.css');

		$identity = Zend_Auth::getInstance()->getIdentity();
		$this->view->identity = $identity;	
		$userid = $identity->id;
		$user =  $this->_user_model->get_one(array('id' => $userid));
		$this->view->assign($user->toArray());
		
		}
		
		public function deleteAction()
		{	
		$id = $this->_request->getParam('id');
		$this->_user_model->deleteUser($id);
		return $this->_forward('list');
		}
		
		public function editAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			 $this->_helper->layout->disableLayout();
			 
			if ($this->_request->isXmlHttpRequest()) {
				$userModel = new User_Model_User();
				 $username = $this->getRequest()->getPost('name');
				 $value = $this->getRequest()->getPost('value');
				 $pk = $this->getRequest()->getPost('pk');
				/*
					[name] => username
					[value] =>  louis343
					[pk] => 3*/
			if($username == 'joined'){
				$exp = explode('/', $value);
				$format = $exp[1].'/'.$exp[0].'/'.$exp[2];
				$value = strtotime($format);
			}elseif($username == 'password'){
				$value = md5($value);
			}
				$userModel->update_where(array($username => trim($value)), array('id' => $pk));
				
       
				}
		}
		
		public function fetchAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			 $this->_helper->layout->disableLayout();
			 
			 $this->_helper->json(array(
				 'file' => 'http://img.f30.vnecdn.net/2017/03/31/IMG5979-1490931574_490x294.jpg',
				 'path' => 'http://img.f30.vnecdn.net/2017/03/31/IMG5979-1490931574_490x294.jpg',
			 ));
		}
		
		public function uploadAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			if ($this->_request->isXmlHttpRequest()) {
				
			$slimObj = json_decode($_POST['slim'][0], true);  
			
			$name = $slimObj['output']['name'];	 
			$image = $slimObj['output']['image'];	 
			
	 $uploadDir = $_SERVER['DOCUMENT_ROOT'] . PATH_PROFILES . '/';
     
	 $img = str_replace('data:image/jpeg;base64,', '', $image);
	 $img = str_replace(' ', '+', $img);
	 $data = base64_decode($img);
	 $file = $uploadDir . $name;
	 $success = file_put_contents($file, $data);
	 
	 $user_id = Zend_Auth::getInstance()->getIdentity()->id;
	 
	 $this->_user_model->update_where(array('avatar' => $name ), array('id' => $user_id));
	
	 	}
			
		}
		
			public function delAction()
		{
		
			  $this->_helper->viewRenderer->setNoRender(true);
			  $this->_helper->layout->disableLayout();
			  	if ($this->_request->isXmlHttpRequest()) 
			  	{
			  $name = $this->getRequest()->getPost('name');
			  
						   
		 $link = $_SERVER['DOCUMENT_ROOT']. PATH_PROFILES . '/' . $name;
			
		 	unlink($link);
	 $user_id = Zend_Auth::getInstance()->getIdentity()->id;	 	
	 $this->_user_model->update_where(array('avatar' => '' ), array('id' => $user_id));
		 
		 echo 1;
			 }
	 			}
   
 }