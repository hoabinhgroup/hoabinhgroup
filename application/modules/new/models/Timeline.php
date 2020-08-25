<?php
class New_Model_Timeline extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'product';
       protected $_primary  = 'id';
       protected $_content_type  = 'timeline';
       protected $_db;
       protected $_lang;
       
       public function __construct(){
	   $this->_db = Zend_Registry::get('db');
	   $ns = new Zend_Session_Namespace('language');
	   $this->_lang = $ns->lang;
	   if (empty($ns->lang)){
			 $this->_lang = 'vi';
			 }
		}	
		
		
		function get_details($options = array()) {

       
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
         $id_product = $this->get_array_value($options, "id_product");
        if ($id_product) {
           $select->where('id_product = ?', $id_product);
        }     
        
		$lang = $this->get_array_value($options, "lang");
		if($lang){
         $select->where('p.lang = ?', $lang);
         }

        $content_type = $this->get_array_value($options, "content_type");
        if ($content_type) {
	      $select->where('p.content_type = ?', $content_type);    
	          }
         
         $status = $this->get_array_value($options, "status");
          if ($status) {
	      $select->where('p.status = ?', $status);    
	          }
        
        $link = $this->get_array_value($options, "link");
        if ($link) {
	                $select->joinInner(
		 				'product_relationships as r',
		 				'r.object_id = p.id')
        					->joinInner(
		 				'menu_items as m',
		 				'r.category_id = m.id', array("m.name as menu_title"));
		 			
            $select->where('m.link = ?', $link);
            if($lang){
            $select->where('m.lang = ?', $lang);
            }
          
			// $select->group('p.id');
        }  
        
        	$select->group('p.id');
          		
           $order = $this->get_array_value($options, "order");
        if ($order) {
           $select->order($order);
        } 	
        
           $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        } 	
             
        
       	$result = $this->_db->fetchAll($select);	
       	
       	  $paginator_per_page = $this->get_array_value($options, "paginator_per_page");
       	 
        if ($paginator_per_page) {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($result));
	 
	$paginator->setItemCountPerPage($paginator_per_page);
	
	 $page = $this->get_array_value($options, "page");
	 
	$paginator->setCurrentPageNumber($page);
	
	
	$pagecount =  $paginator->getPages()->pageCount;
      
      return $paginator;
      
      }else{
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	}
    }	
    
     public function updateCategory($parentID, $pid)
      {
	      $sel = 'SELECT 
	      			category_id 
	      		  FROM 
	      		  	product_relationships 
	      		  WHERE 
	      		  	object_id ="' . $pid . '"';
            $resultSel =  $this->_db->fetchAll($sel);
          
		  
            $delete = 'DELETE FROM 
            				product_relationships 
            			WHERE 
            				object_id="' . $pid . '"';
            $this->_db->query($delete);
          
            foreach($parentID as $key=>$val):
            $insert = 'INSERT INTO 
            			product_relationships (object_id, category_id) 
            		  VALUES ("' . $pid . '", "' . $val . '" )';
            $this->_db->query($insert);
            endforeach;
      }
      
		
		
   	 	  
   	 	public function getRecentPages()
   	 		{
			$select = $this->select()
				->where('content_type = ?', 'new')
				->where('lang = ?', 'vi')
			   ->order('date desc');
    
    $currentProducts = $this->_db->fetchAll($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }

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
		 $row->tags = $data['tags'];
		$row->content_type = $data['content_type'];
		$row->lang = $data['lang'];
        $row->save();
        
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
		  
	  // phan trang
	   public function getProductsByCategory($ident, $sort = null, $order = null)
		  {
            
    if (($sort == 'name') && ($order == 'asc')){
	    $sortOrder = ' order by name asc';
    }elseif (($sort == 'name') && ($order == 'desc')){
	     $sortOrder = ' order by name desc';
    }elseif (($sort == 'price') && ($order == 'asc')){
	     $sortOrder = ' order by price asc';
    }elseif (($sort == 'price') && ($order == 'desc')){
	     $sortOrder = ' order by price desc';
    }else {
	    $sortOrder = '';
    }
     $select = "select p.*, h.price, h.star, e.full from product as p inner join menu_items as i inner join product_relationships as r inner join productImage as e on p.id = r.object_id and i.id = r.category_id where i.link = '$ident' and p.content_type = '$this->_content_type' and e.isDefault = 'Yes'  $sortOrder";
       if(count($select) > 0){
       return $this->_db->fetchAll($select);
  
       } else {
	       throw new Zend_Exception('error!');
       }
	   }
	   
	   public function getValuesFromIdent($ident)
		{
				$select = $this->_db->select()
				 ->from('menu_items',
                    array('id','parent', 'description'))
				->where('link = ?', $ident);
				
	  $currentProducts = $this->_db->fetchRow($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }
		}
		
		  public function getSingleByCategory($ident, $lang)
		  {
            
   
     $select = "select p.* from product as p inner join menu_items as i inner join product_relationships as r on p.id = r.object_id and i.id = r.category_id where i.link = '$ident' and p.content_type = 'single' and p.lang = '".$lang."'";
       if(count($select) > 0){
       return $this->_db->fetchAll($select);
  
       } else {
	       throw new Zend_Exception('error!');
       }
	   }
	   
	   	public function getNameFromParent($parent)
		{
				$select = $this->_db->select()
				 ->from('menu_items', array('name'))
				->where('id = ?', $parent);
				
	  $currentProducts = $this->_db->fetchRow($select);
    if(count($currentProducts) > 0) {
        return $currentProducts['name'];
    }else{
        return null;
    }
		}
		
			public function getNameFromIdent($ident)
		{
				$select = $this->_db->select()
				 ->from('menu_items', array('name'))
				->where('link = ?', $ident);
				
	  $currentProducts = $this->_db->fetchRow($select);
    if(count($currentProducts) > 0) {
        return $currentProducts['name'];
    }else{
        return null;
    }
		}
		
		public function getListFormParent($parent)
		{
				$select = $this->_db->select()
				 ->from('menu_items', array('name','link'))
				->where('parent = ?', $parent);
				
	  $currentProducts = $this->_db->fetchAll($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }
		}
		
		 public function getProductsByTag($ident, $id = null, $limit = null)
		  {
			  $exclude = '';
            if($id != null){
	         $exclude.= " AND p.id != $id ";   
            }
            
            if($limit != null){
	         $limit = " LIMIT $limit";   
            }
            
     $select = "SELECT p.*, i.full FROM product as p 
     			INNER JOIN product_tags as t 
     			INNER JOIN productImage as i 
     			INNER JOIN tags as s 
     			ON t.product_id = p.id 
     			AND t.tag_id = s.id 
     			AND i.productId = p.id 
     			WHERE s.ident = '$ident'
     			AND i.isDefault ='Yes' $exclude GROUP BY id DESC $limit";
       if(count($select) > 0){
       return $this->_db->fetchAll($select);
  
       } else {
	       throw new Zend_Exception('error!');
       }
	   }
	   	   
	    public function getNewsRecent($limit = '')
		  {
      if ($limit != ''){
	      $limit = 'limit '.$limit;
      }      
     $select = "select * from product where content_type = '$this->_content_type' order by date desc $limit";
       if(count($select) > 0){
       return $this->_db->fetchAll($select);
  
       } else {
	       throw new Zend_Exception('error!');
       }
	   }
	   
	    public function recentProducts($currentID, $limit = 4) {
        $select = $this->select()
                ->where('id !=?', $currentID)
                ->where('content_type = ?', $this->_content_type)
                ->order('id desc')
                ->limit($limit);

        $result = $this->fetchAll($select);
        return $result;
    }
	   
	    public function getImageFromId($id)
		  {
    
     $select = "select full from productImage where productId = $id and isDefault='Yes'";
       if(count($select) > 0){
       return $this->_db->fetchRow($select);
  
       } else {
	       throw new Zend_Exception('error!');
       }
	   }
	   
	   
	  public function getDetailsByProduct($ident)
		  {
    
     $select = "SELECT p.* FROM product as p WHERE status = 1 and ident = '$ident'";
       if(count($select) > 0){
       return $this->_db->fetchRow($select);
       } else {
	       throw new Zend_Exception('error!');
       }
	       } 
	       
	       public function getlistNews($lang, $limit = null)
	    {
		   if($limit != ''){
			 $limit = ' LIMIT ' . $limit;  
		   }
		$select = "SELECT distinct p.* 
					FROM product as p 
					INNER JOIN product_relationships as s 
					ON p.id = s.object_id 
					WHERE p.content_type = 'new' 
					AND p.status = 1 
					AND p.lang='".$lang."' 
					ORDER BY p.date DESC $limit";  
		  return $result = $this->_db->fetchAll($select);
	    }
	    
	       
	     public function getlistProducts(){
		 	$select = $this->select()
					 ->where('lang = ?', $this->_lang);
						
       $select->order('date desc');
      
    $currentProducts = $this->_db->fetchAll($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }

			}  
	
  public function getlistProductsByCategory($ident, $lang){
  $select = "SELECT p.*, m.name as menu_title FROM product as p inner join product_relationships as r inner join menu_items as m on p.id = r.object_id and m.id = r.category_id  WHERE m.link = '$ident' and p.lang= '$lang' and p.status = 1 order by id desc";
    
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
	   
	   public function latestProducts()
	   {
		   $select = $this->select()
		   				  ->order('id desc')
		   				  ->limit(5);
		   				  
		   $result = $this->fetchAll($select);	
		   return $result;		  
	   }	
	   
	   public function getMaxID($ident){
		    $selectMaxID = 'SELECT MAX(r.category_id) as maxcatid FROM product as p inner join product_relationships as r on p.id = r.object_id  where p.ident = "'.$ident.'"';
	$resultMaxID = $this->_db->fetchRow($selectMaxID);
	return $resultMaxID['maxcatid'];
	   } 
	   
	   
	    public function delete($id)
	   {
		   $delete = "delete from product where id = $id";
		   $this->_db->query($delete);
		   
		    $delete3 = "delete from product_relationships where object_id = $id";
		   $this->_db->query($delete3);
		   
		    $delete4 = "delete from productImage where productId = $id";
		   $this->_db->query($delete4);
		   
	   }    
   }