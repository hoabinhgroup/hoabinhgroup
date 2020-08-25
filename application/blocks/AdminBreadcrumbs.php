 <?php
class Block_AdminBreadcrumbs extends Zend_View_Helper_Abstract{
    public function AdminBreadcrumbs(){
    	$uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
		$controlName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	   $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		$param = explode('/', $uri);
			$breadcrumbs = '<li><i class="icon-home home-icon"></i><a href="/admin">Home</a></li>';			
		 if ($controlName == 'admin-menuitem'){
		 	$menu = new Model_Menu();
		 	$menuItem = new Model_MenuItem();
		 	
			 $breadcrumbs .= '<li class="active"><a href="/admin/menu">Menu</a></li>';
			 //$breadcrumbs .= '<li class="active"><a href="/admin/menuitem/index/menu/'.(($actionName=='update')?$menuItem->getMenuById($param[5]):$param[5]).'">'.$nameMenu.'</a></li>';
			 	 if($actionName == 'add'){
			 	   $breadcrumbs .= '<li class="active"><a href="/admin/menuitem/index/menu/'.$param[5].'">'.$menu->getMenuById($param[5]).'</a></li>';
				  	$breadcrumbs .= '<li class="active">Thêm MenuItem mới</li>';
				  	
				  }elseif($actionName == 'update'){
				  $breadcrumbs .= '<li class="active"><a href="/admin/menuitem/index/menu/'.$menu->getMenuIdById($param[5]).'">'.$menu->getMenuById($param[5]).'</a></li>';
				  	$breadcrumbs .= '<li class="active">Cập nhật MenuItem</li>';
				  }
				  elseif($actionName == 'move'){
				  $breadcrumbs .= '<li class="active"><a href="/admin/menuitem/index/menu/'.$param[7].'">'.$menu->getMenuById($param[7]).'</a></li>';
				 
				  }elseif($actionName == 'homecat'){
				  $breadcrumbs .= '<li class="active"><a href="/admin/menuitem/index/menu/5">Quản lý Danh mục shop </a></li>';
				 
				  }
		 }elseif($controlName == 'admin-pagelist'){
			 $breadcrumbs .= '<li class="active"><a href="/admin/page/list">Page</a></li>';
			 	 if($actionName == 'create'){
				  	$breadcrumbs .= '<li class="active">Thêm trang mới</li>';
				  }elseif($actionName == 'edit'){
				  	$breadcrumbs .= '<li class="active">Cập nhật trang</li>';
				  }
		 }elseif($controlName == 'blog'){
			 $breadcrumbs .= '<li class="active"><a href="/admin/blog/list">Blog</a></li>';
			 	 if($actionName == 'create'){
				  	$breadcrumbs .= '<li class="active">Thêm trang mới</li>';
				  }elseif($actionName == 'edit'){
				  	$breadcrumbs .= '<li class="active">Cập nhật trang</li>';
				  }
		 }elseif($controlName == 'user'){
			 $breadcrumbs .= '<li class="active"><a href="/admin/user/list">User</a></li>';
			 	 if($actionName == 'create'){
				  	$breadcrumbs .= '<li class="active">Thêm user mới</li>';
				  }elseif($actionName == 'update'){
				  	$breadcrumbs .= '<li class="active">Cập nhật user</li>';
				  }elseif($actionName == 'password'){
				  	$breadcrumbs .= '<li class="active"><a href="/admin/user/update/id/'.$param[5].'">Cập nhật user</a></li>';
				  	$breadcrumbs .= '<li class="active">Cập nhật password</li>';
				  }
		 }elseif($controlName == 'admin-productlist'){
		 $product = new Product_Model_Product();
			  $breadcrumbs .= '<li class="active"><a href="/admin/product/list">Sản phẩm</a></li>';
			   if($actionName == 'photo'){
			 $breadcrumbs .= '<li class="active"><a href="/admin/product/edit/id/'.$param[5].'">'.$product->getProductById($param[5])->name.'</a></li>';   
			   }
		 }
		 
	
		 echo $breadcrumbs;
    }
    
  }