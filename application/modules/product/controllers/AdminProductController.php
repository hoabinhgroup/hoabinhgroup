<?php 
class Product_AdminProductController extends Louis_Controller_Action
{ 	
		protected $_form_model;
		protected $_form_video;
		protected $_product_model;
		protected $_tour_model;
		protected $_travel_guide_model;
		protected $_relationships;
		 
		public function init()
		{
			parent::init();
			
			$this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');  
   // $this->view->headLink()->setStylesheet($this->view->baseUrl().'/styles/front_cal/calendar.css');
			$this->_form_model = new Product_Form_Product();
			$this->_form_travel_guide_model = new Product_Form_TravelGuide();
			$this->_form_video = new Product_Form_Video();
			$this->_product_model = new Product_Model_Product();
			$this->_tour_model = new Product_Model_ProductTour();
			$this->_travel_guide_model = new Product_Model_ProductTravelGuide();
			$this->_relationships = new New_Model_ProductRelationships();
		}
		
		public function createAction()
		{  
			$this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js');
			   //Tags
	 	     $this->view->resulttags = $this->_helper->tags(); //Hiển thị tag
		  		
			if($this->getRequest()->isPost()) {
				
				$data = array(
				 	'name' =>  $_POST['name'],
				 	'ident' =>  $_POST['ident'],
				 	'description' =>  $_POST['description'],
				 	'shortDescription' =>  $_POST['shortDescription'],
				 	'content_type' =>  'tour',
				 	'tags' => $_POST['tags']
				);
          
				$result = $this->_product_model->createProduct($data);
           
          
				$this->_helper->tags->active($_POST['tags'], $result);  // active tag
            
				$others = 'test';
				try {
        
				$others = $_POST['others'];
         
				} catch (Exception $e){
				echo $e->getMessage();
         		}
         		
     
         		$discount = ($_POST['discountPercent'] == null)?0:$_POST['discountPercent'];
         		
         		$communi = null;
         		$communication = $this->_request->getPost('communication');
          
		 		if ($communication != null){
		 		$communi = join( ',', $communication);
         		 }
         		 
         		 
		 		 $location = $this->_request->getPost('location');
		  
		 		 if ($location != null){
		 		 $locations = join( ',', $location);
          			}
       
         
		 		$data_tour = array(
		 		   'id_product' =>  $result,
		 		   'location' =>  $locations,
		 		   'time_travel' =>  $_POST['time_travel'],
		 		   'communication' =>  $communi,
		 		   'price' => $_POST['price'],
		 		   'others' => $others,
		 		   'discountPercent' => $discount,
		 		   'schedule' => $_POST['schedule'],
		 		);
           
		 		$this->_tour_model->create($data_tour);
                  
		 		$this->_helper->content->insert($_POST['parentID'], $result);
         
		 		$path = 'public/images/tours/'.$result;
		
		 		if(!is_dir($path))
		 			{
		 			mkdir($path);
		 			chmod($path, 0777);
		 		   }
 
		 		   return $this->_redirect('/admin/product/photo/id/'.$result);
			
	 }
	 // Neu chua submit
	 	
	 	$this->_form_model->setAction('/admin/product/create');
	 	$this->view->form = $this->_form_model;
      
	}
	

	
	 	public function editAction() 
	 	{
		
      $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js'); 
      $this->view->headScript()->appendFile('/public/js/jquery.simplyCountable.js'); 
     
     	//Tags
       $this->view->resulttags = $this->_helper->tags(); //Hiển thị tag
     
     $this->view->pid = $id = $this->_request->getParam('id');
            
       
    if($this->getRequest()->isPost()) {
       $communi = '';
    // echo strtotime($_POST['date']);
    	$date = Zend_Locale_Format::getDate(
		$_POST['date'],
		array(
        'date_format' => 'dd/MM/yyyy',
        'fix_date'    => true,
		)
		);
  		$time =  strtotime($date['month'].'/'.$date['day'].'/'.$date['year']);

          $data = array(
          'name' =>  $_POST['name'],
          'ident' =>  $_POST['ident'],
          'description' =>  $_POST['description'],
          'shortDescription' =>  $_POST['shortDescription'],
          'tags' => $_POST['tags'],
          'date' => $time
          );
          $parentIDArr = $_POST['parentID'];
          
           $result = $this->_product_model->updateProduct($_POST['id'], $data);
          
          $communication = $this->_request->getPost('communication');
          
          if ($communication != null){
          $communi = join( ',', $communication);
          }
		
		   $location = $this->_request->getPost('location');
		  if ($location != null){
          $locations = join( ',', $location);
          }
          
 
        
          //tags
		    $this->_helper->tags->update($_POST['tags'], $id);
		    
		    $data_update_tour = array(
			    'price' => $_POST['price'],
			    'others' => $_POST['others'],
			    'communication' => $communi,
			    'location' => $locations,
			    'time_travel' => $_POST['time_travel'],
			    'discountPercent' => $_POST['discountPercent'],
			    'schedule' => $_POST['schedule']
		    );
		  
		     $get_id_tour = $this->_tour_model->get_one_where(array('id_product' => $id));
			
		   
		    $this->_tour_model->updateProduct($get_id_tour->id, $data_update_tour);

           //update category
          $this->_product_model->updateCategory($parentIDArr, $id);

         $path = 'public/images/tours/'.$_POST['id'];
		   // check folder
		   if(!is_dir($path)){
			   mkdir($path);
			   chmod($path, 0777);
			   }
           return $this->_redirect('/admin/product/list');
        
    } else {
    
        $product = $this->_product_model->find($id)->current();
        $select_seo = 'select * from seo where id_object = "'.$id.'" and post_style="tour"';
        $rseo = $this->_db->fetchRow($select_seo);
   
		if ($rseo)
			{
				$this->view->assign($rseo);
  		     }
  		  $result = array();
  		  $result = $this->_tour_model->get_one_where(array('id_product' => $id));
  		  if($result){
  		  $result = $result->toArray();  
	  	  $this->view->price = $result['price_type'];
  		  $this->view->tour = $result['tour_type'];
  		  $this->view->travel = $result['travel_type'];
  		   $result['date'] = $product['date'];
  		  }
  		 	 
	     
          $productForm = new Product_Form_ProductEdit($result);
          $productForm->setAction('/admin/product/edit');
          $productForm->setMethod('post');
          $productForm->populate($result);
          $productForm->populate($product->toArray());
        
  
   
        //format the date field
       // $pageForm->getElement('date')->setValue(date('m-d-Y', $page->date));
 
    }
    
    
    $this->view->form = $productForm;
}


