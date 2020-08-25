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

			$dayTrackers = 7;
			$resultDayTrackerViews = array();
			$resultDayTrackerVisits = array();
			$totalVisits = 0;
			$totalViews= 0;
			$totalBounceRate = 0;
			$colorViews = '#71c73e';
			$colorVisits = '#77b7c5';
			$statTracker =  new Model_StatTracker();
			
			for($stepDay= 0; $stepDay< $dayTrackers; $stepDay++){		

			$resultGetViews = $statTracker->getViews($stepDay);
			
					
			$resultGetVisits = $statTracker->getVisits($stepDay);
				
			$resultGetBounceRate = $statTracker->getBounceRate($stepDay);
	
				if(count($resultGetViews) > 0)
				{
					$timeViews = $resultGetViews[0]['viewDays'] * 1000;
					$views = $resultGetViews[0]['total'];
					$resultDayTrackerViews[] = array($timeViews, $views);
					$totalViews = $totalViews + $views;
				}
				
				if(!empty($resultGetVisits) && ($resultGetVisits[0]['visitDays'] != 0))
				{
					
					$timeVisits = $resultGetVisits[0]['visitDays'] * 1000;
					$visits = $resultGetVisits[0]['ip'];
					$resultDayTrackerVisits[] = array($timeVisits, $visits);
					$totalVisits = $totalVisits + $visits;
				}
				
				$totalBounceRate = $totalBounceRate + count($resultGetBounceRate);
				$getBounceRatePercent = round(($totalBounceRate/$totalVisits) * 100);
			}
		
							
			
			$chart = array(
				array(
					'data' => $resultDayTrackerViews, 
					'color' => $colorViews
				),
				array(
					'data' => $resultDayTrackerVisits, 
					'color' => $colorVisits,
					'points' => array(
							'radius' => 4,
							'fillColor' => '#77b7c5',
							),
					)
				);
			$totalVisits = $totalVisits;
			
			$this->_helper->json(
			array('chart' => $chart,'visits' => $totalVisits,'views'=> $totalViews,'bounceRate' => $getBounceRatePercent)
				);
		
	    		}
    		}
    		
    public function responseAction()
    {
	    
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
	
		if($this->getRequest()->isXmlHttpRequest())
	       {
		   	$statTracker = new Model_StatTracker();
		    $page = $this->_request->getPost('page', 1);  
		    $type = $this->_request->getPost('type', 'trackByPage');  
			
	 
		   	try {  
		   		         	   	    
		   	
		   	$result = $statTracker->trackBy($type);
		  
		       } catch (Zend_Exception $e) {
		       echo "Caught exception: " . get_class($e) . "\n";
		       echo "Message: " . $e->getMessage() . "\n";
		   	echo "Error code: " . $e->getCode() . "\n";
		   	echo "File name: " . $e->getFile() . "\n";
		   	echo "Line: " . $e->getLine() . "\n";
		   	echo "Backtrace: " . $e->getTraceAsString() . "\n";
		      
		   }
		   
		
		   $count = count($result);
		   $limit = 10;   
		   
	      $config = array(
    'current_page'  => isset($page) ? $page : 1,
    'total_record'  => $count, // tổng số
    'limit'         => $limit,
    'link_full'     => '/admin/{page}',
    'link_first'    => '/admin',
    'range'         => 1,
    'start'         => $limit * $page,
    'next'         => '>',
    'prev'         => '<',
    'first'         => "<<",
    'last'         => '>>',
);

		$paging = new Louis_Pagination();
		$paging->init($config);
 
		// Lấy limit, start
		$per_page = $paging->get_config('limit');
		$start = $paging->get_config('start');
		$offset = $per_page * ($page - 1);
		
		$result_after = $statTracker->trackBy($type, $per_page, $offset);
			
  			echo json_encode(array(
        'data' => $result_after,
        'paging' => $paging->html(),
        'count' => $count,
        'start' => $start
		));
   	}
	
    }
    
    
   
}