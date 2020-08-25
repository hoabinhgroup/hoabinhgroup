<?php
class CommentController extends Louis_Controller_Action
   {
      
		
		public function viewAction()
		{
		   // echo __METHOD__;
		   $this->_helper->viewRenderer->setNoRender(true);
		   $this->_helper->layout->disableLayout();
		   
		   $userID = $this->_request->getPost('uid');
		   $text = $this->_request->getPost('text');
		   $url = $this->_request->getPost('url');
		   $url = explode('.com', $url);
		   $postID = $this->_request->getPost('postID');
		   $commentID = $this->_request->getPost('commentID');
		   $time = time();
		   $db = Zend_Db_Table::getDefaultAdapter();
		   $insert = 'insert into comments (id_user, id_comment, id_post, content, url, time) values ("'.$userID.'","'.$commentID.'","'.$postID.'","'.$text.'","'.$url[1].'","'.$time.'")';
		   $db->query($insert);
		   
		   return true;
		}	
		
		public function deleteAction(){
			$this->_helper->viewRenderer->setNoRender(true);
		   $this->_helper->layout->disableLayout();
		   
		   $commentID = $this->_request->getPost('commentID');
		   $db = Zend_Db_Table::getDefaultAdapter();
		   $delete = "delete from comments where id_comment = $commentID";
		   $db->query($delete);
		    return true;
		}
		
}
