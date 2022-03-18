<?php
class AdminMenuitemController extends Louis_Controller_Action
{
	protected $_model_menu_items;
	protected $_productImage_model;
	
    public function init()
    {
        /* Initialize action controller here */
         parent::init();
         $this->view->headScript()->appendFile('https://code.jquery.com/jquery-1.9.1.js');
         $this->_model_menu_items = new Model_MenuItem();
         $this->_productImage_model = new Model_ProductImage();
    }
    public function indexAction()
	{
		
		 $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js'); 
		 $this->view->headScript()->appendFile('/public/assets_old/js/jquery.nestable.min.js'); 
	$this->view->headScript()->appendFile(TEMPLATE_URL.'/default/assets/js/bootstrap-multiselect.js');
   $menu = $this->_request->getParam('menu');
   $mdlMenu = new Model_Menu();
   $mdlMenuItem = new Model_MenuItem();
   $this->view->menu = $mdlMenu->find($menu)->current();
   $this->view->items = $mdlMenuItem->getItemsByMenu($menu, $this->_lang);
  

   
    $this->view->itemParent = $mdlMenuItem->getItemsByMenuParent($menu);
    
       if($this->getRequest()->isPost()) {
	   $linkfolder =  $_POST['link_folder'];
	   $parent =  $_POST['parent'];
	   $update = "UPDATE menu_items SET link_folder = '".$linkfolder."' WHERE menu_id = $menu AND (parent  = $parent OR id = $parent)";
	   $this->_db->query($update);
	   }

   }
   
   public function indextestAction()
	{
		//$this->_helper->viewRenderer->setNoRender(true);
		 $this->_helper->layout->disableLayout();
		 $this->view->headScript()->appendFile('/public/scripts/ckeditor/ckeditor.js'); 
		 $this->view->headScript()->appendFile('/public/assets_old/js/jquery.nestable.min.js'); 
	
   $menu = $this->_request->getParam('menu');
   $mdlMenu = new Model_Menu();
   $mdlMenuItem = new Model_MenuItem();
   $this->view->menu = $mdlMenu->find($menu)->current();
   $this->view->items = $mdlMenuItem->getItemsByMenu($menu, $this->_lang);
  

   
    $this->view->itemParent = $mdlMenuItem->getItemsByMenuParent($menu);
    
       if($this->getRequest()->isPost()) {
	   $linkfolder =  $_POST['link_folder'];
	   $parent =  $_POST['parent'];
	   $update = "UPDATE menu_items SET link_folder = '".$linkfolder."' WHERE menu_id = $menu AND (parent  = $parent OR id = $parent)";
	   $this->_db->query($update);
	   }

   }
   
   public function imageAction()
   {
	   $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/uploadifive/uploadifive.css');  
	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/uploadifive/jquery.uploadifive.min.js');
	   //echo __METHOD__;
	    $menu = $this->_request->getParam('id');
	    $mdlMenuItem = new Model_MenuItem();
	    
	    $menuItem = $mdlMenuItem->getMenuidByID($menu);
	    $menuItem = $menuItem->toArray();
	  
	    $this->view->assign($menuItem[0]);
	 
   }
   
