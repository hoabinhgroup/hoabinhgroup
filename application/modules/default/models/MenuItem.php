<?php
class Model_MenuItem extends Louis_Db_Table_Abstract {
   	 protected $_name = 'menu_items';
       	
protected $_referenceMap = array(
    'Menu' => array(
        'columns'	=> array('menu_id'),
        'refTableClass'	=> 'Model_Menu',
        'refColumns' => array('id'),
        'onDelete'	=> self::CASCADE,
        'onUpdate' => self::RESTRICT

) );
	

		public function init()
		{
			parent::init();
		}
		
		public function get_details($options = array()) {

       
         $select = $this->_db->select()
        					->from(array('m' => $this->_name));
        					
         $image = $this->get_array_value($options, "image");	
        if($image){				
		 $select->joinInner(
		 				'productImage as i',
		 				'm.id = i.productId', array("full","caption"));
			$select->where('i.isDefault = ?', 'Yes')
			       ->where('i.content_type = ?', 'menu');
			}					
        
        $link = $this->get_array_value($options, "link");
        if ($link) {
           $select->where('m.link = ?', $link);
        } 	
        
        
        $lang = $this->get_array_value($options, "lang");
         if ($lang) {
           $select->where('m.lang = ?', $lang);
        }
        
        $parent = $this->get_array_value($options, "parent");
         if ($parent) {
           $select->where('parent = ?', $parent);
        }
        
                
           $isHome = $this->get_array_value($options, "isHome");
        if ($isHome) {
            $select->where('m.isHome = ?', $isHome);
        } 
        
         $menu_id = $this->get_array_value($options, "menu_id");
         if ($menu_id) {
           $select->where('m.menu_id = ?', $menu_id);
        }
        
         $select->order('position asc');  
		 $select->group('m.id');
       
           $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        } 	
             
          
        
       	$result = $this->_db->fetchAll($select);	
       	
