<?php 
class New_IndexController extends Louis_Controller_Action
{ 
		public function init(){
			$this->_helper->layout->setLayout('new');
			 parent::init();
		}
		
		public function indexAction()
       {
	       
         $paramArray = $this->_request->getParams();
	     $lang = $paramArray['lang'];
         $news = new New_Model_Product();
		 $list = array();

         if (!isset($paramArray['page'])){
			$pageCurrent = 1;
			
			$list = $news->getlistProducts($this->_lang);
		
			}elseif(!(int) $paramArray['page']){
			$pageCurrent = 1;
			//echo $paramArray['page'];
			$list = $news->getlistProductsByCategory('tin-tuc/'.$paramArray['page'], $this->_lang);
			$this->view->menutitle =  $list[0]['menu_title'];   
	
			}else {
  			$pageCurrent = $paramArray['page'];
  			$options = array(
	  			//'image' => 'yes',
	  			'lang' => $this->_lang,
	  			'content_type' => 'new',
	  			'order' => 'p.date desc',
	  			'status' => 1,
  			);
  			$list = $news->get_details($options);
  			
  		    }
  		  
  		
  			$title = 'HoaBinhgroup | Tổ chức hội nghị sự kiện';
	$keyword = 'HoaBinh Group, công ty tổ chức sự kiện, to chuc hoi nghi, tổ chức hội nghị, tổ chức hội thảo, du lịch MICE, du lich MICE, to chuc hoi thao, tour du lich, du lich trong nuoc, cong ty to chuc hoi nghi, cong ty to chuc hoi thao, hoi nghi tron goi, hoi thao tron goi, đặt vé máy bay, cho thuê thiết bị hội thảo, cho thuê thiết bị hội nghị, Đặt phòng khách sạn giá tốt, cong ty to chuc su kien';
	$description = 'HoaBinh Group - nhà tổ chức hội nghị, hội thảo, tổ chức sự kiện chuyên nghiệp - Là một nhà tổ chức tour hàng đầu với các tour du lịch trong nước và quốc tế - Cung cấp các thiết bị âm thanh, ánh sáng phục vụ sự kiện với chất lượng và giá cả hợp lí nhất. Hotline: 0913 311 911';
  		 $this->view->doctype('XHTML1_RDFA');  // controller
  		    	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keywords",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', strip_tags($description)); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 
  
  		    
  	if($list == null){
	  	throw new Zend_Controller_Action_Exception('File not found', 404);
  	}
  		    
  		//phan trang
	 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($list));
	 
	$paginator->setItemCountPerPage(10);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
      
      $menu = new Model_MenuItem();
      $this->view->newsarr = $menu->showCategoryMenu(2,$this->_lang,($this->_lang == 'vi')?"7":"136");
      
      $this->view->newArr = $menu->showCategoryMenu(2,$this->_lang,($this->_lang == 'vi')?"168":"181");
    
    //lang
	  $module = $this->_request->getModuleName();
	   $this->_helper->translate($module, $this->_lang);

       }
       
       
    public function viewAction()
		{
			
			//echo __METHOD__;
			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();

	 		 
$current = $this->view->current = 'http://hoabinh-group.com'. Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
     
    $name = $paramArray['ident'];

     $product = new New_Model_Product();
     $seo_modal = new New_Model_Seo();
     
     $this->view->maxcatid = $product->getMaxID($name);
    
	 $data = $product->getDetailsByProduct($name);
	 if($data){
		
	 $this->view->lang = $this->_lang;
	 
	if($this->_lang != $data['lang']){
		 $this->_helper->viewRenderer->setNoRender(true);
		 //echo ($this->_lang == 'vi')?"<br/><p>Bài viết chưa có trong hệ thống</p>":"No data";
		  $session = new Zend_Session_Namespace();
			$session->currentUrl = $current;
		 if($this->_lang == 'vi'){
			
			$this->_redirect('http://hoabinh-group.com/translate/filters/change/en');
		 }else{
		
			$this->_redirect('http://hoabinh-group.com/translate/filters/change/vi'); 
		 }
	}
	 $this->view->assign($data);
	
	 }else{
		$this->_response->clearBody();
		$this->_response->clearHeaders();
		$this->_response->setHttpResponseCode(404);
		 
	 }
	 
	$menu = new Model_MenuItem();
      $this->view->newsarr = $menu->showCategoryMenu(2,$this->_lang,($this->_lang == 'vi')?"7":"136");
      
      $this->view->newArr = $menu->showCategoryMenu(2,$this->_lang,($this->_lang == 'vi')?"168":"181");
    
//lang
	  $module = $this->_request->getModuleName();
	   $this->_helper->translate($module, $this->_lang);
	
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
			 $auth = Zend_Auth::getInstance()->getIdentity();
		
			/* if(!isset($auth) || ($auth->id != 3)){
				 echo 'Chức năng đang cập nhật';
				 die();
			 }
			 */
			 if(!isset($_COOKIE['userID'])){
		$this->_redirect('/quanly/signin?redirect=http://hoabinh-group.com/quanly/authorize');
			 }
		
			 
			 $news = new New_Model_Product();
			 $options = array(
				 'image' => 'yes',
				 'content_type' => 'tin-noi-bo',
				 'lang' => $this->_lang,
			 );
			$result = $news->get_details($options);
	
			
			$this->view->result = $result;
			 
		}
		 
}
		