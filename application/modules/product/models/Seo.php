<?php
class Product_Model_Seo extends Louis_Db_Table_Abstract 
     {
       	   protected $_name    = 'seo';
           protected $_primary  = 'id';
           protected $_post_style  = 'tour';
		
		   public function getSeoFromId($id){
		   $select = $this->select()
		   				  ->where('id_object = ?', $id)
		   				  ->where('post_style = ?', $this->_post_style)
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