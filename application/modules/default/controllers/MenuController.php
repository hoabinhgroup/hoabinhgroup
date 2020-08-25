<?php
class MenuController extends Louis_Controller_Action{
    public function init()
    {
        /* Initialize action controller here */
         // $this->view->loo = 45;
      
       
    }
    public function indexAction()
    {
    /*
    	  $auth = Zend_Auth::getInstance();
          if (!$auth->hasIdentity()) {
              if ($this->_request->getActionName() != 'login') {
                  $this->_redirect('/admin/user/login');
              }
          }
    	*/
    	
   

	
    }
    
    public function createAction()
	{
	
    $frmMenu = new Form_Menu();
    if($this->getRequest()->isPost()) {
        if($frmMenu->isValid($_POST)) {
            $menuName = $frmMenu->getValue('name');
            $mdlMenu = new Model_Menu();
            $result = $mdlMenu->createMenu($menuName);
            if($result) {
                // redirect
               $this->_forward('index');
            }
		} }
    $frmMenu->setAction('admin/menu/create');
    $this->view->form = $frmMenu;
	}
	
	public function editAction()
	{
    $id = $this->_request->getParam('id');
    $mdlMenu = new Model_Menu();
    $frmMenu = new Form_Menu();
    
     if($this->getRequest()->isPost()) {
        if($frmMenu->isValid($_POST)) {
            $menuName = $frmMenu->getValue('name');
            $mdlMenu = new Model_Menu();
            $result = $mdlMenu->updateMenu($id, $menuName);
            if($result) {
                // redirect to the index action
                return $this->_forward('index');
            }
			} 
	}else{

    // fetch the current menu from the db
    $currentMenu = $mdlMenu->find($id)->current();
    // populate the form
    $frmMenu->getElement('id')->setValue($currentMenu->id);
    $frmMenu->getElement('name')->setValue($currentMenu->name);
    
    }
    $frmMenu->setAction('/admin/menu/edit');
    // pass the form to the view to render
    $this->view->form = $frmMenu;
	}
	
	public function deleteAction()
			{
			$id = $this->_request->getParam('id');
			$mdlMenu = new Model_Menu();
			$mdlMenu->deleteMenu($id);
			if($mdlMenu){
			return $this->_forward('index');
			}
			}

	public function renderAction()
	{
    $menu = $this->_request->getParam('menu');
    $mdlMenuItems = new Model_MenuItem();
    $menuItems = $mdlMenuItems->getItemsByMenuParent($menu);
    if(count($menuItems) > 0) {
        foreach ($menuItems as $item) {
            $label = $item->name;
            if(!empty($item->link)) {
                $uri = '/'. ADMIN .'/'.$item->link;
            }else{
                $uri = '/page/open/id/' . $item->page_id;
            }

				$itemArray[] = array(
                'label'  => $label,
                'title'  => $label,
                'uri'  => $uri,
                'pages' => array()
				); 
				
				$idMenu[] = $item->id;
 			}

			foreach ($idMenu as $kmenuID=>$vmenuID):
			 $menuItem = new Model_MenuItem();
			 $select = $menuItem->select();
			 $select->where('parent = ?', $vmenuID);
			
			 $submenu = $menuItem->fetchAll($select);
			 $count = count($submenu);
			 if($count > 0){
				$sub = $submenu->toArray();
			  foreach ($sub as $ksmenuID=>$vsmenuID):
			     $submenuArr[] = array(
			     'label' => $vsmenuID['name'],
			     'title' => $vsmenuID['name'],
			     'uri' => '/'. ADMIN .'/'.$vsmenuID['link'],
			     );	     
			  endforeach;
			  $itemArray[$kmenuID]['pages'] = $submenuArr;		
			  unset($submenuArr);			 
			 }
			endforeach;
			 			
			
        $container = new Zend_Navigation($itemArray);
        $this->view->navigation()->setContainer($container);
    }
    
    // $this->getHelper('ViewRenderer')->setNoRender();
		}
		
		
	
	
}