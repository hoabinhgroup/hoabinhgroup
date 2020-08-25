<?php
	class Blog_Model_Blog extends Zend_Db_Table_Abstract
	{	
		
		protected $_name = 'pages';
		
		public function getItems()
		{
			$select = $this->select();
			$select->where('namespace = ?', 'blog');
			$select->order('date_created desc');
			
			 $currentPages = $this->fetchAll($select);
			 if($currentPages->count() > 0) {
			 return $currentPages;
    		}else{
			 return null;
    		}
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
	    $row->name_clean = $data['name_clean'];
        $row->parent_id = $data['parent_id'];
        $row->description = $data['description'];
        $row->content = $data['content'];
        $row->author_id = $data['author_id'];
        $row->photo = $data['photo'];
        $row->save();
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
				$select = $this->select();
				$select->where('name_clean = ?', $name_clean);
					$result = $this->fetchAll($select);	
				return $result[0];		
		}
		
	}