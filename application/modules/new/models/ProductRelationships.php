<?php
class New_Model_ProductRelationships extends Louis_Db_Table_Abstract    {
       protected $_name = 'product_relationships';

       protected $_referenceMap = array(
           'Image' => array(
               'columns' => 'object_id',
               'refTableClass' => 'New_Model_Product',
               'refColumns' => 'id',
	    ) );


	
}