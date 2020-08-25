<?php 
class AdminRecruitmentController extends Louis_Controller_Action
{ 	
		protected $_timeline;
		
		public function init()
		{
			 parent::init();
			 $this->_rec = new Model_Rec();
			 $this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
		}
		
		public function indexAction(){
	 
	
    	$result = $this->_rec->get_details();
		
		$this->view->products = $result;

	}
		
		public function createAction()
		{
			
		$this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js');	
		
		$productForm = new Form_Rec();
       // echo $this->_userID;
         
    if($this->getRequest()->isPost()) {		
	  
	   $location  = implode(',',$_POST['location']);
	
        if($productForm->isValid($_POST)) {
	        
	         $expired = Zend_Locale_Format::getDate($_POST['expired'], array(
                'date_format' => 'dd/MM/yyyy',
                'fix_date' => true,
                ));
            $time = strtotime($expired['month'] . '/' . $expired['day'] . '/' . $expired['year']);

           $data = array(
          'title' =>  $_POST['title'],
          'ident' =>  $_POST['ident'],
          'people' =>  $_POST['people'],
          'salary' =>  $_POST['salary'],
          'exp' =>  $_POST['exp'],
          'deg' =>  $_POST['deg'],
          'job' =>  $_POST['job'],
          'work_style' =>  $_POST['work_style'],
          'sex' =>  $_POST['sex'],
          'age' =>  $_POST['age'],
          'contact' =>  $_POST['contact'],
          'email' =>  $_POST['email'],
          'address' =>  $_POST['address'],
          'mobile' =>  $_POST['mobile'],
          'expired' =>  $time,
          'location' =>  $location,
          'company' =>  $_POST['company'],
          'work_group' =>  $_POST['work_group'],
          'description' =>  $_POST['description'],
          'date' =>  time(),
          'lang' => $this->_lang,
          'author_id' => $this->_userID,
          );   
          
		   $pid =  $this->_rec->save($data);

         if($pid != null){
             
           return $this->_redirect('/admin/recruitment');
           			   }

			  }
	 }
    $productForm->setAction('/admin/recruitment/create');
    $this->view->form = $productForm;

    
    
	}
	
	
		public function editAction() 
		{
    
   		 $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js');		   
	   
        $productForm = new Form_Rec();

        $this->view->id = $id = $this->_request->getParam('id');
        
        $this->view->full_url = $full_url = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getRequestUri();
        
       $this->view->full_photo =  $full_photo_image = str_replace('edit', 'photo', $full_url);
        

        if ($this->getRequest()->isPost()) {
			//if($productForm->isValid($_POST)) {
				
				 $location  = implode(',',$_POST['location']);
            // echo strtotime($_POST['date']);
            $date = Zend_Locale_Format::getDate($_POST['expired'], array(
                'date_format' => 'dd/MM/yyyy',
                'fix_date' => true,
                ));
            $time = strtotime($date['month'] . '/' . $date['day'] . '/' . $date['year']);
            

            $data = array(
           'title' =>  $_POST['title'],
          'ident' =>  $_POST['ident'],
          'people' =>  $_POST['people'],
          'salary' =>  $_POST['salary'],
          'exp' =>  $_POST['exp'],
          'deg' =>  $_POST['deg'],
          'job' =>  $_POST['job'],
          'work_style' =>  $_POST['work_style'],
          'sex' =>  $_POST['sex'],
          'age' =>  $_POST['age'],
          'contact' =>  $_POST['contact'],
          'email' =>  $_POST['email'],
          'address' =>  $_POST['address'],
          'mobile' =>  $_POST['mobile'],
          'expired' =>  $time,
          'location' =>  $location,
          'company' =>  $_POST['company'],
          'work_group' =>  $_POST['work_group'],
          'description' =>  $_POST['description'],
          'last_update' =>  time(),
          'lang' => $this->_lang,
          'author_id' => $this->_userID,
                );
      
            //cache
          // $result = $products->updateProduct($_POST['id'], $data);
           $result = $this->_rec->save($data, $_POST['id']);
                       
	 
			   // end up ảnh bìa
			  // }
            return $this->_redirect('/admin/recruitment');
				
        } else {
            //$result = $products->updateProduct($id, $data);
            $product = $this->_rec->find($id)->current();
            $result =  $product->toArray();
            $result['expired'] = $product->expired;
            $result['location'] = explode(',',$product->location);
    
            $productForm = new Form_Rec($result);
            $productForm->setAction('/admin/recruitment/edit');
            $productForm->setMethod('post');
            $productForm->populate($result);
            //format the date field
            // $pageForm->getElement('date')->setValue(date('m-d-Y', $page->date));

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
	
	public function deleteAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$id = $this->_request->getParam('id');
			
			$this->_rec->delete_where(array('id' => $id));
			return $this->_redirect('/admin/recruitment');
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
		
		 $id_post = $this->getRequest()->getPost('id');
		 
		   $this->_rec->update_where(array('featured' => 1), array('id' => $id_post));
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
		  
		  $this->_rec->update_where(array('featured' => 0), array('id' => $id_post));
		
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
		  		
		  $this->_rec->update_where(array('status' => 1), array('id' => $id_post));
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
		  
		 $this->_rec->update_where(array('status' => 0), array('id' => $id_post));
	
		  echo true;
		  }
	}	
		
			
}