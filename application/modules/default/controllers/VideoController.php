<?php
class VideoController extends Louis_Controller_Action
   {
	   protected $_model_menu_items;
	   protected $_model_productImage;
	   protected $_model_product;
	   
	   public function init()
	   {
		parent::init();
		$this->_model_menu_items = new Model_MenuItem();
		$this->_model_productImage = new Model_ProductImage();
		$this->_model_product = new Product_Model_Product();
        $this->_helper->layout->setLayout('gallery');
        
		$this->_helper->translate($this->_lang);
    	}
    
      	public function indexAction()
      	{
	$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/gallery.css');

	      	
	     $menu = $this->_model_menu_items->get_details(array('menu_id' => 10, 'parent' => 'yes', 'isHome' => 1, 'image' => 'Yes'));
	      	$this->view->menus = $menu;
	      	$this->view->doctype('XHTML1_RDFA');  // controller

		  	$this->view->headTitle('Video');
      	}
      	
      	public function categoryAction()
      	{
	      	require_once(APPLICATION_PATH . '/../libraries/Mobile_Detect.php');
	      	$this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.11.0.min.js');
	      	$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/gallery.css');
		  	$this->view->headLink()->appendStylesheet(TEMPLATE_URL. '/default/css/demo.css');	
		  	$this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
	    $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
	    $this->view->headLink()->appendStylesheet('/public/css/raxus.css');
		 $this->view->headScript()->appendFile('/public/js/raxus-slider.js');
		 
		 	$seo_modal = new New_Model_Seo();
	      	$param = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	      
	      	$ident = $param['ident'];
	      	$parent = $this->_model_menu_items->get_one_where(array('menu_id' => 10, 'link' => $ident, 'lang'));
	     
	      	$this->view->menu = $parent;
	    
	      
	      	$this->view->breadcrum = '<a href="/video/'.$parent->link.'">'.$parent->name.'</a>';
	      	
	      $menu = $this->_model_product->get_details(array('cat' => $parent->id,'status' => 1, 'lang'=> 'vi'));	
	   //  $menu = $this->_model_menu_items->get_details(array('menu_id' => 10, 'parent' => $parent->id,"order" => "m.position asc"));
	  
	    
	      	$this->view->menus = $menu;
	      	
	      	   
	 $seo = $seo_modal->get_one_where(array('id_object' => $parent->id, 'post_style' => 'menuitem'));
  	
  	$title = $parent->name;
	$keyword = '';
	$description = '';
  		
	 //seo
	if($seo != null){

	$title = ($seo->title != null)?$seo->title:$title;
	$keyword = ($seo->keyword != null)?$seo->keyword:$keyword;
	$description = ($seo->description != null)?$seo->description:$description;
	}
	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 
    
    $detect = new Mobile_Detect();
	    if ( $detect->isMobile() ) {
		  $this->render('category-mobile');
		  
		  }
      	}
		
		public function viewAction()
		{
			$this->view->headLink()->appendStylesheet(TEMPLATE_URL. '/default/css/gallery.css');
			  
		    //echo __METHOD__;
		   	$param = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		 $ident = $param['ident'];
	      	$parent = $this->_model_menu_items->get_one_where(array('menu_id' => 10, 'link' => $ident));
	      	
	      	
	     $this->view->breadcrum = $parent->id;

	      		
	     $image = $this->_model_productImage->get_details(array('productId' => $parent->id, 'content_type' => 'menu'));
	     
	     $this->view->menus = $image;
		}	

		
}
