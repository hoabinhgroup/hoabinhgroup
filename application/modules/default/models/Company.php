<?php
class Model_Company extends Louis_Db_Table_Abstract {
     	protected $_name = 'recruitment_company';
       protected $_primary = 'id';
		
		function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('p' => $this->_name));
        										        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
           $select->where('id = ?', $id);
        }        
        
               				
       	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	}

	
        public function delete($id)
	   {
		   $delete = "delete FROM ".$this->_name." where id = $id";
		   $this->_db->query($delete);
		   
		   
	   }  
}