<?php
class Model_Rec extends Louis_Db_Table_Abstract {
     	protected $_name = 'recruitment';
       protected $_primary = 'id';
		
		function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('p' => $this->_name));
        										        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
           $select->where('id = ?', $id);
        }  
        
         $keyword = $this->get_array_value($options, "keyword");
        if ($keyword) {
           $select->where('title LIKE ?', "%{$keyword}%");
        }       
        
        $work_group  = $this->get_array_value($options, "work_group"); 
         if ($work_group) {
           $select->where('work_group = ?', $work_group);
        } 
        
        $location  = $this->get_array_value($options, "location"); 
         if ($location) {
         //  $select->where('location = ?', $location);
           $select->where("FIND_IN_SET($location,location)>0");
        }  
        
        $company  = $this->get_array_value($options, "company"); 
         if ($company) {
           $select->where('company = ?', $company);
        } 
        
		$per_page = $this->get_array_value($options, "per_page");
        if ($per_page) {
	       $page = $this->get_array_value($options, "page");
           $select->limitPage($page, $per_page);
           
        }	
        
        $status  = $this->get_array_value($options, "status"); 
         if ($status) {
           $select->where('status = ?', $status);
        } 
        
        $select->order('p.featured Desc','p.expired Desc');
               				
       	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	}

	
      /*  public function delete($id)
	   {
		   $delete = "delete FROM ".$this->_name." where id = $id";
		   $this->_db->query($delete);
		   
		   
	   }  
	   */
}