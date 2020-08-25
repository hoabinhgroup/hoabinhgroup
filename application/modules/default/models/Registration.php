<?php
class Model_Registration extends Louis_Db_Table_Abstract {
   	 protected $_name = 'registration';


		public function init()
		{
			parent::init();
		}
		
		public function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('r' => $this->_name));
        					
                     
        $select->where("r.deleted = ?", 0);  
        
       	$result = $this->_db->fetchAll($select);	
       	
       	 return $result;
    }	

	
}