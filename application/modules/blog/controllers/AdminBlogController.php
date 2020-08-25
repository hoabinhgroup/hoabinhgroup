<?php 
class Blog_AdminBlogController extends Louis_Controller_Action
{ 	
		public function init()
		{
			$this->_blogModel = new Blog_Model_Blog();
			$this->_blogForm = new Blog_Form_Blog();
		}
	
		public function indexAction()
		{
			echo __METHOD__;
			 
		}
		
		public function createAction()
		{
			//echo __METHOD__;
			if($this->getRequest()->isPost()) 
				{
				if($this->_blogForm->isValid($_POST)) 
					{
			$data = $this->_blogForm->getValues(); 
          
            $itemBlog = new Louis_Content_Item_Blog();
            $itemBlog->name = $this->_blogForm->getValue('name');
            $itemBlog->name_clean = $this->_blogForm->_helper->string($this->_blogForm->getValue('name'),'-');
            $itemBlog->description = $this->_blogForm->getValue('description');
            $itemBlog->content = $this->_blogForm->getValue('content');
            $itemBlog->author_id = $this->_blogForm->getValue('authorID');
      
            if($this->_blogForm->image->isUploaded())
            	{
                $this->_blogForm->image->receive();
                $itemBlog->photo = '/images/blog/' .
                    basename($this->_blogForm->image->getFileName());
				}
        
            $itemBlog->save();
            return $this->_forward('list');
					} 
				}
			$this->_blogForm->setAction('/admin/blog/create');
    	$this->view->form = $this->_blogForm;
		}
		
		public function editAction() {
    $this->_blogForm->setAction('/admin/blog/edit');
    $this->_blogForm->setMethod('post');
    if($this->getRequest()->isPost()) {
        if($this->_blogForm->isValid($_POST)) {
           
          $data = array(
          'name' =>  $this->_blogForm->getValue('name'),
          'name_clean' =>  $this->_helper->string($this->_blogForm->getValue('name'),'-'),
          'description' =>  $this->_blogForm->getValue('description'),
          'content' =>  $this->_blogForm->getValue('content'),
          'author_id' =>  $this->_blogForm->getValue('authorID'),
          'photo' =>  $this->_blogForm->getValue('image'),
          'parent_id' =>  0,
          );
            $result = $this->_blogModel->updatePage($this->_blogForm->getValue('id'), $data);
            return $this->_redirect('/admin/blog/list');
        }
    } else {
        $id = $this->_request->getParam('id');
        $page = $this->_blogModel->find($id)->current();
        $this->_blogForm->populate($page->toArray());
        //format the date field
       // $blogForm->getElement('date')->setValue(date('m-d-Y', $page->date));
    }
    $this->view->form = $this->_blogForm;
	}
			
		public function deleteAction(){
		$id = $this->_request->getParam('id');
         $itemBlog = new Louis_Content_Item_Blog($id);
         $itemBlog->delete();
	       return $this->_redirect('/admin/blog/list');   
         
         
	}
		
		
		public function listAction()
		{
			//echo __METHOD__;

			$select = $this->_blogModel->select();
			$select->where('namespace = ?', 'blog');
			$select->order('name');
    
			$currentPages = $this->_blogModel->fetchAll($select);
			if($currentPages->count() > 0) {
			$this->view->pages = $currentPages;
			}else{
			$this->view->pages = null;
         	}

		}
	
		
	
}
		