<?php 
class AdminIndexController extends Louis_Controller_Action 
{ 
   
		public function init(){
			parent::init();
			$this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
		 $this->view->headTitle("Louis Intelli Cms"); // chay layout toan cuc, set them application.ini
      //  $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
  
	  		// thong tin co ban trong zend view
	  	$baseurl=$this->_request->getbaseurl();
  
  			$this->view->doctype();
  			$this->view->headTitle("Chao mung den voi louis");
  			$this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
  		//	$this->view->headMeta()->appendName("keyword","Zend Framework,Codeigniter,PHP Framework"); 
  			//$this->view->headMeta()->offsetSetName("2","description","");
		}
		
	public function indexAction(){ 
		  $this->view->headScript()->appendFile('/public/js/flot/jquery.flot.min.js'); 
		  $this->view->headScript()->appendFile('/public/js/flot/jquery.flot.resize.min.js'); 
		  $this->view->headScript()->appendFile('/public/js/flot/jquery.flot.pie.min.js'); 
		  $this->view->headScript()->appendFile('/public/js/flot/jquery.flot.stack.min.js'); 
		 $this->view->headLink()->appendStylesheet('/public/js/flot/graph.css');	
    
      }
    
    public function testAction()
    {
	    $this->_helper->viewRenderer->setNoRender(true);
	     $this->_helper->layout->disableLayout();
	     
	     $translate = new Zend_Translate('Array', 
                    APPLICATION_PATH . "/languages/vi.php", 
                    'vi');
                    
		$translate->addTranslation(APPLICATION_PATH . '/languages/en.php', 'en');
		$translate->setLocale('vi');
		Zend_Registry::set('Zend_Translate', $translate);
		
		echo $translate->_('Home');
	     
    }
    
    public function updAction()
    {
	    $this->_helper->viewRenderer->setNoRender(true);
	     $this->_helper->layout->disableLayout();
	     
	  /*   
	     
	     $image = new Model_ProductImage();
	     
	     $conditions = array('content_type' => 'menu');
	     $where = array('productId' =>  239);
	     $image->update_where($conditions, $where);
	     
	     */
	     echo 122;
    }
    
    public function loadAction()
    {
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();

	    if($this->getRequest()->isXmlHttpRequest())
	    		{
				
	    	$now = time();
			$day = date("d",$now);
			$last7days = 7;
			$resultLast7dayViews = array();
			
			for($stepDay= 0; $stepDay< $last7days; $stepDay++){
	    	$getViewVisits = $this->_db->query("
	    	 SELECT thedate_visited AS viewDays, DAY( FROM_UNIXTIME( thedate_visited ) ) as period,
	    	 COUNT(*) AS total 
			 FROM statTracker 
			 WHERE DAY(FROM_UNIXTIME(thedate_visited)) = ? 
			 GROUP BY period", 
			 array($day - $stepDay));		

			$result = $getViewVisits->fetchAll();
				if(count($result) > 0)
				{
					$time = $result[0]['viewDays'] * 1000;
					$views = $result[0]['total'];
					$resultLast7dayViews[] = array($time, $views);
				}
			}
			$resultLast7dayVisits = $resultLast7dayViews;
			$colorViews = '#71c73e';
			$colorVisits = '#77b7c5';
			$this->_helper->json(
			array(
				array(
					'data' => $resultLast7dayViews, 
					'color' => $colorViews
				),
				array(
					'data' => $resultLast7dayVisits, 
					'color' => $colorVisits,
					'points' => array(
							'radius' => 4,
							'fillColor' => '#77b7c5',
							),
					)
				)
				);
		
	    		}
    		}
}