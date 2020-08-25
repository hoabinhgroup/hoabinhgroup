<?php   
    class SurveyController extends Louis_Controller_Action
    {
        public function indexAction()
        {
	    $this->_helper->layout->setLayout('new');
	       //$this->_helper->layout->disableLayout();
          $param = $this->_request->getParams();  
          
          $value = $this->_request->getParam('index', true);  
       
		  $this->view->value = $value;
                
		}
    }