		public function createtravelguideAction()
		{  
			
	  $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js'); 
			   //Tags
	 	$this->view->resulttags = $this->_helper->tags(); //Hiển thị tag
		  		
			if($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				
				$data = array(
				 	'name' =>  $_POST['name'],
				 	'ident' =>  $_POST['ident'],
				 	'description' =>  $_POST['description'],
				 	'shortDescription' =>  $_POST['shortDescription'],
				 	'content_type' =>  'travel_guide',
				 	'tags' => $_POST['tags']
				);
				
				if($this->_form_travel_guide_model->isValid($data)){
					
					$result = $this->_product_model->createProduct($data);
           
				
          
				$this->_helper->tags->active($_POST['tags'], $result);  // active tag
                     		 
		 		$location = $this->_request->getPost('location');
		  
		 		 if ($location != null){
		 		 $locations = join( ',', $location);
          			}
       
         
		 		$data_travel_guide = array(
		 		   'id_product' =>  $result,
		 		   'location' =>  $locations,
		 		);
           
		 		$this->_travel_guide_model->create($data_travel_guide);
                  
		 		$this->_helper->content->insert($_POST['parentID'], $result);
         
		 		$path = 'public/images/travel_guide/'.$result;
		
		 		if(!is_dir($path))
		 			{
		 			mkdir($path);
		 			chmod($path, 0777);
		 		   }
 
		 		   return $this->_redirect('/admin/product/photo/id/'.$result);
				}
				
			
			
	 }
	 // Neu chua submit
	 	
	 	$this->_form_model->setAction('/admin/product/createtravelguide');
	 	$this->view->form = $this->_form_travel_guide_model;
      
	}
	
	
		public function edittravelguideAction()
		 {
       $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js'); 
      $this->view->headScript()->appendFile('/public/js/jquery.simplyCountable.js');
     
     	//Tags
       $this->view->resulttags = $this->_helper->tags(); //Hiển thị tag
     
     $this->view->pid = $id = $this->_request->getParam('id');
            
       
    if($this->getRequest()->isPost()) {
       $communi = '';
    // echo strtotime($_POST['date']);
    	$date = Zend_Locale_Format::getDate(
		$_POST['date'],
		array(
        'date_format' => 'dd/MM/yyyy',
        'fix_date'    => true,
		)
		);
  		$time =  strtotime($date['month'].'/'.$date['day'].'/'.$date['year']);

          $data = array(
          'name' =>  $_POST['name'],
          'ident' =>  $_POST['ident'],
          'description' =>  $_POST['description'],
          'shortDescription' =>  $_POST['shortDescription'],
          'tags' => $_POST['tags'],
          'date' => $time
          );
            $parentIDArr = $_POST['parentID'];
          
           $result = $this->_product_model->updateProduct($_POST['id'], $data);
          
                 
          //tags
		    $this->_helper->tags->update($_POST['tags'], $id);
		    
		    $location = $this->_request->getPost('location');
		  
		 		 if ($location != null){
		 		 $locations = join( ',', $location);
          			}
		    
		    $data_update_travel_guide = array(
			    'location' => $locations
		    );
		    
		  
		     $get_id = $this->_travel_guide_model->get_one_where(array('id_product' => $id));
			
		   
		    $this->_travel_guide_model->updateProduct($get_id->id, $data_update_travel_guide);

			 $this->_product_model->updateCategory($parentIDArr, $id);

         $path = 'public/images/tours/'.$_POST['id'];
		   // check folder
		   if(!is_dir($path)){
			   mkdir($path);
			   chmod($path, 0777);
			   }
           return $this->_redirect('/admin/product/listtravelguide');
        
    } else {
    
        $product = $this->_product_model->find($id)->current();
        $select_seo = 'select * from seo where id_object = "'.$id.'" and post_style="travel_guide"';
        $rseo = $this->_db->fetchRow($select_seo);
   
		if ($rseo)
			{
				$this->view->assign($rseo);
  		     }
  		     
  		  $result = $this->_travel_guide_model->get_one_where(array('id_product' => $id));
  		  $result = $result->toArray();
	      $result['date'] = $product['date'];
          $productForm = new Product_Form_TravelGuideEdit($result);
          $productForm->setAction('/admin/product/edittravelguide');
          $productForm->setMethod('post');
          $productForm->populate($result);
          $productForm->populate($product->toArray());
        
  
   
        //format the date field
       // $pageForm->getElement('date')->setValue(date('m-d-Y', $page->date));
 
    }
    
    
    $this->view->form = $productForm;
}

