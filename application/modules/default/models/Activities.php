<?php
class Model_Activities extends Louis_Db_Table_Abstract {
      protected $_name = 'activity_logs';


	  function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('a' => $this->_name));
        					

        					        					
        $log_type_id = $this->get_array_value($options, "log_type_id");
        if ($log_type_id) {
            $select->where('a.log_type_id = ?', $log_type_id);
        }     
        
           $select->order('a.created_at desc');
		   	$result = $this->_db->fetchAll($select);
		   	return $result;
    	}	

     }