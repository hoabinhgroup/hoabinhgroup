<?php

class New_Model_Seo extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'seo';
       protected $_primary  = 'id';
       protected $_db;
       
       public function __construct(){
	   $this->_db = Zend_Registry::get('db');
		}	
		
		   public function getSeoFromId($id){
		   $select = $this->select()
		   				  ->where('id_object = ?', $id)
		   				  ->limit(1);
		    $result = $this->fetchRow($select);	
		    if($result){
			 return $result;   
		    }else {
			  return 0;
			    //throw new zend_exception('error');
		    }
		   	
	   }  
   }