		public function createvideoAction()
		{
			
		  		
			if($this->_request->isPost()) {
				$formData = $this->_request->getPost();
				
				$data = array(
				 	'name' =>  $_POST['name'],
				 	'ident' =>  $this->_helper->string($_POST['name'],'-'),
				 	'content_type' =>  'video',
				 	'shortDescription' =>  $_POST['shortDescription'],
				 	'date' => time(),
				 	'lang' => $this->_lang,
				 	'author' => $this->_userID,
				);
					
						
				$result = $this->_product_model->save($data);
          		  
                  
		 	if($result != null){
           //lưu table category                 
           foreach ($_POST['parentID'] as $key=>$val):
		       $options = array(
			       'object_id' => $result,
			       'category_id' => $val
		       );
		   $this->_relationships->save($options);
          endforeach;
        		}
 
		 		   return $this->_redirect('/admin/product/list_video');
				
				
				
					
	 }
	 // Neu chua submit
	 	
	 	$this->_form_model->setAction('/admin/product/create_video');
	 	$this->view->form = new Product_Form_Video();
		}
		
		
		public function editvideoAction()
		{
			
		  	$this->view->pid = $id = $this->_request->getParam('id');	
			if($this->_request->isPost()) {
				$formData = $this->_request->getPost();
			
				 $parentIDArr = $_POST['parentID'];
				 
		 $date = Zend_Locale_Format::getDate(
		 $_POST['date'],
		 	array(
		 		'date_format' => 'dd/MM/yyyy',
		 		'fix_date'    => true,
		 		)
		 	);
		
  		$time =  strtotime($date['month'].'/'.$date['day'].'/'.$date['year']);
				 
				$data = array(
				 	'name' =>  $_POST['name'],
				 	'ident' =>  $this->_helper->string($_POST['name'],'-'),
				 	'shortDescription' =>  $_POST['shortDescription'],
				 	'date' => $time,
				);
				
				//if($this->_form_travel_guide_model->isValid($data)){
				
					$result = $this->_product_model->updateProduct($id, $data);
          		  
  
				$this->_product_model->updateCategory($parentIDArr, $id);
				
		 		   return $this->_redirect('/admin/product/list_video');
				//}
							
	 }
	 // Neu chua submit
	 	  $product = $this->_product_model->find($id)->current();	
	 	  $product_obj = $product->toArray();
	 	  $result['date'] = $product_obj['date'];
	 	 
		  $productForm = new Product_Form_VideoEdit($result);
          $productForm->setAction('/admin/product/edit_video');
          $productForm->setMethod('post');
          $productForm->populate($result);
          $productForm->populate($product_obj);
		 $this->view->form = $productForm;		
		}
		
		
		
	
	
