<?php
class Louis_Db_Table_Abstract extends Zend_Db_Table_Abstract 
     {
	    protected $_db;
	    protected $_name;
	    protected $_userID;
	    protected $_lang;
	    protected $log_activity = false;
		protected $log_type = "";
	     
	    public function init(){
	   $this->_db = Zend_Registry::get('db');
	   $ns = new Zend_Session_Namespace('language');
	   $this->_lang = $ns->lang;
	   if (empty($ns->lang)){
			 $this->_lang = 'vi';
			 }
	   
		}
		
	
	   
	   public function get_array_value(array $array, $key)
	   {
		   if (array_key_exists($key, $array)) {
            return $array[$key];
        }
	   }  
	   
	   public function get_one($id = 0) {
       return $this->fetchRow($this->select()->where('id = ?', $id));
    	}
    	

    	public function get_one_where($where = array()) {
		$select = $this->select();
		foreach($where as $key=>$val):
		$select->where("$key = ?", $val);
		endforeach;
        $items = $this->fetchRow($select);
       return $items;
       
    
    	}
    	
    	 public function update_where($data, array $arrs)
        {
	        $where = array();
		 	foreach($arrs as $key => $val):
		 	 $where[] = $this->getAdapter()->quoteInto("$key = ?", $val); 
		 	endforeach;
		return $this->update($data, $where);
        }
        
        
        public function delete_where(array $arrs)
        {
	        $where = array();
	        foreach($arrs as $key => $val):
	        $where[] = $this->getAdapter()->quoteInto("$key = ?", $val);
	        endforeach;
			return $this->delete($where);
        }
        
  
		public function getNameFromListId($listId)
		{
		$idArr = explode(',', $listId);
		$count = count($idArr);
		$data = array();
		foreach($idArr as $key=>$val):
		 $item = $this->get_one($val);
		 if($item){
		   $data[] = '<span title="'.$item->title.'">'.$item->title.'</span>';
		   }
		endforeach;
		return join(', ',$data);
	}
         
         public function getSeo($seo, $menu)
         {
	         
	         if($seo != null){
	$title = ($seo->title != null)?$seo->title:$menu->name;
	$keyword = ($seo->keyword != null)?$seo->keyword:$menu->name;
	$description = ($seo->description != null)?$seo->description:strip_tags($menu->description);
		}
	else{
	$title = $menu->name;
	$keyword = $menu->name;
	$description = strip_tags($menu->description);
	}
  		   
    
    return array('title' => $title,'keyword' => $keyword, 'description' => $description);
         }
	     
	     
	  public function getCount()
	   {
		  return count($this->fetchAll($this->select()));
	   } 
	   
	   public function save($data = array(), $id = null)
	   {
		   if($id != null)
		   {
		 $where = array("id" => $id);	
		 
		 if ($this->log_activity) {
		 //enable activity
		 $data_before_update = $this->get_one($id);
		 }
		 
		 $success = $this->update_where($data, $where);
		   if($success){
			    if ($this->log_activity) {
			    $fields_changed = array();
			    foreach ($data as $field => $value):
			    if ($data_before_update->$field != $value) {
				 $fields_changed[$field] = array("from" => $data_before_update->$field, "to" => $value);   	
				if($field == 'description'){
					$fields_changed['description'] = array("from" => '', "to" => '');
				}   
				    }
			    endforeach;
			    
			     if (count($fields_changed)) {
                      
                        $log_data = array(
                            "action" => "updated",
                            "created_at" => time(),
                            "created_by" => $this->_userID,
                            "log_type" => $this->log_type,
                            "log_type_id" => $id,
                            "changes" => serialize($fields_changed),
                        );
                        $activity_logs_model = new Model_Activities();
                        $activity_logs_model->save($log_data);
                    }
                    
                   }
		   }
		   }else
		   {
			   $row = $this->createRow();
			   foreach($data as $key=>$val):
			   $row->$key = $val;
			   endforeach;   	
	   		   $success = $row->save();
	   		
		   }
		   
		   return $success;
	   }
	   
	     
	  }