<?php
class Model_Online extends Louis_Db_Table_Abstract 
     {
        protected $_name    = 'module_online';
        
    public function init(){
	   		parent::init();
		}	
		

    public function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('mo' => $this->_name));
        						
        
        
       	$result = $this->_db->fetchAll($select);	

      
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	
    }	
    
     public function getVisitorDatas() {
      
      $visitor = array(
         'session_id' => session_id(),
         'ip_address' => $_SERVER['REMOTE_ADDR'],
         'user_agent' => $_SERVER['HTTP_USER_AGENT']
      );
      
      return $visitor;
   
   }
   
   public function getLattestVisits() {
   
      $this->_db->query("DELETE FROM module_online WHERE datetime < (NOW() - INTERVAL 10 MINUTE)");
       $select =   $this->_db->query("
         SELECT datetime,
        	    session_id,
                ip_address, 
         COUNT(*) AS visits 
         FROM module_online 
         WHERE datetime < date_sub(now(), interval 5 minute) 
         GROUP BY session_id");
     
      return $select->fetchAll();
   }     
	   
   }