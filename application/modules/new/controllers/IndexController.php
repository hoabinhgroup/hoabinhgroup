<?php 
class New_IndexController extends Louis_Controller_Action
{ 
		public function init(){			
			 parent::init();
			
			 $this->_helper->layout->setLayout('new');
			$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/news.css');
			//  $this->view->headScript()->appendFile('/public/templates/news2017/default/js/scroll-effects.js');
		}
		
		public function indexAction()
			{
	      
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.carousel.min.css');
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.theme.default.min.css');
	    
	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/owl.carousel.min.js');
	    $this->view->headScript()->appendFile('/public/templates/news2017/default/js/custom-rec.js');
	    $this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
	    $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
	    $this->view->headScript()->appendFile('/rating/js/jquery.5stars.min.js');

		$this->_helper->translate($this->_lang);
	      $front = Zend_Controller_Front::getInstance(); 
	    
         $paramArray = $this->_request->getParams();
      
        
	     $this->view->lang = $lang = $paramArray['lang'];
         $news = new New_Model_Product();
         $seo_modal = new New_Model_Seo();
		 $list = array();
		 
		
		 
		 if($lang == 'vi'){
			 $new = 'tin-tuc';
		 }else{
			  $new = 'news';
		 }
		 
		  if(!isset($paramArray['ident'])){
			// $link = $new;
			$this->_redirect('404.html');
		 }else{
			 $link = $this->view->link = $paramArray['ident'];
		 }
		 
		
		  if($lang != $this->_lang){
	       $this->_forward('filters','translate','default', array('change' => $lang));
        	}
		
		 
		 
		 $pageCurrent = $paramArray['page'];
         if (!isset($paramArray['ident']) ||  (int) $paramArray['ident']){
	  
		
  			$options = array(
	  			//'image' => 'yes',
	  			'lang' => $lang,
	  			'content_type' => 'new',
	  			'order' => 'p.date desc',
	  			'status' => 1,
  			);
  		
		
			}else {
  			
  			$options = array(
	  			//'image' => 'yes',
	  			'lang' => $lang,
	  			'content_type' => 'new',
	  			'order' => 'p.date desc',
	  			'status' => 1,
	  			'link' => $link
  			);
  		
  		
  		
  			$options2 = array(
	  			'image' => 'yes',
	  			'lang' => $lang,
	  			'content_type' => 'new',
	  			'order' => 'p.views desc',
	  			//'order' => 'p.date desc',
	  			'status' => 1,
	  			'limit' => 5,
	  			'link' => $link
  			);
  			
  			
  			
  		
  		    }
  		    
  		    
  		    	
  			 if(isset($paramArray['q'])){
	         $options['search'] = $paramArray['q'];
	         
  			 }
  			
  			$list = $news->get_details($options);
  			
  			
  			 $beInterested = $news->getBeInterested();
  			 $this->view->beInterested = $beInterested;
  			
  		
  			
  		    $menu_obj = new Model_MenuItem();
  		    $menu_content = $menu_obj->get_one_where(array('link' => $link, 'lang' => $lang));
  		
  		if($menu_content){ 
  		$this->view->menu_content = $menu_content;
  		
  	$seo = $seo_modal->get_one_where(array('id_object' => $menu_content->id, 'post_style' => 'menuitem'));
  	
  	$title = $menu_content->name;
	$keyword = '';
	$description = '';
	
	
  		
	
	 //seo
	if($seo != null){

	$title = ($seo->title != null)?$seo->title:$title;
	$keyword = ($seo->keyword != null)?$seo->keyword:$keyword;
	$description = ($seo->description != null)?$seo->description:$description;
	}
	
	$this->view->doctype('XHTML1_RDFA');  // controller
	$trang = '';
	$trang.= ' - Trang '. $paramArray['page'];
	if($pageCurrent > 1){
		$title .= $trang;
		
		if($keyword != ''){
			$keyword.= $trang;
		}
		
		if($description != ''){
			$description.= $trang;
		}
		
		}

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'. $trang); 
  		
  /*			
  		 $this->view->doctype('XHTML1_RDFA');  // controller
  	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keywords",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
  
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', strip_tags($description)); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 
    */
  		    
  //	if($list == null){
	  //	throw new Zend_Controller_Action_Exception('File not found', 404);	  	
  //	}
  	}else{
	  	$this->_redirect('404.shtml');
	  	//throw new Zend_Controller_Action_Exception('File not found', 404);
  	}   
  		if($list){    
  		//phan trang
	 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($list));
	 
	$paginator->setItemCountPerPage(10);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	
	$pagecount =  $paginator->getPages()->pageCount;


     if ($pageCurrent > $pagecount){
	    // $this->_redirect('404.shtml');
	  // throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
      
      
    
    //lang
	  
	   $this->_helper->translate($this->_lang);
	   }
	   
	   
	   	$mostViews = $news->get_details($options2);
  			
	   	$this->view->mostViews = $mostViews;
  			
	   $menuObj = new Model_MenuItem();
      
    //  $mdlMenuItem = new Model_MenuItem();
	  $menu = $menuObj->getItemsByMenu(2, $this->_lang);

	  $parent = $menuObj->get_one_where(array('link' => $new))->id;
	
	  $categories = array();
	  foreach($menu->toArray() as $key =>$val):
	  if($val['parent'] == $parent){
	  $categories[] = $val;
	  }
	  endforeach;

	 //  $recursive = new Louis_System_RecursiveMenu($menu->toArray());
	   $this->view->categories = $categories;
       }

       
    public function viewAction()
		{
			   $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.carousel.min.css');
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.theme.default.min.css');
	    $this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
	    $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/owl.carousel.min.js');
			$this->view->headScript()->appendFile('/public/templates/news2017/default/js/custom-rec.js');
		 $front = Zend_Controller_Front::getInstance(); 	
			//echo __METHOD__;
			$paramArray = $this->_request->getParams();

	//echo $paramArray['lang'];
	 		 
	$current = $this->view->current = $this->_currentUrl;
     
    $name = $paramArray['ident'];

     $product = new New_Model_Product();
     $seo_modal = new New_Model_Seo();
     
    $beInterested = $product->getBeInterested();
  	$this->view->beInterested = $beInterested;
    
     
    $this->view->maxcatid = $catid = $product->getMaxID($name);
    $data = $product->get_one_where(array('ident' => $name, 'lang' => $paramArray['lang'], 'status' => 1));
   
    
	// $data = $product->getDetailsByProduct($name);
	 if($data){
		 $data = $data->toArray();
	 $this->view->lang = $this->_lang;
	 
	  if($paramArray['lang'] != $this->_lang){
	       $this->_forward('filters','translate','default', array('change' => $paramArray['lang']));
        	}
	    
	 $this->view->assign($data);
	 
	 
	 // Tăng lượt view
	 $update_view = $product->update_where(array('views' => new Zend_Db_Expr('`views` + 1')), array('id' => $data['id']));
	 
	 // same tags
	 if($data['tags'] != null){
		 	
	 $ptags = array();
	$str_tags = explode(',',$data['tags']);
	$stt= 0;
	foreach($str_tags as $vtag): $stt++;
	 $ident = Zend_Controller_Action_HelperBroker::getStaticHelper('string')->direct($vtag,'-');
	
	$ptags[] = $product->getProductsByTag($ident, $data['id'], 5);

	endforeach;
	$this->view->same_tags = $ptags[0];
	
	 }
	
	
	 }else{

		//$this->_response->clearBody();
		//$this->_response->clearHeaders();
		//$this->_response->setHttpResponseCode(404);
		 $this->_redirect('404.html');
	 }
	 
	$menu = new Model_MenuItem();
	
  	 $menu_content = $menu->get_one_where(array('id' => $catid, 'lang' => $this->_lang));
  		$this->view->menu_content = $menu_content;
  		
  		//categories
  	 $menuObj = $menu->getItemsByMenu(2, $this->_lang);
  	 

	 
	  $categories = array();
	  foreach($menuObj->toArray() as $key =>$val):
	  if($val['parent'] == 7){
	  $categories[] = $val;
	  }
	  endforeach;

	   $this->view->categories = $categories;
	   
	   // bài viết quan tâm, tin moi nhat chuyen muc
	   $options = array(
	  			'image' => 'yes',
	  			'lang' => $this->_lang,
	  			'content_type' => 'new',
	  			//'order' => 'p.views desc',
	  			'order' => 'p.date desc',
	  			'status' => 1,
	  			'limit' => 3,
	  			'link' => $menu_content->link
  			);
  			
  			$mostViews = $product->get_details($options);
  			
  			$this->view->mostViews = $mostViews;
  		
    
//lang
	  //$module = $this->_request->getModuleName();
	   $this->_helper->translate($this->_lang);
	
	$seo = $seo_modal->getSeoFromId($data['id']);
	
	
	
	 //seo
	if($seo != null){
	$title = ($seo->title != null)?$seo->title:$data['name'];
	$keyword = ($seo->keyword != null)?$seo->keyword:$data['tags'];
	$description = ($seo->description != null)?$seo->description:strip_tags($data['shortDescription']);
	}else{
	$title = $data['name'];
	$keyword = $data['tags'];
	$description = strip_tags($data['shortDescription']);
	}
	
	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 

	 
	 $images = new New_Model_ProductImage();
	 
	//echo $data['id'];
	 $this->view->imagesProduct = $imgProduct = $images->getImagesProduct($data['id']);

	 $arr = array();
	 if ($imgProduct != null){
	foreach($imgProduct as $k=>$v):
	  if ($v['isDefault'] == 'Yes'){
		 $arr[] =  $v['full'];
	  }
	
	endforeach;
	
	if(!empty($arr)){
		$imagefb = $arr[0];
		
		$domain = $this->getRequest()->getHttpHost();
	$urlfb =  'http://'.$domain.'/public/images/news/'.$data['id'].'/'.rawurlencode($imagefb);
	
	  $this->view->headMeta()->setProperty('og:image', $urlfb); 
	}
	

      
	
	  }
	  /*
	 $this->view->productId = $data['id'];
	 
	
     
     
     $category = new Model_Category();
	 $array = $category->getCategoryById($data['categoryId'])->toArray();
	 
	 $category = $array[0];

	 $this->view->assign($category);
	 
	 */

		}	
			
		public function internalAction()
		{
			  $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.carousel.min.css');
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.theme.default.min.css');

	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/owl.carousel.min.js');
			
	
			$this->view->doctype('XHTML1_RDFA');	  
			$this->view->headTitle('Bản tin Hòa Bình Group');
		     $pageModel = new Model_Page();	
		     
		     $options = array(
			     'lang' => $this->_lang
		     );
			 
			 $result = $pageModel->get_details($options);
			 
			 $this->view->result = $result;
		}
		
		function testAction(){
			$product = new New_Model_Product();
			
		}
		 
}
		