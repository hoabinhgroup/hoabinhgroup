<?php
class Model_Menu extends Zend_Db_Table_Abstract {
      protected $_name = 'menus';
      protected $_dependentTables = array('Model_MenuItem');
      protected $_referenceMap = array(
	  'Menu' => array(
      'columns'	=> array('id'),
      'refTableClass' => 'Model_Menu',
      'refColumns' => array('id'),
      'onDelete' => self::CASCADE,
      'onUpdate' => self::RESTRICT
) );

	
      public function createMenu($name)
      {
          $row = $this->createRow();
          $row->name = $name;
          return $row->save();
      }

	  public function getMenus(){
			$select = $this->select();
			$select->order('name');
			$menus = $this->fetchAll($select);
			if($menus->count() > 0) {
				return $menus;
				}else{
					return null;
				}
			}
			
	public function updateMenu ($id, $name)
	{
    $currentMenu = $this->find($id)->current();
    if ($currentMenu) {
        $currentMenu->name = $name;
        return $currentMenu->save();
    } else {
        return false;
    }
	}
	
	public function deleteMenu($menuId)
	{
    $row = $this->find($menuId)->current();
    if($row) {
        return $row->delete();
    }else{
        throw new Zend_Exception("Error loading menu");
    }
		}
		
		
	public function getMenuById($id){
		 $currentMenu = $this->find($id)->current();
		 if ($currentMenu) {
       return $currentMenu->name;
    } else {
      $menuItem = new Model_MenuItem();
    	$currentMenu = $this->find($menuItem->getMenuById($id))->current();
        return $currentMenu->name;
    }
	}	
	
	public function getMenuIdById($id){
		 $currentMenu = $this->find($id)->current();
		 if ($currentMenu) {
       return $currentMenu->id;
    } else {
      $menuItem = new Model_MenuItem();
    	$currentMenu = $this->find($menuItem->getMenuById($id))->current();
        return $currentMenu->id;
    }
	}
}