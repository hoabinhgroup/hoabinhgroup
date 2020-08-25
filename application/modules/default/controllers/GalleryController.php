<?php
class GalleryController extends Louis_Controller_Action
   {
	    protected $_model_menu_items;
	   protected $_model_productImage;
	   
	   public function init()
	   {
		   parent::init();
		   $this->_model_menu_items = new Model_MenuItem();
		   $this->_model_productImage = new Model_ProductImage();
        $this->_helper->layout->setLayout('gallery');
        
		$this->_helper->translate($this->_lang);
    	}
    
      	public function indexAction()
      	{
	      	 $seo_modal = new New_Model_Seo();
	       $this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/gallery.css');
	      	$menu = $this->_model_menu_items->get_details(array('menu_id' => 8, 'parent' => 'yes', 'isHome' => 1, 'image' => 'Yes'));
	    
	      	$this->view->menus = $menu;
	      	
	
  
	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle('Gallery');
	
	      	
      	}
      	
      	public function categoryAction()
      	{
	      
	      	 $seo_modal = new New_Model_Seo();
	      	  $this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/gallery.css');
	      	$param = $this->_request->getParams();

	      	$ident = $param['ident'];
	
	      	
	      	$parent = $this->_model_menu_items->get_one_where(array('menu_id' => 8, 'link' => $ident, 'isHome' => 1));
	      	if(!$parent) $this->_redirect('404.html');
	      	$this->view->menu = $parent;
	      	$this->view->breadcrum = '<a href="/gallery/'.$parent->link.'">'.$parent->name.'</a>';
	     
	     if($ident == 'doi-ngu-nhan-vien'){
		$mdlMenuItems = new Model_ProductImage();
		$menu = $mdlMenuItems->get_details(
			array(
				'productId' => $parent->id
			)
		);
	    $this->view->menus = $menu;	
		$this->render('category2');
	}else{	
	     $menu = $this->_model_menu_items->get_details(array('menu_id' => 8, 'parent' => $parent->id,"order" => "m.position asc", 'isHome' => 1, 'image' => 'Yes'));
	   
	    }	 
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
    
    
    
      	}
      	
      	public function cateogry2Action()
      	{
	      	$this->_helper->viewRenderer->setNoRender(true);
	      	$this->_helper->layout->disableLayout();
	      	
	      	echo '<pre/>';
	      	print_r(23);
	      	echo '<pre/>';
      	}
		
		public function viewAction()
		{
		require_once(APPLICATION_PATH . '/../libraries/Mobile_Detect.php');
	
	
		// $this->view->headScript()->appendFile('/public/js/raxus-slider.min.js'); 
		  $this->view->headLink()->appendStylesheet('/public/photoswipe/photoswipe.css');
		  $this->view->headLink()->appendStylesheet('/public/photoswipe/default-skin/default-skin.css');
		//$this->view->headScript()->appendFile('/public/photoswipe/photoswipe.min.js'); 
		//$this->view->headScript()->appendFile('/public/photoswipe/photoswipe-ui-default.min.js'); 
		// $this->view->headScript()->appendFile('/public/js/photoswipe/photoswipe.min.js'); 
		// $this->view->headScript()->appendFile('/public/js/photoswipe/photoswipe-ui-default.min.js');
		 //$this->_helper->viewRenderer->setNoRender(true);
		//  $this->_helper->layout->disableLayout();
		  $param = $this->_request->getParams();
		  $ident = $param['ident'];
	     $parent = $this->_model_menu_items->get_one_where(array('menu_id' => 8, 'link' => $ident));
	      	
	      	$this->view->menu = $parent;
	     $this->view->breadcrum = $parent->name;

	      		
	     $image = $this->_model_productImage->get_details(array('productId' => $parent->id, 'content_type' => 'menu'));
	    
	     $this->view->menus = $image;
	     
	     $seo_modal = new New_Model_Seo();
	     
	     
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
		  
		    file:///home/admin/domains/hoabinh-group.com/public_html/public/js/photoswipe/photoswipe.css
		  $this->render('view-mobile');
		  
		  }
		}	

		public function leaderAction()
		{
		$this->_helper->layout->setLayout('leader');
		$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/review.css');
		$this->view->headLink()->appendStylesheet(TEMPLATE_URL. '/default/css/demo.css');
		$this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
	    $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
		$this->view->headScript()->appendFile(TEMPLATE_URL. '/default/js/leader.js');
		 

		 $options = array(
		 'menu_id' => 9,
		 'image' => 'Yes',
		 'isHome' => 'Yes',
		 'lang' => $this->_lang,
		 'order' => 'position asc',
		 );
		 
		 $this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle('Hình ảnh đội ngũ lãnh đạo');
	
    $this->view->headMeta()->setProperty('og:type', 'website'); 
		 
		$this->view->result = $result = $this->_model_menu_items->get_details($options);
		
		$this->_helper->translate($this->_lang);
		
		
		

		}
		
}
