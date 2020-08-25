<?php 
class AdminTimelineController extends Louis_Controller_Action
{ 	
		protected $_seo;
		protected $_timeline;
		protected $_image;
		protected $_new;
		protected $_relationships;
		
		public function init()
		{
			 parent::init();
			 header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
			header("Pragma: no-cache"); // HTTP 1.0.
			header("Expires: 0"); // Proxies.
			 $this->_seo = new New_Model_Seo();
			 $this->_timeline = new New_Model_Timeline();
			 $this->_image = new New_Model_ProductImage();
			 $this->_new = new New_Model_Product();
			 $this->_relationships = new New_Model_ProductRelationships();
			 $this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
		
		}
		
		public function indexAction(){
	 
		$options = array(
		'content_type' => 'timeline',
		'lang' => $this->_lang,
		'order' => 'p.date desc'
		);
		//echo $this->baseUrl();
	
    	$result = $this->_timeline->get_details($options);
		
		$this->view->products = $result;

	}
		
		public function createAction()
		{
			
		$this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js');	
		$this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');				 	$this->view->headLink()->setStylesheet('/public/css/slim.min.css');
		
		$productForm = new New_Form_Timeline();
       // echo $this->_userID;
         
    if($this->getRequest()->isPost()) {		  
	  
        if($productForm->isValid($_POST)) {

           $data = array(
          'name' =>  $_POST['name'],
          'description' =>  $_POST['description'],
          'date' =>  time(),
          'content_type' => 'timeline',
          'lang' => $this->_lang,
          'author' => $this->_userID,
          );   
          
		   $pid =  $this->_timeline->save($data);

         // $this->_helper->tags->active($_POST['tags'], $pid);  // active tag
         if($pid != null){
           //lưu table category                 
           foreach ($_POST['parentID'] as $key=>$val):
		       $options = array(
			       'object_id' => $pid,
			       'category_id' => $val
		       );
		   $this->_relationships->save($options);
          endforeach;
         
 
             $path = PATH_TIMELINE . $pid;
		   // Kiểm tra thư mục, nếu chưa có thì tạo thư mục ảnh theo id san pham
		   if(!is_dir($path)){
			   mkdir($path);
			   chmod($path, 0777);
			   }
			   
			   
			   // up ảnh bìa
		       if(($_POST['slim'][0] != '') || ($_POST['slim'][0] != 0) || ($_POST['slim'][0] != null) ){	   
			$slimObj = json_decode($_POST['slim'][0], true);  
			
			$name = $slimObj['output']['name'];	 
			$image = $slimObj['output']['image'];	 
			
	 $uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/' . PATH_TIMELINE . $pid . '/';
     
	 $img = preg_replace('#^data:image/\w+;base64,#i', '', $image);
	 $img = str_replace(' ', '+', $img);
	 $data = base64_decode($img);
	 $file = $uploadDir . $name;
	 $success = file_put_contents($file, $data);
	  
	  $arr_images = array(
		  'productId' => $pid,
		  'full' => $name,
		  'isDefault' => 'Yes',
		  'content_type' => 'product',
	  );
	   $this->_image->save($arr_images);
	   }
			   // end up ảnh bìa
           return $this->_redirect('/admin/timeline');
           			   }

			  }
	 }
    $productForm->setAction('/admin/timeline/create');
    $this->view->form = $productForm;

    
    
	}
	
	
		public function editAction() 
		{
    
   		 $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js');		   
	    $this->view->headScript()->appendFile('/public/assets/js/jquery.colorbox.js'); 
	    $this->view->headScript()->appendFile('/public/js/jquery.simplyCountable.js');	
	     
	     $this->view->headLink()->setStylesheet(TEMPLATE_URL.'/uploadifive/uploadifive.css');
	     $this->view->headLink()->prependStylesheet('/public/assets/css/colorbox.css');
		$this->view->headScript()->appendFile('/public/js/smartcrop.js');  
		$this->view->headScript()->appendFile(TEMPLATE_URL.'/uploadifive/jquery.uploadifive.min.js');  
		$this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');				 	$this->view->headLink()->prependStylesheet('/public/css/slim.min.css');

        $productForm = new New_Form_TimlineEdit();

        $this->view->id = $id = $this->_request->getParam('id');
        
        $this->view->full_url = $full_url = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getRequestUri();
        
       $this->view->full_photo =  $full_photo_image = str_replace('edit', 'photo', $full_url);
        
           //Tags
          $this->view->resulttags = $this->_helper->tags(); //Hiển thị tag

        if ($this->getRequest()->isPost()) {
			if($productForm->isValid($_POST)) {
            // echo strtotime($_POST['date']);
            $date = Zend_Locale_Format::getDate($_POST['date'], array(
                'date_format' => 'dd/MM/yyyy',
                'fix_date' => true,
                ));
            $time = strtotime($date['month'] . '/' . $date['day'] . '/' . $date['year']);
            


            $data = array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],   
                'date' => $time,
                'last_update' => time(),
                'lang' => $this->_lang);
           
            $parentIDArr = $_POST['parentID'];

            //cache
          // $result = $products->updateProduct($_POST['id'], $data);
           $result = $this->_timeline->save($data, $_POST['id']);
                       
			//update category
           $this->_timeline->updateCategory($parentIDArr, $id);
         //  $this->_relationships->insertOrUpdate($parentIDArr);
            
            $path = PATH_TIMELINE . $_POST['id'];
            // Kiểm tra thư mục, nếu chưa có thì tạo thư mục ảnh theo id san pham
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0777);
            }
       
              // up ảnh bìa
              if(($_POST['slim'][0] != '') || ($_POST['slim'][0] != 0) || ($_POST['slim'][0] != null) ){
			$slimObj = json_decode($_POST['slim'][0], true);  
			
			$name = $slimObj['output']['name'];	 
			$image = $slimObj['output']['image'];	 
			
	 $uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/'. PATH_TIMELINE . $id . '/';
     
	 $img = preg_replace('#^data:image/\w+;base64,#i', '', $image);
	 $img = str_replace(' ', '+', $img);
	 $data = base64_decode($img);
	 $file = $uploadDir . $name;
	 $success = file_put_contents($file, $data);
	 
	   $arr_images = array(
		  'productId' => $id,
		  'full' => $name,
		  'isDefault' => 'Yes',
		  'content_type' => 'product',
	  );
	   $this->_image->save($arr_images);
	 
			   // end up ảnh bìa
			   }
            return $this->_redirect('/admin/timeline');
			}	
        } else {
            //$result = $products->updateProduct($id, $data);
            $product = $this->_timeline->find($id)->current();
            $result =  $product->toArray();
            $result['date'] = $product->date;
            $image = new New_Model_ProductImage();
            $img = $image->get_one_where(array('productId' => $id, 'isDefault' => 'Yes'));  
            if($img) {
            $result['cover'] = '/'. PATH_TIMELINE . $id . '/' . $img->full;           
            $result['pid'] = $id;           
            }
            $productForm = new New_Form_TimlineEdit($result);
            $productForm->setAction('/admin/timeline/edit');
            $productForm->setMethod('post');
            $productForm->populate($result);
            //format the date field
            // $pageForm->getElement('date')->setValue(date('m-d-Y', $page->date));

        }

		$seo =  $this->_seo->get_one_where(array('id_object' => $id, 'post_style' => 'new'));
		if($seo){
		$this->view->assign($seo->toArray());
		}
        $this->view->form = $productForm;
        
        
        // activites
        $activities = new Model_Activities();
        $row_activities = $activities->get_details(array('log_type_id' => $id));
   
		$this->view->activities = $row_activities;
}
	
	
		public function saveAction(){
		$this->_helper->layout()->disableLayout();
			//echo 1232;
			$details=$this->getRequest()->getPost('drop_var');
			
			 $db = Zend_Db_Table::getDefaultAdapter();
	if ($details != 0){
	$mycats = $db->select()
        ->from('menu_items', array('id', 'label'))
        ->where('menu_id = ?', 5)
        ->where('parent = ?', $details)
        ->query()
        ->fetchAll();
		
		if($mycats){
		$mycatOptions[0] = 'Chọn danh mục con';
		}else{
		$mycatOptions = null;	
		}
		
	 foreach( $mycats as $mycat ) {
     $mycatOptions[$mycat['id']] = $mycat['label'];
    }
    
		 echo Zend_Json::encode($mycatOptions);
	}
		}
		
	
	
	public function list2Action(){
	$requestData= $_REQUEST;


	$columns = array( 
// datatable column index  => database column name
	0 =>'name', 
	1 => 'shortDescription',
	2=> 'date',
	3=> 'category_id',
);

	}
	
	
	
		 public function uploadthumbAction(){
		 $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender();
	 $pID = $_POST['pid'];
     $uploadDir = $_SERVER['DOCUMENT_ROOT']. '/public/images/news/'.$pID.'/resize_690x290/';
     
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
     $pID = $_POST['productID'];
     $uploadDir = '/public/images/news/'.$pID.'/';
     
       $variationMedium = 'resize_690x290';
     
     $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
				
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	
	
	
	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
				$tempFile   = $_FILES['Filedata']['tmp_name'];
		
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];
	
	$variationPathMedium = str_replace('//','/', $uploadDir . '/' . $variationMedium);
	
    
     if (!is_dir($variationPathMedium)) {
  mkdir($variationPathMedium, 0777, true);
    }
  
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

			if(move_uploaded_file($tempFile, $targetFile)){
			$db = Zend_Db_Table::getDefaultAdapter();	
			
			$insert = "insert into productImage (productId, full, isDefault) values ('".$pID."','".$_FILES['Filedata']['name']."','no') ";
    $item = $db->query($insert);
  
 $lastId = $db->lastInsertId();
        echo json_encode(array('tenfile' => $_FILES['Filedata']['name'], 'idfile' => $lastId));
			}
	
		
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("Đuôi mở rộng không hợp lệ");

	}
	
	 }

	}		
	public function photoAction(){
		
	 //$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		$product = new New_Model_Product();
		//echo $id;
		$this->view->name = $product->getProductById($id)->name;
		$this->view->pid = $id;
	}
	
	public function delcoverAction()
		{
		
			  $this->_helper->viewRenderer->setNoRender(true);
			  $this->_helper->layout->disableLayout();
			  $name = $this->getRequest()->getPost('name');
			  $pid = $this->getRequest()->getPost('pid');
			  
						   
		 $link = $_SERVER['DOCUMENT_ROOT']. '/' . PATH_NEWS . $pid. '/' . $name;			
		 	unlink($link);
		 	
		 $del = "DELETE FROM productImage WHERE productId = $pid AND isDefault = 'Yes'" ;
		 $this->_db->query($del);
		 
		 echo 1;
	 			}
		
	public function delitemAction(){
			  $this->_helper->layout->disableLayout();
			  $data = $this->getRequest()->getPost('itemDel');
			  $PID = $this->getRequest()->getPost('pID');
			  $db = Zend_Db_Table::getDefaultAdapter();
			  
			  $select = "SELECT full FROM productImage WHERE imageId = $data";
			  $module = $db->fetchAll($select);
			  
			   if($module[0]['full'] != ''){
		 $link = $_SERVER['DOCUMENT_ROOT'].'/public/images/news/'.$PID.'/'.$module[0]['full'];

		 $medium = $_SERVER['DOCUMENT_ROOT'].'/public/images/news/'.$PID.'/resize_690x290/'.$module[0]['full'];
		
		 	unlink($link);
		 	unlink($tiny);
		 	unlink($medium);
		 $del = "delete from productImage where imageId = $data";
		 $db->query($del);
	 }
	 		echo $module[0]['full'];
	}	
	
 	
	public function seoAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		//id=35&seo_title=dgfgfg&seo_keyword=fdgfgfgfgfgf&seo_description=fgfgfgfgfg
		
		$id_post = $this->_request->getPost('id');
		$seo_title = $this->_request->getPost('seo_title');
		$seo_keyword = $this->_request->getPost('seo_keyword');
		$seo_description = $this->_request->getPost('seo_description');
		$content_type = $this->_request->getPost('content_type');
		
		$result = 0;
		$db = Zend_Db_Table::getDefaultAdapter();
		try {
		$update = 'update seo set title = "'.$seo_title.'",
											description = "'.$seo_description.'", 
											keyword = "'.$seo_keyword.'" where id_object = "'.$id_post.'"';
		$result = $db->query($update);	
		} catch (Zend_Exception $e) {
       //die('Something went wrong: ' . $e->getMessage());
     
		}
		$rowsAffected = $result->rowCount();
		if ($rowsAffected == 0) {
		 $insert = 'insert into seo (id_object, post_style, title, description, keyword) values ("'.$id_post.'", "'.$content_type.'", "'.$seo_title.'", "'.$seo_description.'", "'.$seo_keyword.'")';	
		$db->query($insert);
		} 
	
		return true;							
	}
	
	public function defaultAction(){
		$this->_helper->layout->disableLayout();
		$data = $this->getRequest()->getPost('itemDef');
	    $PID = $this->getRequest()->getPost('pID');
	    
	    $db = Zend_Db_Table::getDefaultAdapter();
			$setDefault = "update productImage set isDefault = 'Yes' where productId = $PID and imageId = $data";
  
			  $select = "update productImage set isDefault = 'No' where productId = $PID and imageId != $data";
			  $db->query($setDefault);
			 $db->query($select);
	 
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
		
	public function featureAction()
	{
		 $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout();
			if($this->getRequest()->isXmlHttpRequest())
	       {
		 $id_post = $this->_request->getPost('id');

		   $this->_timeline->update_where(array('featured' => 1), array('id' => $id_post));
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
		  
		  $this->_timeline->update_where(array('featured' => 0), array('id' => $id_post));
		
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
		  		
		  $this->_timeline->update_where(array('status' => 1), array('id' => $id_post));
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
		  
		 $this->_timeline->update_where(array('status' => 0), array('id' => $id_post));
	
		  echo true;
		  }
	}	
		
		
		public function deleteAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$id = $this->_request->getParam('id');
		
			$this->_new->delete_where(array('id' => $id));			
			$this->_relationships->delete_where(array('object_id' => $id));
			$dirname = $_SERVER['DOCUMENT_ROOT'] . PATH_TIMELINE . $id;
			$this->_helper->folders->deleteDir($dirname);
			$this->_redirect('admin/timeline');
	}
			
}