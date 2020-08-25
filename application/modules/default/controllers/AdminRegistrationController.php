<?php
class AdminRegistrationController extends Louis_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
       //  $this->view->headScript()->appendFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js');
        $this->view->headScript()->appendFile('/public/assets2/js/jquery-2.1.4.min.js');
         parent::init();
    }
    public function indexAction()
	{
		$registration = new Model_Registration();
	
    	$result = $registration->get_details();
		
		$this->view->registration = $result;
	}
	
	
	
	public function statusAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		 $this->_helper->layout->disableLayout();
		 if($this->getRequest()->isXmlHttpRequest())
		 		{
		 	
		 	$params = $this->_request->getParams();
		 	$registration = new Model_Registration();
		 	$id = $params['id'];	
		 	$status = $params['status'];
		 	if($id){
			 $registration->save(array('status' => $status), $id);	
		 	}	
		 	$this->_helper->json(array('success' => true));	
		 		}
	}
	
	public function detailAction()
	{
		
		 $id = $this->_request->getParam('id');
		 $mdlRegistration = new Model_Registration();
		 
		 $row = $mdlRegistration->get_one($id);
		 
		 $rows = $row->toArray();
		 
		$rows['used_service'] = $this->getServiceName(explode(',',$rows['used_service']));
		 
		$rows['calitext'] =  $this->getcaligraphy($rows['calitext']);
		 
		 $this->view->assign(array('row' => $rows));
		 
	}
	
	 public function getServiceName($serviceIds){
	     $services = array(
		    'Chưa chọn',
		    'Tổ chức sự kiện',
		    'Tổ chức hội nghị',
		    'Thuê thiết bị',
		    'Tour du lịch trong / ngoài nước',
		    'Thuê xe ô tô',
		    'Đặt vé máy bay',
		    'Đặt tiệc trà & Teabreak',
	    );
	   
	    $serviceFilter = array_map(function($item) use ($services){
		    	    
		     return $services[$item];
	    }, $serviceIds);
	    
	    return implode('<br/>',$serviceFilter);
    }
	
	 public function getcaligraphy($id)
    {
	
	    	$data = array(
		    	'Không chọn',
		    	'Chữ Hòa',
		    	'Chữ Trí',
		    	'Chữ Đạt',
		    	'Chữ Hạnh',
		    	'Chữ Phát',
		    	'Chữ Nhẫn',
		    	'Chữ Đức',
		    	'Chữ An',
		    	'Chữ Lộc',
		    	'Chữ Phúc',
		    	'Chữ Vượng',
		    	'Chữ Tâm'
	    	);	
	    	return $data[$id];	
	    	
    }
	
	public function deleteAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		 $this->_helper->layout->disableLayout();
		 $id = $this->_request->getParam ( 'id' );
		 if($id){
		 $registration = new Model_Registration();
		 $registration->save (array("deleted" => 1 ),$id);
		 }
		 $this->_redirect ( 'admin/registration' );
	}
		
   	
}