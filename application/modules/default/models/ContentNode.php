<?php
class Model_ContentNode extends Zend_Db_Table_Abstract {
    
            protected $_name = 'content_nodes';
            
            protected $_referenceMap = array(
			'Page' => array(
			'columns '=> array('page_id'),
			'refTableClass' => 'Model_Page',
			'refColumns' => array('id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
			)
		);
            
           public function setNode($pageId, $node, $value)
		   {
    // nếu tồn tại thì fetch row
    $select = $this->select();
    $select->where("page_id = ?", $pageId);
    $select->where("node = ?", $node);
    $row = $this->fetchRow($select);
    //nếu chưa có thì tạo node
    if(!$row) {
        $row = $this->createRow();
        $row->page_id = $pageId;
        $row->node = $node;
		}
    //set nội dung
    $row->content = $value;
    $row->save();
	} 
	
	
	            
}
