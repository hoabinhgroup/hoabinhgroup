<?php 
    class TranslateController extends Louis_Controller_Action
    {
        public function indexAction()
        {
	    // echo __METHOD__;
	    // $this->_helper->viewRenderer->setNoRender(true);
	     $this->_helper->layout->disableLayout();
	     
	     $english = array(
		     	  'chicken' => 'The chicken',
		     	  'children' => 'The children',
		     	  'teather' => 'The teather',
		     	  'now' => "Now is %1\$s and I'm using %2\$s language",
 	     );
	     
	     $options = array('adapter' => 'Array',
	     				  'content' => $english,
	     				  'locale' => 'en');
	     
	     $translate = new Zend_Translate($options);  
	     
	     /* them mang du lieu tieng viet */
	      $vietnam = array(
		     	  'chicken' => 'Con gà',
		     	  'children' => 'Những đứa trẻ',
		     	  'teather' => 'Giáo viên',
		     	  'now' => "Bây giờ là %1\$s và tôi đang sử dụng ngôn ngữ %2\$s",
 	     );
 	     
 	     $options = array('content' => $vietnam,
	     				  'locale' => 'vi');
 	     
 	     $translate->addTranslation($options);
	      
	     $translate->setLocale('vi');
	    // echo '<br/>'. $translate->_('now');
	    // printf($translate->_('now'), date('H:m:s'), 'tiếng Việt');
		 Zend_Registry::set('Zend_Translate', $translate);
         }
         
         
         public function tmxAction()
		 {
			 $this->_helper->layout->disableLayout();
			 
			 $ns = new Zend_Session_Namespace('language');
			 if (empty($ns->lang)){
				 $ns->lang = 'vi';
			 }
			 
			 $module = $this->_request->getModuleName();
			 $locale = $ns->lang;
			 $file = APPLICATION_PATH . '/languages/' .$module. '/' .$locale.'/lang.tmx'; 
			  $options = array('adapter' => 'Tmx',
	     				  'content' => $file,
	     				  'locale' => $locale);
	         $translate = new Zend_Translate($options);
	         Zend_Registry::set('Zend_Translate', $translate);
	         
         }
         
         
         public function filterAction()
         {
	         $this->_helper->viewRenderer->setNoRender(true);
	         $this->_helper->layout->disableLayout();
	         
	         $arrParam = $this->_request->getParams();
	         
	          $ns = new Zend_Session_Namespace('language');
	          
	           if (!empty($arrParam['change'])){
				 $ns->lang = $arrParam['change'];
				//$locale = new Zend_Locale($arrParam['change']);
				//Zend_Registry::set('Zend_Locale', $locale);
			 }
			 	    if($ns->lang == 'en'){	 
			 
				 	$this->_redirect('/en');
				 	}	else{
						$this->_redirect('/'); 	
				 	}
         }
         
         
          public function filtersAction()
         {
	       $this->_helper->viewRenderer->setNoRender(true);
	         $this->_helper->layout->disableLayout();
	         
	         $arrParam = $this->_request->getParams();
	         
	          $ns = new Zend_Session_Namespace('language');
	          $current = new Zend_Session_Namespace('currentUrl');
	          $current = $current->getIterator();
	           if (!empty($arrParam['change'])){
				 $ns->lang = $arrParam['change'];
				 }
				 

	        $this->_redirect($current['currentUrl']);
	   
         }
    }