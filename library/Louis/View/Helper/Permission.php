<?php
class Louis_View_Helper_Permission extends Zend_View_Helper_Abstract{
        
       public function permission()
		 {
			 return $this;
		 }
		 
	 public function can_access($privilege)
	 {
		 $request = Zend_Controller_Front::getInstance()->getRequest();
		  $ns = new Zend_Session_Namespace('info');
		  $ns_info = $ns->getIterator();
		  $permission = $ns_info['group']['permission'];
		  $privileges = array();
		  if($permission != 'Full Access'){
			$privileges =  $ns_info['acl']['privileges']; 
		  }
		  
		  $moduleName = $request->getModuleName();  	
		 $controllerName  = $request->getControllerName();  
		  
		 if(($permission == 'Full Access') || in_array($moduleName . '_'.$controllerName . '_' . $privilege, $privileges)){
			 return true;
		 }
		  
	 }
	

    } 