<?php
class TestimonialController extends Louis_Controller_Action
   {
      	public function init()
      	{
	      	parent::init();
	      	$this->_helper->layout->setLayout('new');
      	}
		
		public function viewAction()
		{
		//echo __METHOD__;
			$p = new Model_Testimonial();
			$result = $p->getlistProducts();
			
			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
			
			if (!isset($paramArray['page'])){
			$pageCurrent = 1;
			}else {
  			$pageCurrent = $paramArray['page'];
  		    }
			
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($result));
	 
	$paginator->setItemCountPerPage(5);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;

	 if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
    
		}	
		
		public function detailAction()
		{
			$param = $this->_request->getParams();
			
			$name = $param['ident'];


            $product = new Model_Testimonial();
            
            $data = $product->getDetailsByProduct($name);
            
            $this->view->assign($data);
            if($this->_lang == 'vi'){
	        $this->renderScript('testimonial/detail.phtml');  
            }else{
            $this->renderScript('testimonial/detail_en.phtml');
			}
		}
		
}
