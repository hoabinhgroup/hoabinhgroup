<?php
class Model_ProductImage extends Louis_Db_Table_Abstract {
     protected $_name = 'productImage';
       protected $_primary = 'imageId';

       protected $_referenceMap = array(
           'Image' => array(
               'columns' => 'productId',
               'refTableClass' => 'Model_MenuItem',
               'refColumns' => 'id',
               'refColumns' => 'content_type',
) );

		public function init(){
			parent::init();
		}

		
		function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('i' => $this->_name));
        					
        $select->joinInner('menu_items as m', 'm.id = i.productId');					        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
           $select->where('imageId = ?', $id);
        }        
         $productId = $this->get_array_value($options, "productId");
        if ($productId) {
           $select->where('productId = ?', $productId);
        }     
        
          $content_type = $this->get_array_value($options, "content_type");
        if ($content_type) {
           $select->where('content_type = ?', $content_type);
        } 
        
       
           $select->order('i.position asc');
        
               				
       	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	}

	
      	public function create($data)
		  {
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->productId = $data['productId'];
	   		$row->full = $data['full'];
	   		$row->isDefault = $data['isDefault'];
	   		$row->content_type = 'menu';
	   		$id = $row->save();
	   		// lấy id của san pham vừa được tạo
	   		return $id;
   	 	  }
}