   public function uploadimageAction()
   {
	     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender();
     $cID = $_POST['productID'];
     $uploadDir = '/public/images/backgrounds/';
     
     $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
				
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	
	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
				$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
			if(move_uploaded_file($tempFile, $targetFile)){
	
			
		$insert = array(
				'productId' => $cID,
				'full' => $_FILES['Filedata']['name'],
				'isDefault' => 'No',
			);
			
			$lastId = $this->_productImage_model->create($insert);
			
			 echo json_encode(array('tenfile' => $_FILES['Filedata']['name'], 'idfile' => $lastId));
	 		}
			
       
			}
	
		
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("Đuôi mở rộng không hợp lệ");

	}
	
	}
	
	public function captionAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			
			$item = $this->_request->getPost('item');
			$caption = $this->_request->getPost('caption');
			
		if($this->getRequest()->isXmlHttpRequest())
				{
			$data = array(
				'caption' => $caption
			);
			$this->_productImage_model->update_where($data, array('imageId' => $item));
			
			return true;
			
			}
	}
	
	
	public function orderAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			if($this->getRequest()->isXmlHttpRequest())
				{
			 $item = $this->_request->getPost('item');
			 $order = $this->_request->getPost('order');
			
			$data = array(
				'position' => $order
			);
			$this->_productImage_model->update_where($data, array('imageId' => $item));
			
			return true;
			}
	}
	
	public function urlAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			$item = $this->_request->getPost('item');
			$url = $this->_request->getPost('url');
			
			$data = array(
				'url' => $url
			);
			$this->_productImage_model->update_where($data, array('imageId' => $item));
			
			return true;
	}
	 
  
   /*
  public function addAction()
   {
   $menu = $this->_request->getParam('menu');
   $mdlMenu = new Model_Menu();
   $this->view->menu = $mdlMenu->find($menu)->current();
   $frmMenuItem = new Form_MenuItem();
  
    if ($this->_request->isPost()) {
		$data = $_POST;
            $mdlMenuItem = new Model_MenuItem();
           if($data['link'] == null){
	            $data['link'] = $this->_helper->string($data['name'],'-');
            }
            $mdlMenuItem->addItem($data['menu_id'], $data['name'], $data['parentID'], 
               $data['page_id'], $data['link'],$data['link_folder'],$data['description'], $data['is_conveyance_required']);
            $url =  APPLICATION_PATH . '/cache_menu';
			$this->_helper->cache($url);
            $this->_request->setParam('menu', $data['menu_id']);
	            $this->_redirect('/admin/menuitem/add/menu/'.$menu);
		
	 }

   $frmMenuItem->populate(array('menu_id' => $menu));
   $this->view->form = $frmMenuItem;
   
   }
   */
   
   public function addAction()
   {
	
   if($this->getRequest()->isXmlHttpRequest())
   {
   	   $this->_helper->viewRenderer->setNoRender(true);
   	   $this->_helper->layout->disableLayout();
   	 
   	 //$this->_helper->json($this->_request->getParams());
   	 try{
   	 
   	  $id = $this->_request->getPost ( 'id' );
   /*	  $parentID = $this->_request->getPost ( 'parentID' );
   	  $name = $this->_request->getPost ( 'name' );
   	  $menu_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('menu');
   	  $page_id = $this->_request->getPost ( 'page_id' );
   	  $link = $this->_request->getPost ( 'link' );
   	  $link_folder = $this->_request->getPost ( 'link_folder' );
   	  $description = $this->_request->getPost ( 'description' );
   	  $icon = $this->_request->getPost ( 'icon' );
   	  $is_conveyance_required = $this->_request->getPost ( 'is_conveyance_required' );
   	
   	  if($link == null){
	           $link = $this->_helper->string( $this->_request->getPost ('name'),'-');
            }else{
	           $link =  $this->_request->getPost ('link');
            }
           */
        $options = array(
	        'menu_id' => $this->_request->getParam('menu'),
	        'parent'  => $this->_request->getParam ('parentID', 0),
	        'name'  => $this->_request->getPost ('name'),
	        'page_id'  => $this->_request->getPost ('page_id'),
	        'link'  => $this->_request->getPost ('link'),
	        'link_folder'  => $this->_request->getPost ('link_folder'),
	        'description'  => $this->_request->getPost ('description'),
	        'icon'  => $this->_request->getPost ('icon'),
	        'isHome'  => $this->_request->getPost ('is_conveyance_required'),
	        'lang'  => $this->_lang,
        );  
     
             $mdlMenuItem = new Model_MenuItem();
           
          
          $result = $mdlMenuItem->save($options);
           $last =  $mdlMenuItem->get_one($result);
           
      
            $this->_helper->json($last->toArray());  
            
            $url =  APPLICATION_PATH . '/cache/menu/'.$this->_lang;
	        $this->_helper->cache($url);
             
             
   	  } catch (Zend_Exception $e) {
   	 		$errors = array(
   	 		"Caught exception" => get_class($e),
   	 		"Message" => $e->getMessage(),
   	 		"Error code" => $e->getCode(),
   	 		"File name" => $e->getFile(),
   	 		"Line" => $e->getLine(),
   	 		"Backtrace" => $e->getTraceAsString()
   	 				      		);       
   	 	$this->_helper->json($errors);	       
   	 			      		     
   	 			      		   }              
        
   }
   
   }
   
   public function moveAction() {
    $id = $this->_request->getParam ( 'id' );
    $direction = $this->_request->getParam ( 'direction' );
	$mdlMenuItem = new Model_MenuItem ( );
    $menuItem = $mdlMenuItem->find ( $id )->current ();
    if ($direction == 'up') {
        $mdlMenuItem->moveUp ( $id );
    } elseif ($direction == 'down') {
        $mdlMenuItem->moveDown ( $id );
    }
    $this->_request->setParam ( 'menu', $menuItem->menu_id );
    $this->_forward ( 'index' );
	}
	
	public function smoveAction() {
    $id = $this->_request->getParam ( 'id' );
    $direction = $this->_request->getParam ( 'direction' );
	$mdlMenuItem = new Model_MenuItem ( );
    $menuItem = $mdlMenuItem->find ( $id )->current ();
    if ($direction == 'up') {
        $mdlMenuItem->moveUpS ( $id );
    } elseif ($direction == 'down') {
        $mdlMenuItem->moveDownS ( $id );
    }
    $this->_request->setParam ( 'menu', $menuItem->menu_id );
    $this->_forward ( 'index' );
	}
	
	public function hmoveAction() {
    $id = $this->_request->getParam ( 'id' );
    $direction = $this->_request->getParam ( 'direction' );
	$mdlMenuItem = new Model_MenuItem ( );
    $menuItem = $mdlMenuItem->find ( $id )->current ();
    if ($direction == 'up') {
        $mdlMenuItem->moveUpH ( $id );
    } elseif ($direction == 'down') {
        $mdlMenuItem->moveDownH ( $id );
    }
    $this->_request->setParam ( 'menu', $menuItem->menu_id );
    $this->_forward ( 'homecat' );
	}

