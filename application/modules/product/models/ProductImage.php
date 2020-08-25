<?php
class Product_Model_ProductImage extends Louis_Db_Table_Abstract    {
       protected $_name = 'productImage';
       protected $_primary = 'imageId';

       protected $_referenceMap = array(
           'Image' => array(
               'columns' => 'productId',
               'refTableClass' => 'Product_Model_Product',
               'refColumns' => 'productId',
) );

		public function create($data)
		  {
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->productId = $data['productId'];
	   		$row->full = $data['full'];
	   		$row->isDefault = $data['isDefault'];
	   		$row->content_type = 'product';
	   		$id = $row->save();
	   		// lấy id của san pham vừa được tạo
	   		return $id;
   	 	  }


		public function getFirstImageProduct($pid){
			$select = $this->select()
						->where('productId = ?', $pid)
						->where('isDefault =?', 'Yes');
				
				$result = $this->fetchRow($select);		
						
				return $result->toArray();		
		}
		
		
		public function getImagesProduct($pid){
			$select = $this->select()
						->where('productId = ?', $pid);
				
				$result = $this->fetchAll($select);		
						
				return $result->toArray();		
		}
}