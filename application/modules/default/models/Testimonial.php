<?php
class Model_Testimonial extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'product';
       protected $_primary  = 'id';
       protected $_content_type  = 'testimonial';
       protected $_db;
       
       public function __construct(){
	   $this->_db = Zend_Registry::get('db');
	   $ns = new Zend_Session_Namespace('language');
	   $this->_lang = $ns->lang;
	   if (empty($ns->lang)){
			 $this->_lang = 'vi';
			 }
		}	
		
		public function createProduct($data)
		  {
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->name = $data['name'];
	   		$row->ident = $data['ident'];
	   		$row->description = $data['description'];
	   		$row->shortDescription = $data['shortDescription'];
	   		$row->date = time();
	   		$row->content_type = $this->_content_type;
	   		$row->lang = $data['lang'];
	   		$id = $row->save();
	   		// lấy id của san pham vừa được tạo
	   		//$id = $this->_db->lastInsertId();
	   		return $id;
   	 	  }
   	 
   	 
   	 public function updateProduct($id, $data)
		{
    // lấy dữ liệu từ id
    $row = $this->find($id)->current();
    if($row) {
        // update cột trong page table
	     $row->name = $data['name'];
	     $row->ident = $data['ident'];
		 $row->description = $data['description'];
		 $row->shortDescription = $data['shortDescription'];
		 $row->date = $data['date'];
		 $row->content_type = $this->_content_type;
        $row->save();
        
        }
        
        }
       
    public function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('p' => $this->_name));
        						
        	$image = $this->get_array_value($options, "image");	
        if($image){				
		 $select->joinLeft(
		 				'productImage as i',
		 				'p.id = i.productId', array("full"));
			$select->where('i.isDefault = ?', 'Yes');	
			$select->where('i.content_type = ?', 'product');	
			}										 							
        					        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
            $select->where('p.id = ?', $id);
        }        
     
		$lang = $this->get_array_value($options, "lang");
		 if ($lang) {
         $select->where('p.lang = ?', $lang);
		 }
		 
	      $select->where('p.content_type = ?', 'testimonial');    
	      
         
           $order = $this->get_array_value($options, "order");
        if ($order) {
           $select->order($order);
        } else {
	       $select->order('p.date desc'); 
        }
        
        $select->where('p.lang = ?', $this->_lang);
        
           $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        } 	
           $select->group('p.id');  
        
       	$result = $this->_db->fetchAll($select);	

      
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	
    }	
        
       
       public function getProductByIdent($ident)
	   {
       return $this->fetchRow(
           $this->select()->where('ident = ?', $ident)
           					->where('content_type = ?', $this->_content_type)
		   );
	 }
	 
	  public function getProductById($id)
	   {
       return $this->fetchRow($this->select()->where('id = ?', $id));
	 }
	 
	  public function getProductsByCatId($id)
	   {
       return $this->fetchAll($this->select()->where('categoryId = ?', $id)
       										->orWhere('subcategoryId = ?', $id));
	 }
		  
	 
	   
	  public function getDetailsByProduct($ident)
		  {
    
     $select = "SELECT p.* FROM product as p WHERE ident = '$ident' and p.content_type =  '$this->_content_type'";
       if(count($select) > 0){
       return $this->_db->fetchRow($select);
       } else {
	       throw new Zend_Exception('error!');
       }
	       } 
	       
	       public function getlistProducts($lang = 'vi'){
		$select = $this->select()
						->where('content_type = ?', $this->_content_type)
						->where('lang = ?', $lang);
       $select->order('name');
    
    $currentProducts = $this->_db->fetchAll($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }

	}   
	   
	   public function relatedProducts($catId, $subcatId = null)
	   {
		   $select = $this->select();
		   
		   		if($subcatId == null){
			   		$select->where('categoryId = ?', $catId);
		   		}else{
			   		$select->where('subcategoryId = ?', $subcatId);
		   		}
		   $result = $this->fetchAll($select);	
		   
		   return $result;	  
	   }
	   
	   public function featureProducts()
	   {
		    $select = "select p.id, p.ident, p.name, t.price, t.time_travel, i.full from product as p inner join product_tours as t left join productImage as i on t.id_product = p.id and i.productId = p.id where p.content_type = '$this->_content_type' and i.isDefault = 'Yes'";
		    $result = $this->_db->fetchAll($select);
		    return $result;
	   }
	   
	 /*    public function delete($id)
	   {
		   if($id != null){
		   $delete = "DELETE FROM product where content_type = $this->__content_type AND id = $id";
		   $this->_db->query($delete);
		   }
		   
	   }   
	   
	   */
	   
   }