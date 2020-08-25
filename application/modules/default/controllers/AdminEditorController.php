<?php 
class AdminEditorController extends Louis_Controller_Action 
{ 
   
		public function init(){
		parent::init();
	$this->view->headScript()->appendFile('http://code.jquery.com/jquery-1.9.1.js');
		}
		
		public function indexAction()
		{
			
		}
	

		
		public function layoutAction()
		{/*
		$this->view->headScript()->appendFile('http://vietnamevents.com/public/codemirror/jquery-linedtextarea.js');
			$this->view->headLink()->setStylesheet('http://vietnamevents.com/public/codemirror/jquery-linedtextarea.css');
			*/
			
			$this->view->headScript()->appendFile('https://cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/ace.js');
			
			
			
			$layout = $_GET['template'];
			
			$this->view->layout = $layout;
		
			$this->view->lang = $this->_lang;
			
		}
		
		public function formAction()
		{
			
		}
	
		
}