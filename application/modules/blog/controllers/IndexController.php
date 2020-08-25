<?php 
class Blog_IndexController extends Louis_Controller_Action
{ 
		protected $_blogModel;
   
		public function init(){
			$this->_helper->layout->setLayout('blog');
			$this->_blogModel = new Blog_Model_Blog();
		}
		
		public function indexAction()
		{
			 $this->view->pages = $items = $this->_blogModel->getItems();
			 
		}
		
		public function viewAction()
		{
			
			$uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
			$blog = explode('/', $uri);
			$name = $blog[2];
  
			$data = $this->_blogModel->getContentByUrl($name)->toArray();
			
			$this->view->assign($data);
			
		}
}
		