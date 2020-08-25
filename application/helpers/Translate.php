<?php
class Zend_Controller_Action_Helper_Translate extends
Zend_Controller_Action_Helper_Abstract
{
	/*
	public function direct($moduleName, $lang) {

  	 //$module = $this->_request->getModuleName();
  	 $module = $moduleName;
		 $locale = $lang;
			 $file = APPLICATION_PATH . '/languages/' .$module. '/' .$locale.'/lang.tmx'; 
			  $options = array('adapter' => 'Tmx',
	     				  'content' => $file,
	     				  'locale' => $locale);
	         $translate = new Zend_Translate($options);
	         Zend_Registry::set('Zend_Translate', $translate);

	}
	*/
	
	public function direct($lang) {

			 $translate = new Zend_Translate('Array', 
                    APPLICATION_PATH . "/languages/vi.php", 
                    'vi');
                    
		$translate->addTranslation(APPLICATION_PATH . '/languages/en.php', 'en');
		$translate->setLocale($lang);
		Zend_Registry::set('Zend_Translate', $translate);

	}

}