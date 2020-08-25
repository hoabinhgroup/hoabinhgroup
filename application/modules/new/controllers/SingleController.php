<?php
class New_SingleController extends Louis_Controller_Action
   {
	   	public function init(){		
		 parent::init();
		 $this->_helper->layout->setLayout('new');
		$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/review.css');
		}
	
       public function indexAction()
       {
	      $params = $this->_request->getParams();
	     
		 
	      $ident = $params['ident'];
	      
	      $menu = new Model_MenuItem();
	      $options = array(
		      'lang' => $this->_lang,
		      'link' => $ident,
	      );
	      $result = $menu->get_one_where($options);
		 if($result['link_folder'] != ''){
			  $this->_redirect('/'.$result['link_folder'].'/'.$ident);
		 }
	
	     // parent
	  /*   if($result->parent != 0){
		   $parent = $result->parent; 
		   }else{
			$parent = $result->id;   
		   } 
		     
	     $result_parent = $menu->get_one($parent); 
	     */
	   //  $this->view->menu_name = $result->name;
	   if($result){
 $seo_modal = new New_Model_Seo();		   
 $seo = $seo_modal->get_one_where(array('id_object' => $result->id, 'post_style' => 'menuitem'));
  	
  	$title = $result['name'];
	$keyword = '';
	$description = '';
  		
	 //seo
	if($seo != null){

	$title = ($seo->title != null)?$seo->title:$title;
	$keyword = ($seo->keyword != null)?$seo->keyword:$keyword;
	$description = ($seo->description != null)?$seo->description:$description;
	}
	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 

	      
	      $this->view->assign($result->toArray());
	      
	      	if($this->_lang == 'en'){
		   	$this->render('index-en');
	     	}
	   }else{
		   $this->_redirect('404.shtml');
		  	//throw new Zend_Controller_Action_Exception('File not found', 404);  
		// $this->_redirect('/404.shtml');
	   }   
	      //left menu
	       //echo $result->parent;
	       
	    /*   $options2 = array(
		       'lang' => $this->_lang,
		       'menu_id' => 2,
		       'parent' => $parent,
	       );
	     $result_left = $menu->get_details($options2);
	     $this->view->result = $result_left;
	     */
	      	       
       }
       
       public function viewAction()
       {
	       
	        $this->view->doctype();
  	       
	       $paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	 $module = $this->_request->getModuleName();
	   $this->_helper->translate($this->_lang);
	     $this->view->idents = $ident = $paramArray['ident'];
	  				
	   
	      $new = new New_Model_Product();

	      
	     $arr = $new->getValuesFromIdent($ident);
	  	  $this->view->parents = $arr['parent'];
	  	  $this->view->description_menu = $arr['description'];
	  	 
	      $this->view->lang = $this->_lang;
	      
	      $data = array();
	      $data = $new->getSingleByCategory($ident, $this->_lang);
	      if ( $arr['parent'] != 0){
		     $parent = $arr['parent'];
			 $this->view->menu_name = $new->getNameFromParent($parent);
			}else {
			 $parent = $arr['id'];
			 $this->view->menu_name = $new->getNameFromIdent($ident);
		}
		
		
		 $listnew = $new->getListFormParent($parent);
		  $this->view->result = $listnew;
		 
		  
		  if ($data != null){
			  
			   $data = $data[0];
			   
		
		  $this->view->assign($data);
		  
		  
		  if($data['shortDescription'] == null){
			  $seo_des = $data['name']. ' HoaBinh Group, '.$arr['description'];
		  }else{
			 $seo_des =  $data['shortDescription'];
		  }
		  
		  	      $seo_modal = new New_Model_Seo();
	      $seo = $seo_modal->getSeoFromId($data['id']);
	  
		  
		   //seo
	if($seo != null){
	$title = ($seo->title != null)?$seo->title:$data['name']." | HoaBinh Group";
	$keyword = ($seo->keyword != null)?$seo->keyword:$data['tags'];
	$description = ($seo->description != null)?$seo->description:$seo_des;
	}else{
	$title = $data['name']." | HoaBinh Group";
	$keyword = $data['tags'];
	$description = $seo_des;
	}
	
	$this->view->doctype('XHTML1_RDFA');	  
    $this->view->headTitle($title);
    $this->view->headMeta()->appendName("keywords",$keyword); 
    $this->view->headMeta()->offsetSetName("2","description",strip_tags($description));
	 $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', strip_tags($description)); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 	 
  
		  }else {
			 // throw new Zend_Exception('error!');
			 $this->view->message = 'updating';
		  }
		  
       }
       
       public function brandAction()
       {
	        $paramArray = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	       

	      $ident = 'thuong-hieu';
	      
	      $new = new New_Model_Product();
	      
	      $data = array();
	      $data = $new->getSingleByCategory($ident, $this->_lang);
	      
		  $listnew = $new->getlistNews($this->_lang);
		
		  $this->view->result = $listnew;
		  $data = $data[0];
		  
		  if ($data != null){
		  $this->view->assign($data);
		  }else {
			  $this->_redirect('404.shtml');
			 // throw new Zend_Exception('error!');
		  }
       }
       
       public function authorizeAction()
       {
	  
	      $this->_helper->layout->disableLayout();
	       
       }
       
       function fieldAction()
	   {
		
		   $this->view->headScript()->appendFile('/public/templates/news2017/default/js/timeline.js');	
		   $this->view->headScript()->appendFile('/public/js/slim.kickstart.min.js');	
		   $this->view->headScript()->appendFile('/public/js/readmore.min.js');	
		  
		   	$this->view->headLink()->prependStylesheet('/public/css/slim.min.css');
	
	       $params = $this->_request->getParams();
	 
		   $seo_modal = new New_Model_Seo();
	      $ident = $params['ident'];
	      
	      
	      $menu = new Model_MenuItem();
	      $options = array(
		      'image' => 'yes',
		      'lang' => $this->_lang,
		      'link' => $ident,
		      'limit' => 1
	      );
	      $result = $menu->get_details($options);
	 
	      if($result){
	      $result = $result[0];
	      if($params['folder'] == $result['link_folder']){
		      
	     
	    
	 $seo = $seo_modal->get_one_where(array('id_object' => $result['id'], 'post_style' => 'menuitem'));
  	
  	$title = $result['name'];
	$keyword = '';
	$description = '';
  		
	 //seo
	if($seo != null){

	$title = ($seo->title != null)?$seo->title:$title;
	$keyword = ($seo->keyword != null)?$seo->keyword:$keyword;
	$description = ($seo->description != null)?$seo->description:$description;
	}
	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'); 

	  
		
	      $this->view->assign($result);
	      
	      }else{
		    throw new Zend_Controller_Action_Exception('This page does not exist', 404);
	      }
	     }
	      

	      $timeline = new New_Model_Timeline();
	      $options2 = array(
	  			'image' => 'yes',
	  			'lang' => $this->_lang,
	  			'content_type' => 'timeline',
	  			//'order' => 'p.views desc',
	  			'order' => 'p.date desc',
	  			'status' => 1,
	  			'limit' => 20,
	  			'link' => $ident
  			);
  			
  			
  			$this->view->timeline = $res_timeline = $timeline->get_details($options2);
  			
  	
  			
  				if(($this->_lang == 'en') || ($params['lang'] == 'en')){
  				$this->render('field-en');
	     	}
  			
       }
}