public function updateAction ()
	{
		
		$this->view->headScript()->appendFile(TEMPLATE_URL.'/default/assets/js/bootstrap-multiselect.js');
		
    $this->view->id_menuitem = $id = $this->_request->getParam('id');
    // fetch the current item
    $mdlMenuItem = new Model_MenuItem();
    $currentMenuItem = $mdlMenuItem->find($id)->current();
    // fetch its menu
    $mdlMenu = new Model_Menu();
    $this->view->menu = $mdlMenu->find($currentMenuItem->menu_id)->current();
    
    $frmMenuItem = new Form_MenuItem();
    $frmMenuItem->setAction('/'. ADMIN .'/menuitem/update');
    // process the postback
    if ($this->_request->isPost()) {
      //  if ($frmMenuItem->isValid($_POST)) {
	       $data = $_POST;
            if($data['link'] == null){
	            $data['link'] = $this->_helper->string($data['name'],'-');
            }
        
          $options = array(
	        'parent'  => $this->_request->getPost ('parentID'),
	        'name'  => $this->_request->getPost ('name'),
	        'page_id'  => $this->_request->getPost ('page_id'),
	        'link'  => $this->_request->getPost ('link'),
	        'link_folder'  => $this->_request->getPost ('link_folder'),
	        'description'  => $this->_request->getPost ('description'),
	        'icon'  => $this->_request->getPost ('icon'),
	        'isHome'  => $this->_request->getPost ('is_conveyance_required'),
	        'lang'  => $this->_lang,
        ); 
           
            $mdlMenuItem->save($options, $id);
             $url =  APPLICATION_PATH . '/cache/menu/'.$this->_lang;
	        $this->_helper->cache($url);
            
            $this->_request->setParam('menu', $data['menu_id']);
            
            $url = '/admin/menuitem/index/menu/'. $data['menu_id'];
          $this->_redirect($url, array('prependBase' => false));
           //return $this->_forward('index');
      // }
} else {
	
	    $select_seo = 'SELECT * FROM seo WHERE id_object = "'.$id.'" and post_style="menuitem"';
        $rseo = $this->_db->fetchRow($select_seo);
  
       if ($rseo){
	  $this->view->assign($rseo);
	    }
        $frmMenuItem->populate($currentMenuItem->toArray());
      
   
    
    $this->view->form = $frmMenuItem;
}
}
	public function deleteAction() {
    $id = $this->_request->getParam ( 'id' );
    $mdlMenuItem = new Model_MenuItem ( );
    $currentMenuItem = $mdlMenuItem->find ( $id )->current ();
    $mdlMenuItem->deleteItem ( $id );
    $this->_request->setParam ( 'menu', $currentMenuItem->menu_id );
    $this->_forward ( 'index' );
	}
	
	public function homecatAction()
	{
		 $mdlMenuItem = new Model_MenuItem ( );
		 $select = $mdlMenuItem->select()
		 						->where('isHome = ?', 'Yes')
		 						->where('menu_id = ?', '5')
		 						->order('Hpos ASC');
		 						
		 $result = 	$mdlMenuItem->fetchAll($select);
		 
		 $this->view->result = $result;					
	}
	
	
	public function uploadAction(){
		
		   $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender();
     $cID = $_POST['catID'];
     $uploadDir = '/public/images/menuitems/';
     
     $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'webp');
				
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	
	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
				$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
			if(move_uploaded_file($tempFile, $targetFile)){
			$db = Zend_Db_Table::getDefaultAdapter();	
			
			$select = "SELECT image FROM menu_items WHERE id = $cID";
			  $module = $db->fetchAll($select);
			     if($module[0]['image'] != ''){
		 $link = $_SERVER['DOCUMENT_ROOT'].'/public/images/menuitems/'.$module[0]['image'];
		
		 	unlink($link);
		
	 		}
			
			$update = "update menu_items set image = '".$_FILES['Filedata']['name']."' where id = '".$cID."' ";
			$item = $db->query($update);
  
			$lastId = $db->lastInsertId();
        echo json_encode(array('tenfile' => $_FILES['Filedata']['name']));
			}
	
		
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("Đuôi mở rộng không hợp lệ");

	}
	
	 }

	}
	
	public function delitemAction(){
			  $this->_helper->viewRenderer->setNoRender(true);
			  $this->_helper->layout->disableLayout();
			  $data = $this->getRequest()->getPost('itemDel');
			  $PID = $this->getRequest()->getPost('pID');
			  
			  $select = "SELECT full FROM productImage WHERE imageId = $data";
			  $module = $this->_db->fetchAll($select);
			  
			   if($module[0]['full'] != ''){
		 $link = $_SERVER['DOCUMENT_ROOT'].'/public/images/backgrounds/'.$module[0]['full'];
		
		 	unlink($link);
		 	
		 $del = "delete from productImage where imageId = $data";
		 $this->_db->query($del);
	 }
	 		echo $module[0]['full'];
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
	
	
	public function sortAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$data = $this->getRequest()->getPost('dataString');

		echo $this->_helper->recursive($data);
	}
	
	public function toggleAction()
	{
		  $this->_helper->viewRenderer->setNoRender(true);
		  $this->_helper->layout->disableLayout(); 
		
		if($this->getRequest()->isXmlHttpRequest())
	      {
		 
		 	
		  $id_post = $this->_request->getPost('id');
		  $select = "select isHome from menu_items where id = $id_post";
		  $row = $this->_db->fetchRow($select);
		  if($row['isHome'] == "Yes"){
			 $update = "update menu_items set isHome = 'No' where id = $id_post";
		     
		  }else{
			  $update = "update menu_items set isHome = 'yes' where id = $id_post"; 
		  }
		  $this->_db->query($update); 
		// $update = "update menu_items set isHome = 1 where id_product = $id_post";
		 // $this->_db->query($update);
		 
		
		  echo $row['isHome'];;
		  }
	}

	
	public function seoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$id_post = $this->_request->getPost('id_object');
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
		 $insert = 'insert into seo (id_object, post_style, title, description, keyword) values ("'.$id_post.'", "menuitem", "'.$seo_title.'", "'.$seo_description.'", "'.$seo_keyword.'")';	
		$this->_db->query($insert);
		} 
	
		return true;
	}
	
	public function postDispatch(){
		$action = $this->getRequest()->getActionName();
		
		if ($action != 'index'){
			 $url =  APPLICATION_PATH . '/cache/menu/'.$this->_lang;
   
			@$this->_helper->cache($url);
		}
		
		
	}
		
   	
}