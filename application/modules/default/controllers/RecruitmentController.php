<?php
class RecruitmentController extends Louis_Controller_Action
   {
	   public function init()
	   {
		   parent::init();
         $this->_helper->layout->setLayout('new');
         
			$this->view->headLink()->setStylesheet(TEMPLATE_URL. '/default/css/news.css');
		
		
			$this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/uploadifive/uploadifive.css');  

						 
    	}
    
      	public function indexAction()
      	{
	      		$this->view->headScript()->appendFile('/public/templates/news2017/default/js/custom-rec.js?v=1.1');
	      	   $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.carousel.min.css');
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.theme.default.min.css');
	    $this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
	    $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/owl.carousel.min.js');
	     // $this->view->headLink()->appendStylesheet('/public/js/loadding/jquery.loading-indicator.css');
	      	// $this->view->headScript()->appendFile('/public/js/loadding/jquery.loading-indicator.min.js');  
		// $this->view->headScript()->appendFile('/public/js/loadding/jquery.loading-indicator.min.js');
		//  $this->view->headScript()->appendFile('/public/js/rec.js'); 
		  	
		  	      
		  $params = $this->getRequest()->getParams();	   
		 
	      $rec = new Model_Rec();
	      $rec = $rec->get_details();
	      $this->view->result = $rec;
	      
	      $this->view->doctype('XHTML1_RDFA');  // controller

		$title = $params['content_type']; 
		$keyword = '';  
		$description = '';
		$this->view->doctype('XHTML1_RDFA');  // controller
	$trang = '';
	$trang.= ' - Trang '. $params['page'];
	if($params['page'] > 1){
		$title .= $trang;
		
		if($keyword != ''){
			$keyword.= $trang;
		}
		
		if($description != ''){
			$description.= $trang;
		}
		
		}

	$this->view->headTitle($title);
	$this->view->headMeta()->appendName("keyword",$keyword); 
    $this->view->headMeta()->offsetSetName("1","description",$description); 
    
    $this->view->headMeta()->setProperty('og:title', $title); 
    $this->view->headMeta()->setProperty('og:description', $description); 
    $this->view->headMeta()->setProperty('og:type', 'website'. $trang); 
	      
	      if($params['lang'] == 'en'){
		      $this->render('index-en');
	      }
	     
      	}
		
		public function viewAction()
		{
			$this->_helper->layout->setLayout('rec_detail');
			$this->view->headLink()->appendStylesheet('/public/popup/css/slick-modal-min.css');
			 $this->view->headScript()->appendFile('/public/popup/js/jquery.slick-modal.min.js');
			$this->view->headLink()->appendStylesheet('/public/js/loadding/jquery.loading-indicator.css');
			
		 $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.carousel.min.css');
	     $this->view->headLink()->appendStylesheet(TEMPLATE_URL.'/default/css/owl.theme.default.min.css');
	    
	    $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/owl.carousel.min.js');
	      	// $this->view->headScript()->appendFile('/public/js/loadding/jquery.loading-indicator.min.js');  
		 $this->view->headScript()->appendFile('/public/js/loadding/jquery.loading-indicator.js');
		
		  //$this->view->headScript()->appendFile('/public/js/rec.js'); 
		  
		   $this->view->headScript()->appendFile('/public/js/md5.js');
		   $this->view->headScript()->appendFile(TEMPLATE_URL.'/uploadifive/jquery.uploadifive.min.js');
		  $this->view->headScript()->appendFile(TEMPLATE_URL.'/default/js/cv_upload.js');
		
		 
		    //echo __METHOD__;
		    $params = $this->getRequest()->getParams();
		    $rec_model = new Model_Rec();
		  
		    $where = array(
			    'ident' => $params['ident']
		    );
	      $rec = $rec_model->get_one_where($where);
	    
		   $val = $rec->toArray();
		   
		    $result_arr = array();
		     $rec_model->update_where(array('views' => new Zend_Db_Expr('`views` + 3')), array('id' => $val['id']));
          
		   $location = new Model_Location();
		   $location  = $location->getNameFromListId($val["location"]);
		   $company = new Model_Company();
		   $company = $company->get_one($val['company'])->title;	
		   $group = new Model_Group();
		   $group = $group->get_one($val['work_group'])->title;
		   
		   $result_arr = array(
			'title' => $val['title'],   
			'ident' => $val['ident'],   
			'people' => $val['people'],
			'salary' =>  $val['salary'],
          'exp' =>  $val['exp'],
          'deg' =>  $val['deg'],
          'job' =>  $val['job'],
          'work_style' =>  $val['work_style'],
          'sex' =>  $val['sex'],
          'age' =>  $val['age'],
          'contact' =>  $val['contact'],
          'email' =>  $val['email'],
          'address' =>  $val['address'],
          'mobile' =>  $val['mobile'],
			'expired' => date('d/m/Y',$val['expired']),
			'location' => $location,
			'company' => $company,
			'work_group' => $group,			
			'views' => $val['views'],			
			'description' => $val['description'],			
			);


	$this->view->doctype('XHTML1_RDFA');  // controller

	$this->view->headTitle($val['title']);
    $this->view->headMeta()->offsetSetName("1","description",$this->_helper->string->cut_string(strip_tags($val['description']),'160','...')); 
    //160
    $this->view->headMeta()->setProperty('og:title', $val['title']); 
    $this->view->headMeta()->setProperty('og:description', $this->_helper->string->cut_string(strip_tags($val['description']),'160','...')); 
    $this->view->headMeta()->setProperty('og:type', 'Tuyển dụng'); 
		 $this->view->assign($result_arr);
		    
		   
		   
		}	

		public function responseAction()
		{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		/* $rec_first = new Model_Rec();
		  $options = array(
			   'keyword' => 'tư vấn'
		   );
	      $rec = $rec_first->get_details($options);
		
		echo "<pre>";
		print_r($rec);
		echo "</pre>";
		*/
		if($this->getRequest()->isXmlHttpRequest())
	       {
		      
		    $page = $this->_request->getPost('page'); 
		    $keyword = $this->_request->getPost('keyword'); 
		    $work_group = $this->_request->getPost('work_group'); 
		    $location = $this->_request->getPost('location'); 
		    $company = $this->_request->getPost('company'); 
		    
		   $rec_first = new Model_Rec();
		   $options = array(
			   'keyword' => $keyword,
			   'work_group' => $work_group,
			   'location' => $location,
			   'company' => $company,
			   'status' => 1,
		   );
	      $rec = $rec_first->get_details($options);
	       $count = count($rec);
		    
		     $config = array(
    'current_page'  => isset($page) ? $page : 1,
    'total_record'  => $count, // tổng số
    'limit'         => 10,
    'link_full'     => '/tuyen-dung/{page}',
    'link_first'    => '/tuyen-dung',
    'range'         => 9
			);
			
			
		
			
			
			$paging = new Louis_Pagination();
			$paging->init($config);
		   
		   $per_page = $paging->get_config('limit');
		   $optionsx['per_page'] = $per_page;
		   $optionsx['page'] = $page;
		    $optionsx['keyword'] = $keyword;
		   $optionsx['work_group'] = $work_group;
		   $optionsx['location'] = $location;
		   $optionsx['company'] = $company;
		   $optionsx['status'] = 1;
		   
		   $rec2 = $rec_first->get_details($optionsx);
		   
		   $result_arr = array();
		   if(count($rec2) > 0){
		   foreach($rec2 as $key=>$val):
		   $location = new Model_Location();
		   $location  = $location->getNameFromListId($val['location']);
		   $company = new Model_Company();
		   $company = $company->get_one($val['company'])->title;	
		   $group = new Model_Group();
		   $group = $group->get_one($val['work_group'])->title;
		   $featured = $val['featured'];
		   
		   $result_arr[] = array(
			'title' => $val['title'],   
			'ident' => $val['ident'],   
			'people' => $val['people'],
			'expired' => date('d/m/Y',$val['expired']),
			'location' => $location,
			'company' => $company,
			'featured' => $featured,
			'work_group' => $group			
			);

		   endforeach;
		   }
		   
		
		   
		   	echo json_encode(array(
        'rec' => $result_arr,
        'post' => $_POST,
        'paging' => $paging->html(),
        'count' => $count
		));
	
		   }
		}
		
		
		public function submitAction()
		{
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			
			try{
			
			
	
			$db = Zend_Db_Table::getDefaultAdapter();
			$id = $this->_request->getParam('id');
			$name = $this->_request->getPost('name');
			$email = $this->_request->getPost('email');
			$address = $this->_request->getPost('address');
			$phone = $this->_request->getPost('phone');
			$message = $this->_request->getPost('message');
			$link = $this->_request->getPost('link');
			
			$filename = $this->_request->getPost('filename');
			$filetype = $this->_request->getPost('filetype');
			
			
	        $html = new Zend_View();
			$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/emails/');
      
			// assign valeues
	 		 $html->assign('name', $name);
	 		 $html->assign('email', $email);
	 		 $html->assign('address', $address);
	 		 $html->assign('phone', $phone);
	 		 $html->assign('message', $message);
	 		 $html->assign('link', $link);
	  
	  $bodyText = $html->render('join_template.phtml');
			
				$connmail = new Zend_Mail_Transport_Smtp ( 'smtp.gmail.com', array ('auth' => 'login', 'username' => 'hoabinhwebmaster@gmail.com', 'password' => 'whlfemisfxmhwewt', 'ssl' => 'tls', 'port' => 587 ) );
				
			Zend_Mail::setDefaultTransport ( $connmail );
			$mail = new Zend_Mail ( 'UTF-8' );
			
			$mail->setBodyHtml($bodyText);
			$mail->setFrom ( 'hr@hoabinh-group.com','Hòa Bình Group');
		    $mail->addTo ( 'hr@hoabinh-group.com' );
			$mail->addCc($email);
			$mail->setSubject ( $name );
			
			if($filename != null){
			
			 $files = 'public/files/'.$filename;
			 $at = new Zend_Mime_Part(file_get_contents($files));
			// $at->type        = mime_content_type($file);
			 $at->type        = $filetype;
			// $at->type        = 'application/pdf';
			 $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
			 $at->encoding    = Zend_Mime::ENCODING_BASE64;
			 $at->filename    = basename($files);
			// $at->filename    = 'tuan';
			 $mail->addAttachment($at); 
			 }
			
			$mail->send();
			
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
		
		//	echo $id;
		return true;
		
		
		}
		
		
				public function uploadAction()
		{
			
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
		
			//$timestamp = time();
			//echo mime_content_type('public/files/26647-AsCA2016-Abstract_BASAB.doc');
		$uploadDir = '/public/files/';
		
		$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'doc', 'pdf', 'docx', 'zip');
				
	    $verifyToken = md5('unique_salt' . $_POST['timestamp']);
	   
	    	 if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile   = $_FILES['Filedata']['tmp_name'];
		
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];
	

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

			if(move_uploaded_file($tempFile, $targetFile)){
			
		 echo json_encode($_FILES);
     
			}
	  
	
		} else {
        
		// Duoi mo rong khong hop le
		echo output_message("Đuôi mở rộng không hợp lệ");

	}
	
	 }

		}

}
