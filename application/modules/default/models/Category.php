<?php
class Model_Category extends Zend_Db_Table_Abstract
   {
       protected $_name = 'menu_items';
       protected $_primary = 'id';


	public function getCategoriesByParentId($parentId)
   {
       $select = $this->select()
                      ->where('parentId = ?', $parentId)
                      ->order('name');
       return $this->fetchAll($select);
   }
   
   	public function getCategoryByIdent($ident)
   {
       $select = $this->select()
                      ->where('ident = ?', $ident);
       return $this->fetchRow($select);
   }
   
   	public function getCategoryById($id)
   {
       $select = $this->select()
                      ->where('id = ?', $id);
       return $this->fetchAll($select);
   }
}