		public function saveAction(){
		$this->_helper->layout()->disableLayout();
			//echo 1232;
			$details=$this->getRequest()->getPost('drop_var');
			
			 
	if ($details != 0){
	$mycats = $this->_db->select()
        ->from('menu_items', array('id', 'label'))
        ->where('menu_id = ?', 5)
        ->where('parent = ?', $details)
        ->query()
        ->fetchAll();
		
		if($mycats){
		$mycatOptions[0] = 'Ch·ªçn danh m·ª•c con';
		}else{
		$mycatOptions = null;	
		}
		
	 foreach( $mycats as $mycat ) {
     $mycatOptions[$mycat['id']] = $mycat['label'];
    }
    
		 echo Zend_Json::encode($mycatOptions);
	}
		}
		
	public function listAction(){

    // fetch all of the current pages
     $result = $this->_product_model->get_details(array('content_type' => 'tour'));

     $this->view->products = $result;
  
	}
	
	public function listvideoAction()
	{
		  $cat = $this->_request->getParam('cat');
		  $options = array(
			  'content_type' => 'video'
		  );
		  if(isset($cat)){
			$options['cat'] = $cat;
		  }
		  $result = $this->_product_model->get_details($options);
		  
          $this->view->products = $result;
	}
	
	public function listtravelguideAction(){
		$mdlMenu = new Model_MenuItem();
		$menus = $mdlMenu->getItemsByMenu(2,'vi','No');
		$recursive = new Louis_System_Recursive($menus->toArray());
        $newArr = $recursive->buildArray(233);
      
        $listid = array();
        foreach($newArr as $key=>$val):
        $listid[] = $val['id'];
        
        endforeach;
       // $listid[] = 233;
       
        //$listid = join(',',$listid);
		
       
		// fetch all of the current pages
    	$result = $this->_travel_guide_model->getlistProductsByCats($listid);
    
		$this->view->products = $result;
	}
	