       	 return $result;
    }	

	public function itemInSelectbox($name, $value = null, $options, $default='None', $attribs = array()){
			 	$strAttribs = '';
	  if(count($attribs) > 0){
		  foreach($attribs as $key =>$val){
			  $strAttribs .= $key.' = "'. $val . '"';
		  }
	  }
	   // $xhtml = '<div class="styled-select">';
		$xhtml = '<select name="'.$name.'" class="chosen-select form-control" id="'.$name.'" '.$strAttribs.'>';
		$xhtml .='<option label="'.$default.'" value="0">'.$default.'</option>';
		 foreach($options as $key=>$info):
		 $strSelect = '';
		 	if ($info['id'] == $value){
			 $strSelect = ' selected="selected"';
			 }
		 	 
			  if($info['level'] == 1){ 
		
	     $xhtml .='<option label="'.$info['name'].'" value="'.$info['id'].'" '.$strSelect.'>+'.$info['name'].'</option>';
	    }else{ 
		$string = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$newString = '';
		for($i = 1;$i<$info['level'];$i++){
		  $newString .= $string;	
		}
		$name = $newString . '-' . $info['name'];
	 $xhtml .=' <option label="'.$info['name'].'" value="'.$info['id'].'" '.$strSelect.'>'.$name.'</option>';
	  } 
	   endforeach; 
	   $xhtml .= '</select>'; 
	   //$xhtml .= '</div>'; 
	return $xhtml;

	}
	
	public function editItemInSelectbox($name, $value = array(), $options, $attribs = array()){
			 	$strAttribs = '';
	  if(count($attribs) > 0){
		  foreach($attribs as $key =>$val){
			  $strAttribs .= $key.' = "'. $val . '"';
		  }
	  }
	 
		$xhtml = '<select name="'.$name.'" id="'.$name.'" '.$strAttribs.'>';
		 foreach($options as $key=>$info):
		 $strSelect = '';
		 	foreach( $value as $k => $v):
		 	if ($info['id'] == $v){
			 $strSelect = ' selected="selected"';
			 }
			  endforeach; 
			  if($info['level'] == 1){ 
	     $xhtml .='<option label="'.$info['name'].'" value="'.$info['id'].'" '.$strSelect.'>+'.$info['name'].'</option>';
	    }else{ 
		$string = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$newString = '';
		for($i = 1;$i<$info['level'];$i++){
		  $newString .= $string;	
		}
		$name = $newString . '-' . $info['name'];
	 $xhtml .=' <option label="'.$info['name'].'" value="'.$info['id'].'" '.$strSelect.'>'.$name.'</option>';
	  } 
	  		  
	   endforeach; 
	   $xhtml .= '</select>'; 
	return $xhtml;

	}


	public function getItemsByMenu($menuId, $lang = 'vi')
	{
    $select = $this->select();
    $select->where("menu_id = ?", $menuId);
    $select->where("lang = ?", $lang);
    $select->order("position");
    $items = $this->fetchAll($select);
    if($items->count() > 0) {
        return $items;
    }else{
        return null;
    }
	}
	
	public function getItemsByMenuParent($menuId)
	{
    $select = $this->select();
    $select->where("menu_id = ?", $menuId);
    $select->where("parent = ?", 0);
    $select->order("position");
    $items = $this->fetchAll($select);
    if($items->count() > 0) {
        return $items;
    }else{
        return null;
    }
	}
	
	public function getMenuidByIdent($ident){
		$select = $this->select();
		$select->where('link = ?', $ident);
		$items = $this->fetchRow($select);
		return $items;
	}
	
	public function getMenuidByID($id){
		$select = $this->select();
		$select->where('id = ?', $id);
		$items = $this->fetchAll($select);
		if($items->count() > 0) {
        return $items;
		}else{
        return null;
		}
	}
	
	public function getGalleryByCat($ident){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = "SELECT i.id, s.caption, s.url, e.full FROM `menu_items` as i inner join menu_item_images as s inner join productImage as e on i.id = s.id_menu_item and s.id_product_image = e.imageId WHERE i.link = '".$ident."'";
		$result = $db->fetchAll($select);
		return $result;
	}
	
	public function getGalleryByCatIndex($ident){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = "SELECT i.id, s.caption, s.url, e.full 
					FROM `menu_items` as i 
					INNER JOIN menu_item_images as s 
					INNER JOIN productImage as e 
					ON i.id = s.id_menu_item 
					AND s.id_product_image = e.imageId 
					WHERE i.link = '".$ident."' ";
		$result = $db->fetchAll($select);
		return $result;
	}
	
	public function getGalleryById($id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
			$select = "SELECT i.id, s.caption, s.url, e.full FROM `menu_items` as i inner join menu_item_images as s inner join productImage as e on i.id = s.id_menu_item and s.id_product_image = e.imageId WHERE i.productId = '".$id."' ";
		$result = $db->fetchAll($select);
		return $result;
	}
	
	function getCategories($pid)
	{
		 $select = $this->_db->select()
        					->from(array('m' => $this->_name), array('m.name as menu_name', 'm.link', 'm.link_folder'))
        					->joinInner(array('r' => 'product_relationships'), 'r.category_id = m.id')
        					->joinInner(array('p' => 'product'), 'p.id = r.object_id', array('p.id as pid'))
        					->where('p.id = ?', $pid);
        					
        	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }
	}
	
	
	public function addItem($menuId, $name, $parent, $pageId = 0, $link = null, $description, $checkbox, $lang)
	{
    $row = $this->createRow();
    $row->menu_id = $menuId;
    $row->name = $name;
    $row->parent = $parent;
    $row->page_id = $pageId;
    $row->link = $link;
    $row->description = $description;
    $row->isHome = $checkbox;
    $row->Hpos = $this->_getLastId($menuId) + 1;
   if($parent == 0){
    $row->position = $this->_getLastPosition($menuId) + 1;
    }else {
	$row->position = $this->_getLastPositionSub($menuId, $parent) + 1; 
    }
    $row->lang = $lang;
    return $row->save();
	}
	
	
	private function _getLastPosition ($menuId)
    {
        $select = $this->select();
        $select->where("menu_id = ?", $menuId);
        $select->order('position DESC');
        $row = $this->fetchRow($select);
		if ($row) {
            return $row->position;
        } else {
            return 0;
			}
		 }
	
	private function _getLastId ($menuId)
    {
        $select = $this->select();
        $select->where("menu_id = ?", $menuId);
        $select->order('Hpos DESC');
        $row = $this->fetchRow($select);
		if ($row) {
            return $row->Hpos;
        } else {
            return 0;
			}
		 }
		 
	private function _getLastPositionSub ($menuId, $parent)
    {
        $select = $this->select();
        $select->where("menu_id = ?", $menuId);
        $select->where("parent = ?", $parent);
        $select->order('position DESC');
        $row = $this->fetchRow($select);
		if ($row) {
            return $row->position;
        } else {
            return 0;
			}
		 }	 
		 
	
