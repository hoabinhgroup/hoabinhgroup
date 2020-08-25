<?php 
class AdminTestimonialController extends Louis_Controller_Action 
{ 
   		protected $_tes;
   		protected $_new;
   		protected $_relationships;
   		protected $_image;
   		
		public function init(){
		 parent::init();
		 $this->_tes = new Model_Testimonial();
		 $this->_new = new New_Model_Product();
		 $this->_image = new New_Model_ProductImage();
		 $this->_relationships = new New_Model_ProductRelationships();
		 $this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');

		}
		
		 public function indexAction(){ 
   
     $result =  $this->_tes->getlistProducts($this->_lang);
     $this->view->products = $result;
    
          }
          
          public function createAction()
          {
	          $this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');				 	$this->view->headLink()->setStylesheet('/public/css/slim.min.css');
	          $productForm = new Form_Testimonial();
	          $db = Zend_Db_Table::getDefaultAdapter();
	          $products = new Model_Testimonial();
	          
			  $this->view->form = $productForm;
			  
			  	 if($this->getRequest()->isPost()) {
		        if($productForm->isValid($_POST)) {
			         $data = array(
		          'name' =>  $_POST['name'],
		          'ident' =>  $this->_helper->string($_POST['name'],'-'),
		          //'parentID' => $productForm->getValue('parentID'),
		          'description' =>  $_POST['description'],
		          'shortDescription' =>  $_POST['shortDescription'],
		          'date' =>  time(),
		          'content_type' =>  'testimonial',
		          'lang' =>  $this->_lang
		          );
		
			        $result = $products->save($data);
			        
			        
			        $path = 'public/images/testimonial/'.$result;
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
			
	 $uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/'. PATH_TESTIMONIALS . $result. '/';
     
	 $img = str_replace('data:image/jpeg;base64,', '', $image);
	 $img = str_replace(' ', '+', $img);
	 $data = base64_decode($img);
	 $file = $uploadDir . $name;
	 $success = file_put_contents($file, $data);
	 
	   $arr_images = array(
		  'productId' => $result,
		  'full' => $name,
		  'isDefault' => 'Yes',
		  'content_type' => 'product',
	  );
	   $this->_image->save($arr_images);
	 }
			   // end up ảnh bìa     
		 
		           return $this->_redirect('/admin/testimonial/photo/id/'.$result);
			        
			        }

			  
          }
			  
          }
          
          public function editAction()
          {
	          $this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');				 	$this->view->headLink()->setStylesheet('/public/css/slim.min.css');
	           $products = new Model_Testimonial();
	          $db = Zend_Db_Table::getDefaultAdapter();
						// $products = new Service_Model_Visa();
			 $id = $this->_request->getParam('id');
						 
						 
			 if($this->getRequest()->isPost()) {
		
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
		          'ident' =>  $this->_helper->string($_POST['name'],'-'),
		          'description' =>  $_POST['description'],
		          'shortDescription' =>  $_POST['shortDescription'],
		          'date' => $time
		          );
		          
		         $result = $products->save($data, $_POST['id']);
		         
		
		         $path = 'public/images/testimonial/'.$_POST['id'];
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
			
	 $uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/'. PATH_TESTIMONIALS . $_POST['id'] . '/';
     
	 $img = str_replace('data:image/jpeg;base64,', '', $image);
	 $img = str_replace(' ', '+', $img);
	 $data = base64_decode($img);
	 $file = $uploadDir . $name;
	 $success = file_put_contents($file, $data);
	 
	   $arr_images = array(
		  'productId' => $_POST['id'],
		  'full' => $name,
		  'isDefault' => 'Yes',
		  'content_type' => 'product',
	  );
	   $this->_image->save($arr_images);
	 }
			   // end up ảnh bìa  
					   
		           return $this->_redirect('/admin/testimonial');
		        
		    }
						 
					
				 $product = $products->find($id)->current();
				 $result['date'] = $product['date'];
				  $image = new New_Model_ProductImage();
            $img = $image->get_one_where(array('productId' => $id, 'isDefault' => 'Yes')); 
        
            if($img) {
            $result['cover'] = PATH_TESTIMONIALS . $id . '/' . $img->full;  
                      
            $result['pid'] = $id;
            }
				 $productFormEdit = new Form_TestimonialEdit($result);	
				 $productFormEdit->populate($product->toArray());
				 $this->view->form = $productFormEdit;
          }
          
          
          public function uploadAction()
          {
	          $this->_helper->layout->disableLayout();
			   $this->_helper->viewRenderer->setNoRender();
			   $pID = $_POST['productID'];
			   $uploadDir = '/public/images/testimonial/'.$pID.'/';
     
      $variationDir = 'resize_150x150';
       $variationMedium = 'resize_478x256';
//$variationPath = '/public/images/tours/'.$pID.'/resize_74x48';
     
     $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
				
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	
	
	
	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
				$tempFile   = $_FILES['Filedata']['tmp_name'];
		
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];
	
	$variationPath = str_replace('//','/', $uploadDir . '/' . $variationDir);
	$variationPathMedium = str_replace('//','/', $uploadDir . '/' . $variationMedium);
	
	 if (!is_dir($variationPath)) {
 // fwrite($bs, 'No dir, creating ' . $variationPath . "\n");
  mkdir($variationPath, 0777, true);
    }
    
     if (!is_dir($variationPathMedium)) {
 // fwrite($bs, 'No dir, creating ' . $variationPath . "\n");
  mkdir($variationPathMedium, 0777, true);
    }
  
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
			//echo $_FILES['Filedata']['name'];
			//echo $_FILES['Filedata']['name'];
			if(move_uploaded_file($tempFile, $targetFile)){
				  
				$image = new Louis_System_Image($targetFile);
				
				//Zend_Debug::dump($image, $label = null, $echo = true);
				$width = 150;
				$height = 150;
				$image->resize($width, $height);
			
				$image->saveAs($variationPath, null, true);				
				unset($image);
				$image = new Louis_System_Image($targetFile);
				$image->resize(478, 256);
				$image->saveAs($variationPathMedium, null, true);
				unset($image);
				
			$db = Zend_Db_Table::getDefaultAdapter();	
			
			$insert = "insert into productImage (productId, full, isDefault, content_type) values ('".$pID."','".$_FILES['Filedata']['name']."','no', 'product') ";
    $item = $db->query($insert);
  
 $lastId = $db->lastInsertId();
        echo json_encode(array('tenfile' => $_FILES['Filedata']['name']));
			}
	
		
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("Đuôi mở rộng không hợp lệ");

	}
	
	 }
          }
          
          
          public function photoAction()
          {
	          //$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		$product = new Model_Testimonial();
		//echo $id;
		$this->view->name = $product->getProductById($id)->name;
		$this->view->pid = $id;
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
	
		public function delitemAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			  $this->_helper->layout->disableLayout();
			 
			  $data = $this->getRequest()->getPost('itemDel');
			  $PID = $this->getRequest()->getPost('pID');
	
			  $select = "SELECT full FROM productImage WHERE imageId = $data";
			  $module = $this->_db->fetchAll($select);
			  $img_name = $module[0]['full'];
			 
			 
	
			   if($img_name != '')
			   {
		$del = "DELETE FROM productImage WHERE imageId = $data";
		 $this->_db->query($del);
		 		   				   
		 $link = $_SERVER['DOCUMENT_ROOT']. PATH_TESTIMONIALS . $PID. '/' . $img_name;
		 $medium = $_SERVER['DOCUMENT_ROOT'] . PATH_TESTIMONIALS . $PID . '/resize_478x256/' . $img_name;
		 $tiny = $_SERVER['DOCUMENT_ROOT'] . PATH_TESTIMONIALS . $PID . '/resize_150x150/' . $img_name;
		 	unlink($link);
		 	unlink($medium);
		 	unlink($tiny);
		 
	 			}
	 		echo $img_name;
	 		
	 		    // do something
			      }
			      
		public function delcoverAction()
		{
		
			  $this->_helper->viewRenderer->setNoRender(true);
			  $this->_helper->layout->disableLayout();
			  $name = $this->getRequest()->getPost('name');
			  $pid = $this->getRequest()->getPost('pid');
			  
						   
		 $link = $_SERVER['DOCUMENT_ROOT']. '/' . PATH_TESTIMONIALS . $pid. '/' . $name;			
		 	unlink($link);
		 	
		 $del = "DELETE FROM productImage WHERE productId = $pid AND isDefault = 'Yes'" ;
		 $this->_db->query($del);
		 
		 echo 1;
	 			}	      
			      
			     
	public function deleteAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam('id');
		
		$this->_tes->delete_where(array('id' => $id));
		$this->_redirect('admin/testimonial');
			 
		
	}
		
}