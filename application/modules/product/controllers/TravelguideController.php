<?php 
class Product_TravelguideController extends Louis_Controller_Action
{ 
		protected $_form_model;
		protected $_product_model;
		protected $_tour_model;
		protected $_travel_guide_model;
		 
		public function init()
		{
			parent::init();
			$this->_form_model = new Product_Form_Product();
			$this->_product_model = new Product_Model_Product();
			$this->_tour_model = new Product_Model_ProductTour();
			$this->_travel_guide_model = new Product_Model_ProductTravelGuide();
		}

		public function indexAction()
       {
	     
	     // $this->getHelper(redirector)->gotoSimple('index', 'index', 'default');
	      
	      /*$this->getHelper(redirector)->gotoUrl('/index/index');
        //or use gotoRoute()
        $this->getHelper(redirector)->gotoRoute('action'=>'index', 'controller'=>'index', 'module' => 'default');
        */
         $this->_helper->layout->setLayout('travel_guide');
         $param = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		 if (isset($param['ident'])){
			 $this->view->ident = $param['ident']; 
			 }
		
         
          if (!isset($param['page'])){
			$pageCurrent = 1;
			}else{
			$pageCurrent = $param['page'];
			}
			
		$data = $this->_travel_guide_model->getProducts();
		
		$this->view->featured = $data_featured = $this->_travel_guide_model->getProducts(1);
		 	
				//phan trang
	 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	 
	$paginator->setItemCountPerPage(20);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->data = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
      
        
     
       }
       
       public function detailAction()
       {
	       $this->_helper->layout->setLayout('travel_guide_detail');
	       $param = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	 
	      $data = $this->_travel_guide_model->get_one_custom(array('ident' => $param['ident'],'status' => 1));
	      
	      if($data)
	      {
		     $this->view->locations = $data['location'];
		  // $data = $data->toArray(); 
		   $this->view->assign($data);
	      }else {
		   $this->_helper->viewRenderer->setNoRender(true);
		     
		   $this->view->message = 'Bài viết không tồn tại';
		   
	      }
	      
	      //other
	      $cats = $this->_product_model->getCatsFromId($data['id']);

		  $arr_cats = array();
		  foreach($cats as $k=>$v):
		  $arr_cats[] = $v['category_id'];
		  endforeach;
		  $this->view->cats  = $arr_cats;
		  $cats_strings = implode(',',$arr_cats);
		  $menu = new Model_MenuItem();
		  $this->view->menu = $menu->getMenuNameFromListId($cats_strings);
           
		$products = $this->_product_model->getOtherTravelGuideFromCats($cats_strings, $data['id']);
	
		$this->view->other_products = $products;

	      
       }
       
       public function categoryAction()
       {
	      $this->_helper->layout->setLayout('travel_guide');
	      
	      $param = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		  
		  $this->view->ident = $ident = $param['ident'];
		  
		  $menu = new Model_MenuItem();
		  $name_menu  = $menu->get_one_where(array('link' => $ident));
		  $this->view->obj_menu = $name_menu;
         
          if (!isset($param['page'])){
			$pageCurrent = 1;
			}else{
			$pageCurrent = $param['page'];
			}
		$data = array();	
		$this->view->data = $data = $this->_travel_guide_model->getProductsByCategory($param['ident']);
		if($data){
		$locations = array();
		foreach($data as $key=>$val):
		$locations[] = $val['location'];
		endforeach;
		$this->view->locations = join(',',$locations);
		$this->view->featured = $data_featured = $this->_travel_guide_model->getProducts(1);
		 	
				//phan trang
	 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	 
	$paginator->setItemCountPerPage(20);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->data = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
      
			}
	    } 
}
		