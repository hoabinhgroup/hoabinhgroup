<?php
class Model_Page extends Louis_Db_Table_Abstract {
    /**
     * Khai báo tên table trong CSDL
     */
    protected $_name = 'pages';
    
    protected $_dependentTables = array('Model_ContentNode');
    
    protected $_referenceMap = array(
			'Page' => array(
			'columns '=> array('parent_id'),
			'refTableClass' => 'Model_Page',
			'refColumns' => array('id'),
			'onDelete' => self::CASCADE,
			'onUpdate' => self::RESTRICT
			)
		);
		
	public function init()
		{
			parent::init();
		}	
		
		
		public function get_details($options = array()) {

       
         $select = $this->_db->select()
        					->from(array('p' => $this->_name));
        					
        $select->where('p.publish = ?', 1);
        
         $lang = $this->get_array_value($options, "lang");
         if ($lang) {
           $select->where('p.lang = ?', $lang);
        }
        
         $select->order('p.id desc');  
       
           $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        } 	
             
          
        
       	$result = $this->_db->fetchAll($select);	
       	
       	 return $result;
    }	

		    
	
    public function createPage($name, $namespace, $parentId = 0)
   {
    //tạo page mới
    $row = $this->createRow();
    $row->name = $name;
    $row->namespace = $namespace;
    $row->parent_id = $parentId;
    $row->date_created = time();
    $row->save();
    // lấy id của page vừa được tạo
    $id = $this->_db->lastInsertId();
    return $id;
    }
	

    
    	public function updatePage($id, $data)
		{
    // lấy dữ liệu từ id
    $row = $this->find($id)->current();
    if($row) {
        // update cột trong page table
	    $row->name = $data['name'];
        $row->description = $data['description'];
        $row->namespace = $data['namespace'];
        $row->lang = $data['lang'];
        $row->save();
        // unset các field đã được set trong table page
        /*
        unset($data['id']);
        unset($data['name']);
        unset($data['parent_id']);

        // set các fields trong content node table
       
        if(count($data) > 0) {
            $mdlContentNode = new Model_ContentNode();
            foreach ($data as $key => $value) {
                $mdlContentNode->setNode($id, $key, $value);
            }
			}
      else {
        throw new Zend_Exception('Không thể mở page để cập nhật!');
			}
		*/
			}
		}
		
		public function deletePage($id)
	 {	
    // tìm row dựa trên id
    $row = $this->find($id)->current();
    if($row) {
        $row->delete();
        return true;
    } else {
        throw new Zend_Exception("Xóa thất bại, không tìm được trang!");
    }
}

		public function getContentByUrl($name_clean){
				//$db = Zend_Db_Table::getDefaultAdapter();
				$select = $this->select()
							->where('name_clean = ?', $name_clean);
					$result = $this->_db->fetchRow($select);	
				return $result;		
		}

}