<?php 
class OnlineController extends Louis_Controller_Action 
{ 
   
		public function init(){
			parent::init();
			
			$this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
	
		}
		
	function indexAction() {

        exit('bad ajax request');
       
    }
    
    function getAction() {
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
        $online = new Model_Online();
        $query = $online->getLattestVisits();
		
        return count($query);
    
    }
    
    function visitAction() {
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
    	$online = new Model_Online();
        $json = array('success' => 'false');
    
        $data = $online->getVisitorDatas();
       
        if($online->save($data)) {
            
            $json = array(
               'success' => 'true',
               'session_id' => session_id(),
               'visits' => $this->get()
            );
            
        }
        
        echo json_encode($json);
    
    }
   
}