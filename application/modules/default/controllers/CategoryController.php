<?php
class CategoryController extends Louis_Controller_Action
   {
       public function indexAction()
       {
           $id = $this->_getParam('categoryId', 0);
           $catalogModel = new Model_Catalog();
           $this->view->categories =
               $catalogModel->getCategoriesByParentId($id);
           $this->_helper
                ->viewRenderer
                ->setResponseSegment(
                    $this->_getParam('responseSegment')
					);
		}
		
		public function viewAction()
		{
		//echo __METHOD__;
			$this->view->uri = $uri = Zend_Registry::get('uri');
			//$identUri = '';
			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	
			
			$cat = explode('/', $uri);
			$name = $cat[2];
			$db = Zend_Db_Table::getDefaultAdapter();
			
			
		

			
		/*
			if (!empty($cat[4]) && $cat[4] != 'sort')
			{
				throw new Zend_exception('error');
			}
			
			if (!empty($cat[6]) && $cat[6] != 'order')
			{
				throw new Zend_exception('error');
			}
		*/	
		
			if (!isset($paramArray['page'])){
			$pageCurrent = 1;
			}else {
  			$pageCurrent = $paramArray['page'];
  		    }
		if (!isset($paramArray['ident2'])){
			$ident = $cat[1].'/'.$paramArray['ident'];
	
		}else {
			$ident = $cat[1].'/'.$paramArray['ident'].'/'.$paramArray['ident2'];
				
			}
			
			$se = 'select id, name, description from menu_items where link = "'.$ident.'"';
			$s = $db->fetchRow($se);
			$this->view->sID =  $s['id'];
			$this->view->sNAME =  $s['name'];
			$this->view->description =  $s['description'];
			
			//echo $s['name'];
  	
  			if (isset($paramArray['sort'])){
  			$sort = $paramArray['sort'];
  			}else {
  			$sort = null;
  			}
  
  			if (isset($paramArray['order'])){
  			$order = $paramArray['order'];
  			}else {
  			$order = null;
  			}
  
  	echo $this->view->current = $ident.'/'.$paramArray['page'];
  
    $product = new Product_Model_Product();
    
	$data = $product->getProductsByCategory($ident, $sort, $order);
	
	
	
	$title = $s['name'].' | Hoa Binh Tour';
	$keyword = $s['name'].' Touris, travel MICE';
	$description = ($s['description'] != '')?'Category '.$this->_helper->string->cut_string(strip_tags($s['description']),'150','...'):'Category '.$s['name'];

	
	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 

	 
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	 
	$paginator->setItemCountPerPage(8);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
    
    
		}	
		
		
		public function viewhotelAction()
		{
		//echo __METHOD__;
		
			$this->view->uri = $uri = Zend_Registry::get('uri');
			//$identUri = '';
			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
			$cat = explode('/', $uri);
			$name = $cat[2];
			$db = Zend_Db_Table::getDefaultAdapter();
			
		
			if (!empty($cat[6]) && $cat[6] != 'sort')
			{
				throw new Zend_exception('error');
			}
			
		
			if (!isset($paramArray['page'])){
			$pageCurrent = 1;
			}else {
  			$pageCurrent = $paramArray['page'];
  		    }
		if (!isset($paramArray['ident2'])){
			$ident = $cat[1].'/'.$paramArray['ident'];
	
		}else {
			$ident = $cat[1].'/'.$paramArray['ident'].'/'.$paramArray['ident2'];
				
			}
			
			$se = 'select id, name from menu_items where link = "'.$ident.'"';
			$s = $db->fetchRow($se);
			$this->view->sID =  $s['id'];
			$this->view->sNAME =  $s['name'];
			
			
				$title = $s['name'].' | Hoa Binh Hotel';
				$keyword = $s['name'].' Touris, travel MICE, hotel hoabinh';
				$description = 'Hotel '.$s['name'];
				
				$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description);

  	
  			if (isset($paramArray['sort'])){
  			$sort = $paramArray['sort'];
  			}else {
  			$sort = null;
  			}
  
  			if (isset($paramArray['order'])){
  			$order = $paramArray['order'];
  			}else {
  			$order = null;
  			}
  
  	//$this->view->current = '/'.$paramArray['controller'].'/'.$paramArray['ident'].'/'.$paramArray['page'];
  
    $product = new Hotel_Model_Product();
    
	$data = $product->getProductsByCategory($ident, $sort, $order);

	 
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	 
	$paginator->setItemCountPerPage(5);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }
    
   
		}
	
	public function getchildAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$db = Zend_Db_Table::getDefaultAdapter();
			$id = $this->_request->getPost('parent_id');
	
	$query  = "select * from menu_items where menu_id = 2 and parent = ".$id;
	$result = $db->fetchAll($query);

	if($result)
	{?>
		
		<option value="" selected="selected"> -- Select Style Touris --</option>
		<?php
			if ($id != 0) {
		foreach($result as $key=>$val):
		?>
			<option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
		<?php
		endforeach;
		}
		?>
	
		
	<?php	
	}
	

	}
		
}
