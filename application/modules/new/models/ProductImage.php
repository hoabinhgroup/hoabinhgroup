<?php
class New_Model_ProductImage extends Louis_Db_Table_Abstract    {
       protected $_name = 'productImage';
       protected $_primary = 'imageId';

       protected $_referenceMap = array(
           'Image' => array(
               'columns' => 'productId',
               'refTableClass' => 'New_Model_Product',
               'refColumns' => 'productId',
) );


		function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('p' => $this->_name));
        
            
         $productId = $this->get_array_value($options, "productId");
        if ($productId) {
           $select->where('productId = ?', $productId);
        }   
        
        $isDefault =  $this->get_array_value($options, "isDefault");
         if ($isDefault) {
           $select->where('isDefault = ?', $isDefault);
        } 
        
        $content_type = $this->get_array_value($options, "content_type");
        if ($content_type) {
	      $select->where('p.content_type = ?', $content_type);    
	          }
 
        	$result = $this->_db->fetchAll($select);	
       	
         return $result;  
      }	

		public function getFirstImageProduct($pid){
			$select = $this->select()
						->where('productId = ?', $pid)
						->where('isDefault =?', 'Yes');
				
				$result = $this->fetchAll($select);		
						
				return $result->toArray();		
		}
		
		
		public function getImagesProduct($pid){
			$select = $this->select()
						->where('productId = ?', $pid);
				
				$result = $this->fetchAll($select);		
						
				return $result->toArray();		
		}
}