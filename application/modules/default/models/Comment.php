<?php
class Model_Comment extends Zend_Db_Table_Abstract {
      protected $_name = 'comments';


	  public function listRecentComments()
	  {
		  $select = $this->select()
		  				->order('time desc')
		  				->limit(10);
		  return $result = $this->fetchAll($select);
		  			
	  }
	  
	   public function countNumber(){
		   $count = 'SELECT count(id) FROM `comments`';
		   $t= $this->_db->fetchRow($count);
		   return $t['count(id)'];
	   }
	   

     }