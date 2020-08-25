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
        
        $this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/gallery.css');
    	}
    
      	public function indexAction()
      	{
	      
	      	$this->view->headTitle("Chuyên mục hình ảnh HoabinhGroup");
	      	$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();

		  	$options= array(
			  	'menu_id' => 8,
			  	'parent' => 'Yes',
			  	'isHome' => 'Yes',
		  	);
	      $result = $this->_model_menu_items->get_details($options);
	      echo "<pre>";
	      print_r($result);
	      echo "</pre>";	
      	}
		
		public function viewAction()
		{
		    //echo __METHOD__;
		    $paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		    
		    $this->view->doctype();
 $this->view->headTitle("Gallery Hoabinh Group");
  $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
 $this->view->headMeta()->appendName("keywords","Hoabinhgroup, Công ty tổ chức sự kiện, Sự kiện truyền thông Việt Nam, tổ chức hội nghị, tổ chức hội thảo, du lịch MICE, du lich MICE, to chuc hoi thao, tour du lich"); 
  $this->view->headMeta()->offsetSetName("2","description","Tập đoàn ​HoaBinh Group (Convention – Events – Travel) đã khẳng định uy tín của mình là một trong những tập đoàn hàng đầu trong lĩnh vực tổ chức Hội nghị, hội thảo, sự kiện và du lịch tại Việt Nam.");

		   
		    
		    $ident = $paramArray['ident'];
		    
		    $menu = new Model_MenuItem();
	      	$gallery = $menu->getGalleryByCat($ident);
	      	
	      	$this->view->gallery = $gallery;
		}	

		
}
