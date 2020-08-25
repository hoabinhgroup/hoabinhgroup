<?php 
class Product_IndexController extends Louis_Controller_Action
{ 
		protected $_form_model;
		protected $_product_model;
		protected $_tour_model;
		protected $_travel_guide_model;
		protected $_hotel_model;
		 
		public function init()
		{
			parent::init();
			$this->_form_model = new Product_Form_Product();
			$this->_product_model = new Product_Model_Product();
			$this->_tour_model = new Product_Model_ProductTour();
			$this->_travel_guide_model = new Product_Model_ProductTravelGuide();
			$this->_hotel_model = new Hotel_Model_Product();
		}

		public function indexAction()
       {
         	$this->_helper->layout->setLayout('tour_home');
         	
         	$menu = new Model_MenuItem();	       
	       $inbound = $menu->get_details(array('parent' => 72, 'limit' => 8));
		   $this->view->inbound = $inbound;	
		   	   
		   $outbound = $menu->get_details(array('parent' => 296, 'limit' => 4));
		   $this->view->outbound = $outbound;		   
		   
		   $guide = $this->_travel_guide_model->get_details(array('limit' => 6));		   
		   $this->view->guide = $guide;
		  
		  $hotel = $this->_hotel_model->get_details(array('limit' => 5));
		  $this->view->hotel = $hotel;
       }
       
       public function categoryAction()
       {
	       $this->_helper->layout->setLayout('tour_category');
	        $uri = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	        
	       
	       $menu = new Model_MenuItem();
	       
	       $row_menu = $menu->get_one_where(array('link' => $uri['ident']));
		   $this->view->menu_name = strtoupper($row_menu['name']);
		   $options = array(
			   'parent' =>  $row_menu['id'],
			   'order' =>  'm.position ASC',
			   
		   );
		   $menus =  $menu->get_details($options);
		   
		
		  $pageCurrent = $uri['page'];
		  
		   //phan trang
	 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($menus));
	 