public function moveUp($itemId)
{
    $row = $this->find($itemId)->current();
    if($row) {
        $position = $row->position;
        if($position < 1) {
               // this is already the first item
               return FALSE;
        }else{
            //find the previous item
            $select = $this->select();
         $select->order('position DESC');
            $select->where("position < ?", $position);
            $select->where("menu_id = ?", $row->menu_id);
            $previousItem = $this->fetchRow($select);
            if($previousItem) {
                //switch positions with the previous item
                $previousPosition = $previousItem->position;
                $previousItem->position = $position;
                $previousItem->save();
                $row->position = $previousPosition;
                $row->save();
            }
}
} else {
        throw new Zend_Exception("Error loading menu item");
    }
}


public function moveUpS($itemId)
{
    $row = $this->find($itemId)->current();
    if($row) {
        $position = $row->position;
        if($position < 1) {
               // this is already the first item
               return FALSE;
        }else{
            //find the previous item
            $select = $this->select();
         $select->order('position DESC');
            $select->where("position < ?", $position);
            $select->where("menu_id = ?", $row->menu_id);
            $select->where("parent = ?", $row->parent);
            $previousItem = $this->fetchRow($select);
            if($previousItem) {
                //switch positions with the previous item
                $previousPosition = $previousItem->position;
                $previousItem->position = $position;
                $previousItem->save();
                $row->position = $previousPosition;
                $row->save();
            }
}
} else {
        throw new Zend_Exception("Error loading menu item");
    }
}

public function moveUpH($itemId)
{
    $row = $this->find($itemId)->current();
    if($row) {
        $position = $row->Hpos;
        if($position < 1) {
               // this is already the first item
               return FALSE;
        }else{
            //find the previous item
            $select = $this->select();
         $select->order('Hpos DESC');
            $select->where("Hpos < ?", $position);
            $select->where("menu_id = ?", $row->menu_id);
            $previousItem = $this->fetchRow($select);
            if($previousItem) {
                //switch positions with the previous item
                $previousPosition = $previousItem->Hpos;
                $previousItem->Hpos = $position;
                $previousItem->save();
                $row->Hpos = $previousPosition;
                $row->save();
            }
}
} else {
        throw new Zend_Exception("Error loading menu item");
    }
}



public function moveDown($itemId) {
    $row = $this->find ( $itemId )->current ();
    if ($row) {
        $position = $row->position;
        if ($position == $this->_getLastPosition ( $row->menu_id )) {
            // this is already the last item
            return FALSE;
        } else {
            //find the next item
            $select = $this->select ();
            $select->order ( 'position ASC' );
            $select->where ( "position > ?", $position );
                    $select->where("menu_id = ?", $row->menu_id);
            $nextItem = $this->fetchRow ( $select );
            if ($nextItem) {
                //switch positions with the next item
                $nextPosition = $nextItem->position;
                $nextItem->position = $position;
                $nextItem->save ();
                $row->position = $nextPosition;
                $row->save ();
            }
}
} else {
        throw new Zend_Exception ( "Error loading menu item" );
    }
}	
	
	
	public function moveDownS($itemId) {
    $row = $this->find ( $itemId )->current ();
    if ($row) {
        $position = $row->position;
        if ($position == $this->_getLastPosition ( $row->menu_id )) {
            // this is already the last item
            return FALSE;
        } else {
            //find the next item
            $select = $this->select ();
            $select->order ( 'position ASC' );
            $select->where ( "position > ?", $position );
                    $select->where("menu_id = ?", $row->menu_id);
                    $select->where("parent = ?", $row->parent);
            $nextItem = $this->fetchRow ( $select );
            if ($nextItem) {
                //switch positions with the next item
                $nextPosition = $nextItem->position;
                $nextItem->position = $position;
                $nextItem->save ();
                $row->position = $nextPosition;
                $row->save ();
            }
}
} else {
        throw new Zend_Exception ( "Error loading menu item" );
    }
}