	 public function uploadthumbAction(){
		 $this->_helper->layout->disableLayout();
         $this->_helper->viewRenderer->setNoRender();
    	 $pID = $_POST['pid'];
    	 $size = $_POST['size'];
         $uploadDir = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $pID . '/resize_'.$size.'/';
     
        $img = $_POST['imgBase64'];
        $pname = $_POST['pname'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = $uploadDir . $pname;
        $success = file_put_contents($file, $data);
        echo $success ? $file : 'Unable to save the file.';
		}
	
	public function uploadAction(){
		
	  $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender();
    $cID = $_POST['productID'];
     
     $uploadDir = PATH_TOURS . $cID . '/';
        $large = '500x256';
        $medium = '334x179';
        $tiny = '97x60';
       $variationLarge = 'resize_'.$large;
       $variationMedium = 'resize_'.$medium;
       $variationTiny = 'resize_'.$tiny;
     
     $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
				
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	
	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
				$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];
	
	$variationPath = str_replace('//','/', $uploadDir .'/' . $variationLarge);
	$variationPathMedium = str_replace('//','/', $uploadDir . '/' . $variationMedium);
	$variationPathTiny = str_replace('//','/', $uploadDir . '/'. $variationTiny);
	
	 if (!is_dir($variationPath)) {
 // fwrite($bs, 'No dir, creating ' . $variationPath . "\n");
        mkdir($variationPath, 0777, true);
    }
    
     if (!is_dir($variationPathMedium)) {
 // fwrite($bs, 'No dir, creating ' . $variationPath . "\n");
        mkdir($variationPathMedium, 0777, true);
    }
    
     if (!is_dir($variationPathTiny)) {
 // fwrite($bs, 'No dir, creating ' . $variationPath . "\n");
        mkdir($variationPathTiny, 0777, true);
    }

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
    
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
			if(move_uploaded_file($tempFile, $targetFile)){
				
			
		$insert = "insert into productImage (productId, full, isDefault, content_type) values ('".$cID."','".$_FILES['Filedata']['name']."','No','product') ";
			$item = $this->_db->query($insert);
  
			$lastId = $this->_db->lastInsertId();
			
			$arrsize = array('0' => $large, '1' => $medium, '2' => $tiny);
		
        echo json_encode(array('tenfile' => $_FILES['Filedata']['name'], 'idfile' => $lastId, 'size' => $arrsize));
			}
	
		
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("ƒêu√¥i m·ªü r·ªông kh√¥ng h·ª£p l·ªá");

	}
	
	}

	}
	
	public function photoAction(){
	 //$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		$product = new Product_Model_Product();
		//echo $id;
		$this->view->name = $product->getProductById($id)->name;
		$this->view->pid = $id;
		$this->view->ident = $product->getProductById($id)->ident;
        //Zend_debug::dump($product->getProductById($id)->name);
        //exit();
	}
	
	public function deleteAction()
	{
		$id = $this->_request->getParam('id');
		
		
		$product = new Product_Model_Product();
		$product->delete($id);
		
		$dirname = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $id;
		$this->_helper->folders->deleteDir($dirname);
		// $this->_helper->cache($url);  
	       $this->_redirect('admin/product/list');
	}
	
	public function delvideoAction()
	{
		$id = $this->_request->getParam('id');
	
		//$this->_product_model->delVideo($id);		
		$this->_product_model->delete_where(array('id' => $id));
		$this->_relationships->delete_where(array('object_id' => $id));
		
	    $this->_redirect('admin/product/list_video');
	}
		
	public function delitemAction(){
			  $this->_helper->layout->disableLayout();
			  $data = $this->getRequest()->getPost('itemDel');
			  $PID = $this->getRequest()->getPost('pID');
			  
			  
			  $select = "SELECT full FROM productImage WHERE imageId = $data";
			  $module = $this->_db->fetchAll($select);
			  
			  /*
	    $large = '500x256';
        $medium = '334x179';
        $tiny = '97x60';
        */
			   if($module[0]['full'] != ''){
		 $link = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $PID . '/'.$module[0]['full'];
		 $tiny = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $PID . '/resize_97x60/'.$module[0]['full'];
		 $medium = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $PID.'/resize_334x179/'.$module[0]['full'];
		 
		  $large = $_SERVER['DOCUMENT_ROOT'] . PATH_TOURS . $PID.'/resize_500x256/'.$module[0]['full'];
		
		 	unlink($link);
		 	unlink($tiny);
		 	unlink($medium);
		 	unlink($large);
		 $del = "delete from productImage where imageId = $data";
		 $this->_db->query($del);
	 }
	 		echo $module[0]['full'];
	}	
	
	public function defaultAction(){
		$this->_helper->layout->disableLayout();
		$data = $this->getRequest()->getPost('itemDef');
	    $PID = $this->getRequest()->getPost('pID');
	    
	    
			$setDefault = "update productImage set isDefault = 'Yes' where productId = $PID and imageId = $data";
  
			  $select = "update productImage set isDefault = 'No' where productId = $PID and imageId != $data";
			  $this->_db->query($setDefault);
			 $this->_db->query($select);
			 
	 
	 		return true;
	}
		
		
	public function getProductById($id){
		 $currentProduct = $this->find($id)->current();
		 if ($currentProduct) {
       return $currentProduct;
	   } else {
        return false;
		}
		}
		
	public function booktourAction(){
	    
		$select= 'select * from product_tours_booking order by id desc';
		$this->view->result = $result = $this->_db->fetchAll($select);
	}
	
	public function seoAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		//id=35&seo_title=dgfgfg&seo_keyword=fdgfgfgfgfgf&seo_description=fgfgfgfgfg
		
		$id_post = $this->_request->getPost('id');
		$seo_title = $this->_request->getPost('seo_title');
		$seo_keyword = $this->_request->getPost('seo_keyword');
		$seo_description = $this->_request->getPost('seo_description');
		
		$result = 0;
		
		try {
		$update = 'update seo set title = "'.$seo_title.'",
											description = "'.$seo_description.'", 
											keyword = "'.$seo_keyword.'" where id_object = "'.$id_post.'"';
		$result = $this->_db->query($update);	
		} catch (Zend_Exception $e) {
       //die('Something went wrong: ' . $e->getMessage());
     
		}
		$rowsAffected = $result->rowCount();
		if ($rowsAffected == 0) {
		 $insert = 'insert into seo (id_object, post_style, title, description, keyword) values ("'.$id_post.'", "tour", "'.$seo_title.'", "'.$seo_description.'", "'.$seo_keyword.'")';	
		$this->_db->query($insert);
		} 
	
		return true;							
	}
	
	public function tagAction(){
	//if($this->getRequest()->isXmlHttpRequest())
	  //  {
		
		$query = $this->_request->getParam('q');
		
		$select = 'select name from tags where name like "%'.$query.'%"';
		$result = $this->_db->fetchAll($select);
		
		//foreach($result as $key=>$val):
		
		//endforeach;
	
	//die();
		$this->_helper->json($result);
		//}
	}
	
	public function featureAction()
	{
		 $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout();
			if($this->getRequest()->isXmlHttpRequest())
	       {
		 
		  $id_post = $this->_request->getPost('id');

		   $this->_product_model->update_where(array('featured' => 1), array('id' => $id_post));
		  echo true;
		  }
	}
	
	public function unfeatureAction()
	{
		  $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout();
			if($this->getRequest()->isXmlHttpRequest())
	       {
		 
		  $id_post = $this->_request->getPost('id');
		  
		  $this->_product_model->update_where(array('featured' => 0), array('id' => $id_post));
		
		  echo true;
		  }
	}
	
	
	public function showAction()
	{
		 $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout();
			if($this->getRequest()->isXmlHttpRequest())
	       {
		 
		  $id_post = $this->_request->getPost('id');
		  		
		  $this->_product_model->update_where(array('status' => 1), array('id' => $id_post));
		  echo true;
		  }
	}
	
	public function hideAction()
	{
		  $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout();
			if($this->getRequest()->isXmlHttpRequest())
	       {
		 
		  $id_post = $this->_request->getPost('id');
		  
		 $this->_product_model->update_where(array('status' => 0), array('id' => $id_post));
		  echo true;
		  }
	}	
	
	public function testAction()
	{
		
	}
	
	public function tourAction()
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
		    
		   $location = array();
		   $locations = $this->_tour_model->get_locations($sortid,$price);
		   
		  // $tour = explode(',',$tour);
		  // $travel = explode(',',$travel);
		   
		   
		    $result = array();
		    $id_tour = array();
		    $id_travel = array();
			foreach($locations as $key=>$val):
			 $location = explode(',',$val['location']);
			 $tour_type_column = explode(',',$val['tour_type']);
			// $travel_type_column = explode(',',$val['travel_type']);
			 //$tour_travel_col = array_merge($tour_type_column, $travel_type_column);
			 array_unshift($tour_type_column , $val['id']);
			// array_unshift($travel_type_column , $val['id']);

			// foreach ($tour as $kt):
			   if(in_array($id_menu, $location)){
				   if ($tour != null){
			 	if(strpos($val['tour_type'], $tour) !== false){			 
				 $id_tour[] = $val['id'];
			 	}
			     	}else{
				  $id_tour[] = $val['id'];  	
			     	}
			     	
			      if ($travel != null){
				  if(strpos($val['travel_type'], $travel) !== false){			 
				 $id_travel[] = $val['id'];
			 	}    
			      }	else{
				  $id_travel[] = $val['id'];  	
			     	}
			 	}
			// endforeach;
			 

			endforeach;
			
			$array_merge = array_intersect($id_tour,$id_travel); // lay gia tri giong
		
			$result_arr = array();
			
			foreach($array_merge as $v):
			
			$result = $this->_product_model->get_one_custom($v);
			$time_travel = explode('.',$result['time_travel']);
				if(!isset($time_travel[1])){
						$dem = '';
					}else{
						$dem = $time_travel[1]. ' đêm';
					}
				$time_travel = $time_travel[0]. ' ngày '. $dem; 
				
				
				$com_arr = explode(',',$result['communication']);
					$icon = '';
					foreach($com_arr as $val):
				    $icon.= ($val == 'car')?'<i class="fa fa-bus" aria-hidden="true"></i>':'';
				    $icon.= ($val == 'train')?' <i class="fa fa-train" aria-hidden="true"></i>':'';
				    $icon.= ($val == 'plane')?' <i class="fa fa-plane" aria-hidden="true"></i>':'';
				    $icon.= ($val == 'bike')?' <i class="fa fa-bicycle" aria-hidden="true"></i>':'';
				    $icon.= ($val == 'cruise')?' <i class="fa fa-ship" aria-hidden="true"></i>':'';
					endforeach;
					
		$full_img = PATH_TOURS . $result['id']. '/' .$result['full'];			
			$result_arr[] = array(
			'id' => $result['id'],
			'ident' => $result['ident'],
			'name' => $result['name'],
			'shortDescription' => $result['shortDescription'],
			'date' => date('d/m/Y',$result['date']),
			'price' => number_format($result['price']),
			'time_travel' => $time_travel,
			'communication' => $icon,
			'schedule' => $result['schedule'],
			'img' => $full_img
			);
			
			endforeach;
			
			//$result_arr[] = array('count' => count($result_arr));
			
			 $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($result_arr));
	 
  			$paginator->setItemCountPerPage(100);
	
  			$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
  			$paginator->setCurrentPageNumber($page);

  			$pagecount =  $paginator->getPages()->pageCount;
  			
  			$tours = array();
  			foreach($paginator as $k=>$v):
  			$tours[] = $v;
  			
  			endforeach;
  			
  			
  			echo json_encode($tours);
		    }
	}
	
	public function setAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		if($this->getRequest()->isXmlHttpRequest())
	       {
		$price = '';
		$tour = '';
		$travel = '';
		
		  $price = $this->_request->getPost('price');
		   $tour = $this->_request->getPost('tour');
		   $travel = $this->_request->getPost('travel');
		  $pid = $this->_request->getPost('pid');
	
		  $this->_db->beginTransaction(); //begin a transaction
		  try{
		  $update = "UPDATE product_tours SET 
	 				  	price_type = '".$price."',  
	 				  	tour_type = '".$tour."',  
	 				  	travel_type = '".$travel."' 
	 				  WHERE 
	 				  id_product = $pid";
	 				  
		    $this->_db->query($update);
		    $this->_db->commit();
		    }
catch(Exception $e)
{
$this->_db->rollback();
}
		    echo true;
		  }
		  
		 
		
		  }
		
	

}