<?php
class Louis_Controller_Action extends Zend_Controller_Action{
	
	protected $_lang;
	protected $_db;
	protected $_userID;
	protected $_currentUrl;
	protected $_currentPage;
/*
	public function preDispatch() {
		  $moduleName = $this->_request->getModuleName();
		  $controllerName  = $this->_request->getControllerName();
		  $actionName = $this->_request->getActionName();
       if($moduleName=='admin'){
          $auth = Zend_Auth::getInstance();
          if (!$auth->hasIdentity()) {

     if(($actionName != 'login') && ($actionName != 'render') && ($actionName != 'showinfouser'))
     		{
              $this->_helper->redirector('login', 'user', 'admin');
             }
              
         
          }
       }	
   }
   //co the chen template mo rong
   
   */
   public function init(){
	   $db = Zend_Db_Table::getDefaultAdapter();
	   $this->_db = $db;
	   $moduleName = $this->_request->getModuleName();
	   $controllerName = $this->_request->getControllerName();
	   $actionName = $this->_request->getActionName();
	   $routerName = $moduleName . '-'.$controllerName.'-'.$actionName;
	   
	  
	   	$identity = Zend_Auth::getInstance()->getIdentity();
	   	if($identity){
		  $this->_userID = $identity->id;  	
	   	}
	   	 
	   	$this->view->userID = $this->_userID;
	   	
	  
	   	
	   $ns = new Zend_Session_Namespace('language');
	   $this->_lang = $ns->lang;
	   if (empty($ns->lang)){
			 $this->_lang = 'vi';
			 }
	  $this->view->lang = $this->_lang;
			 
		$front = Zend_Controller_Front::getInstance(); 
	   	$this->_currentUrl = 'http://' .
	   	$front->getRequest()->getHttpHost() .
	   	$front->getRequest()->getRequestUri();		
	$this->view->currentUrl = $this->_currentUrl;
	$currentUrl = new Zend_Session_Namespace('currentUrl');
	   	if($actionName != 'filters'){
	   	$currentUrl->currentUrl = $this->_currentUrl;
	   	}
	$this->_currentPage = $front->getRequest()->getRequestUri();
			 
	$param_redirect =  $this->_request->getParam('redirect');
	if($param_redirect != null){
		echo $this->_redirect($param_redirect);
	}
	
	 
		   	$cd = $front->getControllerDirectory();
		   	$moduleNames = array_keys($cd);
		   	$getModuleErrorName = array_pop($moduleNames);
		   	$page = $this->getRequest()->getRequestUri();
		   //echo $routerName;
		   if(in_array($moduleName, $moduleNames)){
			   $browser  =$_SERVER['HTTP_USER_AGENT']; 
	   $ip  =  $_SERVER['REMOTE_ADDR'];  
	   $from_page =  $front->getRequest()->getServer('HTTP_REFERER');//  page from which visitor
	   
	   
	   $from_page = ($from_page)?$from_page:'';
	     	
	   try {
	       // Calling Zend_Loader::loadClass() with a non-existant class will cause
	       // an exception to be thrown in Zend_Loader:
	   
	   	$statTracker = new Model_StatTracker();
	    $statTracker->save(
	     	  array(
		   	'browser' => $browser,
		   	'ip' => $ip,
		   	'from_page' => $from_page,
		   	'page' => $page,
		   	'thedate_visited' => time()
		   			)
		   	);
		   	  
	   } catch (Zend_Exception $e) {
	       echo "Caught exception: " . get_class($e) . "\n";
	       echo "Message: " . $e->getMessage() . "\n";
	       // Other code to recover from the error
	   }
		   }
	   	
   }
   
   public function preDispatch() {
	  $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
	   Zend_Registry::set('uri', $uri);

	 
	   }

}