public function moveDownH($itemId) {
    $row = $this->find ( $itemId )->current ();
    if ($row) {
        $position = $row->Hpos;
        if ($position == $this->_getLastId ( $row->menu_id )) {
            // this is already the last item
            return FALSE;
        } else {
            //find the next item
            $select = $this->select ();
            $select->order ( 'Hpos ASC' );
            $select->where ( "Hpos > ?", $position );
                    $select->where("menu_id = ?", $row->menu_id);
            $nextItem = $this->fetchRow ( $select );
            if ($nextItem) {
                //switch positions with the next item
                $nextPosition = $nextItem->Hpos;
                $nextItem->Hpos = $position;
                $nextItem->save ();
                $row->Hpos = $nextPosition;
                $row->save ();
            }
}
} else {
        throw new Zend_Exception ( "Error loading menu item" );
    }
}
	
	public function updateItem($itemId, $name, $parent, $pageId = 0, $link = null ,$description, $icon, $checkbox, $lang) {
    $row = $this->find ( $itemId )->current ();
    if ($row) {
        $row->name = $name;
        $row->page_id = $pageId;
        $row->parent = $parent;
        if ($pageId < 1) {
            $row->link = $link;
        } else {
            $row->link = null;
        }
        $row->link_folder = $link_folder;
        $row->description = $description;
        $row->icon = $icon;
        $row->isHome = $checkbox;
        $row->Hpos = $itemId;
        $row->lang = $lang;
        return $row->save ();
    } else {
        throw new Zend_Exception ( "Error loading menu item" );
    }
	}	 
		
		public function deleteItem($itemId) {
    $row = $this->find ( $itemId )->current ();
    if ($row) {
        return $row->delete ();
    } else {
        throw new Zend_Exception ( "Error loading menu item" );
    }
	} 
	
	public function getMenuById($id){
		 $currentMenu = $this->find($id)->current();
		 if ($currentMenu) {
       return $currentMenu->menu_id;
    } else {
        return false;
    }
	}
	
	public function showCategoryMenu($menu_id = 2, $lang = 'vi' , $arr)
		{
			$select = $this->select();
        $select->where('menu_id = ?', $menu_id);
        $select->where('lang = ?', $lang);
        $select->order('position asc');
        
        $menus = $this->fetchAll($select);
        $recursive = new Louis_System_Recursive($menus->toArray());
        $newArr = $recursive->buildArray($arr);
        
        return $newArr;
		}
   	 
   	 public function getItemsByMenuAdmin($menuId, $show = null)
	{
		
		
    $select = $this->select();
    $select->where("menu_id = ?", $menuId);
    $select->where("lang = ?", $this->_lang);
    if($show != null){
    $select->where("isHome = ?", "Yes");
    }
    $select->order("position");
    $items = $this->fetchAll($select);
    if($items->count() > 0) {
        return $items;
    }else{
        return null;
    }
	}
	
	
	 public function getItemsByMenuSidebar($menuId)
	{
				
    $select = $this->select();
    $select->where("menu_id = ?", $menuId);
    $select->where("lang = ?", 'vi');
    $select->order("position");
    $items = $this->fetchAll($select);
    if($items->count() > 0) {
        return $items;
    }else{
        return null;
    }
	}
}