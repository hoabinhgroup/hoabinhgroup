<?php
class TagController extends Louis_Controller_Action
   {
      public function init()
      {
	      $this->_helper->layout->setLayout('new');
			$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/news.css');
		 parent::init();
      }
		
		public function viewAction()
		{
		//echo __METHOD__;
			$this->view->uri = $uri = Zend_Registry::get('uri');
			//$identUri = '';
			$paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();

			$cat = explode('/', $uri);
			//$name = $cat[2];
			$db = Zend_Db_Table::getDefaultAdapter();
			
	
			if (!empty($cat[4]) && $cat[4] != 'sort')
			{
				throw new Zend_exception('error sort');
			}
			
			if (!empty($cat[6]) && $cat[6] != 'order')
			{
				throw new Zend_exception('error order');
			}
			
		
		
			if (!isset($paramArray['page'])){
			$pageCurrent = 1;
			}else {
  			$pageCurrent = $paramArray['page'];
  		    }
		
  			
  			$ident = $paramArray['ident'];
  
  $this->view->current = 'tag/'.$ident.'/'.$paramArray['page'];
  
  $selectcount = 'select name,view from tags where ident = "'.$ident.'"';
  $re_selectcount = $db->fetchRow($selectcount);

$this->view->tags = $re_selectcount;
  
  $cselect = $re_selectcount['view'] + 1;
  
  $updatesql = 'update tags set view = "'.$cselect.'" where ident = "'.$ident.'"';
  $db->query($updatesql);

  
    $product = new New_Model_Product();
    
    // chuyên mục
     $mdlMenuItem = new Model_MenuItem();
	  $menu = $mdlMenuItem->getItemsByMenu(2, $this->_lang);
	  
     $categories = array();
	  foreach($menu->toArray() as $key =>$val):
	  if($val['parent'] == 7){
	  $categories[] = $val;
	  }
	  endforeach;

	   $this->view->categories = $categories;
	   
	   
    
	$data = $product->getProductsByTag($ident);
	
	
	  $this->view->doctype();
     $this->view->headTitle("Hoabinh group | ".$re_selectcount['name']);

	 
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
		

		
}
