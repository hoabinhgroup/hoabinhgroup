<?php 
class AdminPagesController extends Louis_Controller_Action 
{ 
   
		public function init(){
			parent::init();
		}
		
		public function createAction()
		{
			//echo __METHOD__;
			
			$pageForm = new Form_Page();
			$pageModel = new Model_Page();
			if($this->getRequest()->isPost()) {
			 $data = array(
          'name' =>  $_POST['name'],
          'name_clean' =>  $_POST['name_clean'],
          'description' =>  $_POST['description'],
          'date_created' =>  time(),
          'publish' => 1,
          'namespace' => 'page',
          'lang' => $this->_lang
          );   
          
		   $pageModel->save($data);
		   if($pageModel){
		   return $this->_redirect('/admin/pages/list');
		   }
	 	}
	 		$pageForm->setAction('/admin/pages/create');
	 		$pageForm->setMethod('post');
	 		$this->view->form = $pageForm;
    
	}
	
	

	public function editAction() {
		
    $pageForm = new Form_Page();
    $pageForm->setAction('/admin/pages/edit');
    $pageForm->setMethod('post');
     $pageModel = new Model_Page();
    // $id = $pageModel->find($id)->current();
     
    if($this->getRequest()->isPost()) {
        if($pageForm->isValid($_POST)) {
           
          $data = array(
          'name' =>  $pageForm->getValue('name'),
          'description' =>  $pageForm->getValue('description'),
          'content' =>  $pageForm->getValue('content'),
          'namespace' =>  'page',
          'lang' =>  $this->_lang,
          'parent_id' =>  0,
          );
            $result = $pageModel->updatePage($pageForm->getValue('id'), $data);
            return $this->_redirect('/admin/pages/list');
        }
    } else {
        $id = $this->_request->getParam('id');
        $page = $pageModel->find($id)->current();
        $pageForm->populate($page->toArray());
        //format the date field
       // $pageForm->getElement('date')->setValue(date('m-d-Y', $page->date));
    }
    $this->view->form = $pageForm;
}


	public function deleteAction(){
		$id = $this->_request->getParam('id');
		$pageModel = new Model_Page();
        // $itemPage = new Louis_Content_Item_Page($id);
         $pageModel->deletePage($id);
	       return $this->_redirect('/admin/pages/list');   
         
         
	}
	
	
	public function listAction()
	{
    $pageModel = new Model_Page();
    // fetch all of the current pages
    $select = $pageModel->select();
    $select->where('lang = ?', $this->_lang);
    $select->order('name');
    
    $currentPages = $pageModel->fetchAll($select);
    if($currentPages->count() > 0) {
        $this->view->pages = $currentPages;
    }else{
        $this->view->pages = null;
    }
}
	
	
	
		
}