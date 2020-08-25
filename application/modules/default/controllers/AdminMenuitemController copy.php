<?php
class AdminMenuitemController extends Louis_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
         parent::init();
    }
    public function indexAction()
	{
   $menu = $this->_request->getParam('menu');
   $mdlMenu = new Model_Menu();
   $mdlMenuItem = new Model_MenuItem();
   $this->view->menu = $mdlMenu->find($menu)->current();
   $this->view->items = $mdlMenuItem->getItemsByMenu($menu, $this->_lang);
   }
   
   public function imageAction()
   {
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
			$db = Zend_Db_Table::getDefaultAdapter();	
			
		
	 		}
			
			$insert = "insert into productImage (full, isDefault) values ('".$_FILES['Filedata']['name']."', 'No')";
			$item = $db->query($insert);
  
			$lastId = $db->lastInsertId();
			
			$insert_menu_items = "insert into menu_item_images (id_menu_item, id_product_image) values ('".$cID."', '".$lastId."')";
			$db->query($insert_menu_items);
        echo json_encode(array('tenfile' => $_FILES['Filedata']['name'], 'idfile' => $lastId));
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
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$update = "update menu_item_images set caption = '".$caption."' where id = $item";
			$db->query($update);
			
			return true;
	}
	
	
	public function orderAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			 $item = $this->_request->getPost('item');
			 $order = $this->_request->getPost('order');
			
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$update = "UPDATE `menu_item_images` SET `order` = '$order' WHERE `menu_item_images`.`id` =$item;";
			$db->query($update);
			
			return true;
	}
	
	public function urlAction()
	{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			$item = $this->_request->getPost('item');
			$url = $this->_request->getPost('url');
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$update = "update menu_item_images set url = '".$url."' where id = $item";
			$db->query($update);
			
			return true;
	}
	 
  
   
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
               $data['page_id'], $data['link'],$data['description'], $data['is_conveyance_required'],$this->_lang);
            $this->_request->setParam('menu', $data['menu_id']);
	            $this->_forward('index');
		
	 }

   $frmMenuItem->populate(array('menu_id' => $menu));
   $this->view->form = $frmMenuItem;
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
    $id = $this->_request->getParam('id');
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
       // if ($frmMenuItem->isValid($_POST)) {
	       $data = $_POST;
            if($data['link'] == null){
	            $data['link'] = $this->_helper->string($data['name'],'-');
            }
           
            $mdlMenuItem->updateItem($data['id'], $data['name'], $data['parentID'],
               $data['page_id'], $data['link'], $data['description'],  $data['is_conveyance_required'], $this->_lang);
            $this->_request->setParam('menu', $data['menu_id']);
            return $this->_forward('index');
    //   }
} else {
        $frmMenuItem->populate($currentMenuItem->toArray());
    }
    $this->view->form = $frmMenuItem;
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
			  $db = Zend_Db_Table::getDefaultAdapter();
			  
			  $select = "SELECT full FROM productImage WHERE imageId = $data";
			  $module = $db->fetchAll($select);
			  
			   if($module[0]['full'] != ''){
		 $link = $_SERVER['DOCUMENT_ROOT'].'/public/images/backgrounds/'.$module[0]['full'];
		
		 	unlink($link);
		 	
		 $del = "delete from productImage where imageId = $data";
		 $db->query($del);
	 }
	 		echo $module[0]['full'];
	}	
	
	public function defaultAction(){
		$this->_helper->layout->disableLayout();
		$data = $this->getRequest()->getPost('itemDef');
	    $PID = $this->getRequest()->getPost('pID');
	    
	    $db = Zend_Db_Table::getDefaultAdapter();
			$setDefault = "update productImage set isDefault = 'Yes' where imageId = $data";
  
			  $select = "update productImage as i inner join menu_item_images as s on i.imageId = s.id_product_image set i.isDefault = 'No' where i.imageId != $data and s.id_menu_item = $PID";
			  $db->query($setDefault);
			 $db->query($select);
	 
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