	$paginator->setItemCountPerPage(12);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->menus = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }

	   		
	   		//$this->view->menus = $menus;
       }
       
       
       public function tourAction()
       {
	       $this->_helper->layout->setLayout('tour');
	       
	       $paramArray = $this->_request->getParams();
	
		   $this->view->uri = $uri = Zend_Registry::get('uri');
			$ident = $paramArray['ident'];
				
			
			
			$menu_item = new Model_MenuItem();
			$menu_obj = $menu_item->getMenuNameByLink($paramArray['ident']);
			
			$this->view->title = $menu_obj['name'];
			$this->view->id_menu = $id_menu = $menu_obj['id'];
		  
	  		
	/*  	$seo_modal = new Product_Model_Seo();
  	$seoObj = array();	    
    $seo = $seo_modal->get_one_where(array('id_object' => $menu_obj['id'], 'post_style' => 'tour'));
   
     //seo 
	$seoObj = $seo_modal->getSeo($seo, $menu_obj); 
	
	$this->view->doctype('XHTML1_RDFA');  // controller
  	$this->view->headTitle($seoObj['title']);
	$this->view->headMeta()->appendName("keywords",$seoObj['keyword']); 
    $this->view->headMeta()->offsetSetName("1","description",$seoObj['description']); 
    
    $this->view->headMeta()->setProperty('og:title', $seoObj['title']); 
    $this->view->headMeta()->setProperty('og:description', $seoObj['description']); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 
	  	*/
	  		// Tour khuyến mại
    
       $promotion =  $this->_product_model->get_promotions();
       if($promotion){
	    
       $this->view->promotions =   $promotion;
       }

       }
       
       public function exampleAction()
       {
	       
	        $params = $this->_request->getParams();
	    
	        $this->_helper->layout->setLayout('tour');
	       $tours = $this->_product_model->get_details(array('content_type' => 'tour'));
	       
	       
	      $config = array(
    'current_page'  => isset($params['page']) ? $params['page'] : 1,
    'total_record'  => count($tours), // tổng số thành viên
    'limit'         => 3,
    'link_full'     => '/example/trong-nuoc/{page}',
    'link_first'    => '/example/trong-nuoc',
    'range'         => 9
);

$paging = new Louis_Pagination();
$paging->init($config);
 
// Lấy limit, start
$per_page = $paging->get_config('limit');
$start = $paging->get_config('start');
 
 
// $sql = "select * from product where content_type = 'tour' limit $start, $per_page ";
 //$tour = $this->_db->fetchAll($sql);
// Lấy danh sách
$tour = $this->_product_model->get_details(array('content_type' => 'tour' ,'page' => $params['page'], 'per_page' =>  $per_page ));

$this->view->tour = $tour;
$this->view->pagination  = $paging->html();

if($this->getRequest()->isXmlHttpRequest())
{
	 die (json_encode(array(
        'tour' => $tour,
        'paging' => $paging->html()
    )));
}

 
 
       }
       
       public function tourdetailAction()
       {
	       $this->_helper->layout->setLayout('tour_detail');
	       			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();

	 		 
$current = $this->view->current = 'http://hoabinh-group.com'. $this->_request->getRequestUri();
        
      $name = $paramArray['ident'];

     $seo_modal = new New_Model_Seo();
     
     $this->view->maxcatid = $this->_product_model->getMaxID($name);
    
	 $data = $this->_product_model->getDetailsByProduct($name);
	
				
	 $data['time_travel'] = $this->_helper->content->convert_time_travel($data['time_travel']);
	 	 
	$data['communication'] = $this->_helper->content->get_icon_communication($data['communication']);	
	 
	
	 $this->view->pid = $data['id'];
	 
	 
	$cats = $this->_product_model->getCatsFromId($data['id']);
   
	$arr_cats = array();
	foreach($cats as $k=>$v):
	$arr_cats[] = $v['category_id'];
	endforeach;
	
    $menu = new Model_MenuItem();
    $this->view->menus = $menu->getMenusFromListId($arr_cats);
	$this->view->cats  = $arr_cats;
	
	$this->view->location_str = $location_str = $data['location'];
	// show các tỉnh
	$this->view->locations = $menu->getLocationFromListId($data['location']);
	$cats_strings = implode(',',$arr_cats);


	$locations = $this->_tour_model->get_locations(array('limit' => 6));
	
	//Zend_Debug::dump($locations);
	
	$location_arr = explode(',',$data['location']);
	
	//travel guide box
	 $this->view->travel_guide = $this->_travel_guide_model->get_travel_guide_relation_locations($location_arr);
	
	//Zend_Debug::dump($location_arr);
	$pid= array();
	foreach($locations as $kl=> $vl):
	 if ($vl['id_product'] != $data['id'])
	 	{
	    $exp_location = explode(',', $vl['location']);
	    
	    foreach($location_arr as $k_obj):
	    $time_travel = $this->_helper->content->convert_time_travel($vl['time_travel']);
	 	 
		$icon = $this->_helper->content->get_icon_communication($vl['communication']);
	    			
		$full_img = PATH_TOURS . $vl['id']. '/' .$vl['full'];	
		
	    if(in_array($k_obj, $exp_location)){
		   
		    $p[] = array(
			'id' => $vl['id'],
			'id_product' => $vl['id_product'],
			'ident' => $vl['ident'],
			'name' => $vl['name'],
			'shortDescription' => $vl['shortDescription'],
			'date' => date('d/m/Y',$vl['date']),
			'price' => number_format($vl['price']),
			'time_travel' => $time_travel,
			'communication' => $icon,
			'schedule' => $vl['schedule'],
			'img' => $full_img
			);
			
	    }
	    endforeach;
	    }
	endforeach;
	//unset($p[0]);
	 $this->view->other_products = $this->_helper->content->super_unique($p);
	
	 $this->view->assign($data);
	 
	 
	$menu = new Model_MenuItem();
 
	
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
    // Tour khuyến mại
    
       $promotion =  $this->_product_model->get_promotions();
  
       if($promotion){
	    
       $this->view->promotions =   $promotion;
       }
	       
       }
       
	   public function viewAction()
		{
		  $this->_helper->layout->setLayout('layout_detail');
		  $this->view->uri = $uri = Zend_Registry::get('uri');
		  $paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();

		 $this->view->ident = $name = $paramArray['ident'];
		 
		 
		  $ranStr = md5(microtime());
		  $ranStr = substr($ranStr, 0, 6);
		  $_SESSION['cap_code'] = $ranStr;

		  $this->view->cap_code =  $_SESSION['cap_code'];
          
          //echo $name;
        $product = new Product_Model_Product();
        $seo_modal = new Product_Model_Seo();
        $this->view->maxcatid = $product->getMaxID($name);
        $data = $product->getDetailsByProduct($name);
        $seo = $seo_modal->getSeoFromId($data['id']);
        
	    $this->view->lstproduct = $lstproduct = $product->getProductsByCategoryId($this->view->maxcatid);
      // Zend_Debug::dump($lstproduct);
    	if($seo != null){
    	$title = ($seo->title != null)?$seo->title:'Tour '.$data['name'];
    	$keyword = ($seo->keyword != null)?$seo->keyword:$data['name'].' Touris, travel MICE';
    	$description = ($seo->description != null)?$seo->description:$data['shortDescription'];
    	}else{
    	$title = 'Du lich, Tour '.$data['name'];
    	$keyword = $data['name'].' Tourist, travel MICE, HaLongBay cruise';
    	$description = $data['shortDescription'];
    	}
    	
    	$this->view->doctype('XHTML1_RDFA');  // controller
    
    	$this->view->headTitle($title);
    	$this->view->headMeta()->appendName("keyword",$keyword); 
        $this->view->headMeta()->offsetSetName("1","description",$description); 
        
        $this->view->headMeta()->setProperty('og:title', $title); 
        $this->view->headMeta()->setProperty('og:description', strip_tags($description)); 
        $this->view->headMeta()->setProperty('og:type', 'website'); 
        $images = new Product_Model_ProductImage();
	 
    	$this->view->imagesProduct = $imgProduct =  $images->getImagesProduct($data['id']);
	 
    	foreach($imgProduct as $k=>$v):
    	  if ($v['isDefault'] == 'Yes'){
    		 $arr[] =  $v['full'];
    	  }
    	
    	endforeach;
	
    	$imagefb = $arr[0];
    	$domain = $this->getRequest()->getHttpHost();
    	$urlfb =  'http://'.$domain.'/public/images/tours/'.$data['id'].'/'.rawurlencode($imagefb);
    	$this->view->headMeta()->setProperty('og:image', $urlfb); 
        $this->view->assign($data);
        
        //Zend_Debug::dump($domain);
        //exit();
    	  
		}
		
		public function previewAction()
		{

			$this->_helper->layout->disableLayout();
		}
		
		public function bookingAction(){
			
			
			/* $captcha = $this->_request->getParam('captcha');
			  if ($captcha == $_SESSION['cap_code'])
			  {
			*/
			if($this->getRequest()->isXmlHttpRequest())
			{
				$db = Zend_Db_Table::getDefaultAdapter();
			$id_tour = $this->_request->getParam('id_tour');
			$name = $this->_request->getPost('name');
			$email = $this->_request->getPost('email');
			$mobiphone = $this->_request->getPost('mobiphone');
			$address = $this->_request->getPost('address');
			$adult = $this->_request->getPost('adult');
			$child = $this->_request->getPost('child');
			
		/*	$insert = 'insert into product_tours_booking (id_product, name, email, mobile, address, payment, note) values ("'.$id.'","'.$name.'", "'.$email.'", "'.$mobiphone.'", "'.$address.'", "'.$payment.'", "'.$note.'")';
			$db->query($insert);
			
			$product = new Product_Model_Product();
			$res = $product->getProductById($id);
		*/
			$product = new Product_Model_Product();
			$res = $product->get_one(array($id_tour));
			
			
        	  $html = new Zend_View();
              $html->setScriptPath(APPLICATION_PATH . '/modules/product/views/emails/');
              
              // assign valeues
        	  $html->assign('name', $name);
        	  $html->assign('email', $email);
        	  $html->assign('mobi', $mobiphone);
        	  $html->assign('address', $address);
        	  $html->assign('adult', $adult);
        	  $html->assign('child', $child);
        	  $html->assign('title', $res->name);
        	  $html->assign('ident', $res->ident);
	  
	       $bodyText = $html->render('booking_tour_template.phtml');
			
			$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'hoabinhwebmaster@gmail.com', 'password' => 'whlfemisfxmhwewt', 'ssl' => 'tls', 'port' => 587 ) );
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			$mail->setBodyHtml($bodyText);
			$mail->addTo ( 'louis.standbyme@gmail.com');
			//$mail->addCc('domestic@hoabinhtourist.com', 'Chào bạn, có người vừa đăng ký Tour');
	     //	$mail->addCc('tours@hoabinhtourist.com', 'Chào Chị Lan, có người vừa đăng ký Tour');
			//$mail->addCc('ops@hoabinhtourist.com', 'Chào Anh Hân, có người vừa đăng ký Tour');
			//$mail->addCc('mice@hoabinhtourist.com', 'Chào bạn Giang, có người vừa đăng ký Tour');
		
			//$mail->addCc('media2@hoabinhtourist.com', 'Chào bạn, có người vừa đăng ký Tour');
			$mail->addCc($email, 'Cám ơn quý khách đã đăng ký Tour');
			$mail->setSubject ( $res->name );
			$mail->setFrom ( 'Hòa Bình Group ');
			$mail->send ();
		  return true;
		  
			}else
			{
				$this->_helper->viewRenderer->setNoRender(true);
				$this->_helper->layout->disableLayout();
			}
			
			
			
		/*	}
			else
			{
			$check_captcha = false;
			echo 2;
			}
		*/
			
			
		}	
		
		public function captchaAction()
    {
	 
	   

   }
   	
   	public function testAction()
   	{
	   	$this->_helper->viewRenderer->setNoRender(true);
	   	$this->_helper->layout->disableLayout();
	   	
	   	$menu = new Model_MenuItem();
	   	$content_menu = $menu->getItemsByMenu(6, $lang = 'vi' , $isHome = '', $parent = 72);
	   	$value_menu = array();
	   	foreach($content_menu as  $v):
	    $value_menu[] = array(
		    'name' => $v['name'],
		    'ident' => '/tour/'.$v['link'],	    
		    'division' => 'Tour đi qua Tỉnh/ Thành phố',	    
	    );
	    endforeach;
	    
	    
	   	 $content = $this->_product_model->get_details(array('content_type' => 'tour'));
	    $value = array();
	    foreach($content as $key => $val):
	    $value[] = array(
		    'name' => $val['name'],
		    'ident' => '/tour/'.$val['ident'].'.html',    
		    'division' => 'Tour',	    
	    );
	    endforeach;
	    $values = array_merge($value_menu, $value);
	 
	 $this->_helper->json($values);
	   	
	
   	}
   	
   	public function responseAction()
   	{
	   	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		if($this->getRequest()->isXmlHttpRequest())
	       {
		   
		      $price = $this->_request->getPost('price');
		      $tour = $this->_request->getPost('tour');
		      $travel = $this->_request->getPost('travel');
		      $id_menu = $this->_request->getPost('mid'); 
		      $sortid = $this->_request->getPost('sortid'); 
		      $page = $this->_request->getPost('page'); 
		    
		   $location = array();
		   $options = array(
			   'price' => $price,
			   'sortid' => $sortid,
			   'tour' => $tour,
			   'travel' => $travel,
			   'menu_id' => $id_menu,
		   );
		   $locations = $this->_tour_model->get_locations($options);
		   
		   $menu = new Model_MenuItem();
		   $objMenu = $menu->get_one($id_menu);
		 
		 
		   		$count = count($locations);
			    
	      $config = array(
    'current_page'  => isset($page) ? $page : 1,
    'total_record'  => $count, // tổng số thành viên
    'limit'         => 6,
    'link_full'     => '/tour/'.$objMenu->link.'/{page}',
    'link_first'    => '/tour/'.$objMenu->link,
    'range'         => 9
);

		$paging = new Louis_Pagination();
		$paging->init($config);
 
		// Lấy limit, start
	$per_page = $paging->get_config('limit');
	//$start = $paging->get_config('start');
	$options['per_page'] = $per_page;
	$options['page'] = $page;
	$locations = $this->_tour_model->get_locations($options);
			
			$result_arr = array();
	   foreach($locations as $key=>$val):
	
	   	 $time_travel = $this->_helper->content->convert_time_travel($val['time_travel']);
	 	 
		$icon = $this->_helper->content->get_icon_communication($val['communication']);
		
		$full_img = PATH_TOURS . $val['id']. '/' .$val['full'];			
			$result_arr[] = array(
			'id' => $val['id'],
			'ident' => $val['ident'],
			'name' => $val['name'],
			'shortDescription' => $val['shortDescription'],
			'date' => date('d/m/Y',$val['date']),
			'price' => number_format($val['price']),
			'time_travel' => $time_travel,
			'communication' => $icon,
			'schedule' => $val['schedule'],
			'img' => $full_img
			);

	   endforeach;
  			
  			
  			echo json_encode(array(
        'tour' => $result_arr,
        'paging' => $paging->html(),
        'count' => $count
		));
   	}
   		}
 
}
		