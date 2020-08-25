<?php
class Model_Tag extends Zend_Db_Table_Abstract {
      protected $_name = 'tags';


	  public function listTags()
	  {
		  $select = $this->select()
		  				->order('view desc')
		  				->limit(30);
		  return $result = $this->fetchAll($select);
		  			
	  }

     }