<?php
class Product_Model_Product extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'product';
       protected $_primary  = 'id';
       protected $_content_type  = 'tour';
    
    
     public function __construct(){
	   $this->_db = Zend_Registry::get('db');
	   $ns = new Zend_Session_Namespace('language');
	   $this->_lang = $ns->lang;
	   if (empty($ns->lang)){
			 $this->_lang = 'vi';
			 }
		}

		public function getRecentPages()
		{
			$select = $this->select()
			   ->order('date desc');
    
    $currentProducts = $this->_db->fetchAll($select);
    if(count($currentProducts) > 0) {
        return $currentProducts;
    }else{
        return null;
    }

		}
		
		
           

		
		function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('p' => $this->_name), array('p.id as pid','name','ident','views', 'shortDescription', 'description', 'status', 'featured', 'author'));
        					
        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
            $select->where('p.id = ?', $id);
        }        
        
         $content_type = $this->get_array_value($options, "content_type");
        if ($content_type) {
	        if($content_type == 'tour'){
		       $select->joinInner('product_tours as t', 't.id_product = p.id');
	        }
           $select->where('p.content_type = ?', $content_type);
        }
        
          $featured = $this->get_array_value($options, "featured");
        if ($featured) {
           $select->where('p.featured = ?', $featured);
        }  
       
        
        $id_product = $this->get_array_value($options, "id_product");
        if ($id_product) {
           $select->where('id_product = ?', $id_product);
        }       
        
          $cat = $this->get_array_value($options, "cat");
        if ($cat) {
	        $select->joinInner('product_relationships as r', 'r.object_id = p.id');
			$select->where('r.category_id = ?', $cat);
            $select->group('p.id');
        } 
            
         $status = $this->get_array_value($options, "status");
         if($status){
         $select->where('p.status = ?', $status);
        }
        $select->order('p.id desc');
        
        $lang = $this->get_array_value($options, "lang");
        if($lang){
	      $select->where('p.lang = ?', 'vi');  
        }else{
         $select->where('p.lang = ?', $this->_lang);
        }
        
        $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        }   
        
         $per_page = $this->get_array_value($options, "per_page");
        if ($per_page) {
	       $page = $this->get_array_value($options, "page");
           $select->limitPage($page, $per_page);
        }
      
               				
       	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	}
		
		public function createProduct($data)
		  {
			  $identity = Zend_Auth::getInstance()->getIdentity();
			 $user_id = $identity->id;
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->name = $data['name'];
	   		$row->ident = $data['ident'];
	   		$row->description = $data['description'];
	   		$row->shortDescription = $data['shortDescription'];
	   		$row->date = time();
	   		$row->content_type = $data['content_type'];
	   		$row->tags = $data['tags'];
	   		$row->author = $user_id;
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
		 $row->tags = $data['tags'];
        $row->save();
        
        }
        
        }
        
        
        
    
   function get_one_custom($id)
    {
	   
        $select = $this->_db->select()
        					->from($this->_name ." as p")
        					->joinInner("product_tours as t", "t.id_product = p.id", array("price","time_travel","communication","discountPercent","schedule"))   			
							->joinInner("productImage as i", "i.productId = p.id", array("full"));
            $select->where('p.id = ?', $id);
            $select->where('i.isDefault = ?', 'Yes');
            $select->where('i.content_type = ?', 'product');
           // $select->where('p.status = ?', $id);
            
          return $this->_db->fetchRow($select);
    }
    
    public function get_promotions()
    {
	     $select = $this->_db->select()
        					->from($this->_name ." as p")
        					->joinInner("product_tours as t", "t.id_product = p.id", array("price","discountPercent"))   			
							->joinLeft("productImage as i", "i.productId = p.id", array("full"));
            $select->where('t.discountPercent != ?', 0);
            $select->where('i.isDefault = ?', 'Yes');
            $select->where('i.content_type = ?', 'product');
           // $select->where('p.status = ?', $id);
            return $this->_db->fetchAll($select);
            
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
      
      public function getCatsFromId($id)
		{
			 $select = $this->_db->select()
						->from("product_relationships as r", 'category_id')		 			
						->where('r.object_id = ?', $id);
    
			$currentProducts = $this->_db->fetchAll($select);
			return $currentProducts;
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
		   public function getProductsByCategory($ident, $sort = null, $order = null){
            
            if (($sort == 'name') && ($order == 'asc')){
        	    $sortOrder = ' order by p.name asc';
            }elseif (($sort == 'name') && ($order == 'desc')){
        	     $sortOrder = ' order by p.name desc';
            }elseif (($sort == 'price') && ($order == 'asc')){
        	     $sortOrder = ' order by t.price asc';
            }elseif (($sort == 'price') && ($order == 'desc')){
        	     $sortOrder = ' order by t.price desc';
            }elseif (($sort == 'time') && ($order == 'asc')){
        	     $sortOrder = ' order by t.time_travel asc';
            }elseif (($sort == 'time') && ($order == 'desc')){
        	     $sortOrder = ' order by t.time_travel desc';
            }else {
        	    $sortOrder = ' order by p.date desc';
        	   // throw new Zend_exception('error url');
            }
             $select = "select p.*, t.price, t.time_travel, t.location, pi.full from product as p inner join menu_items as i inner join product_relationships as r inner join product_tours as t inner join productImage as pi on p.id = r.object_id and i.id = r.category_id and t.id_product = p.id and p.id = pi.productId where i.link = '$ident' and p.content_type = '$this->_content_type' and pi.isDefault = 'Yes' and p.status = 1 $sortOrder";
               if(count($select) > 0){
               return $this->_db->fetchAll($select);
          
               } else {
        	       throw new Zend_Exception('error!');
               }
	   }
	   
	   
	    public function getProductsByCategoryId($catid){
            

             $select = "select p.*, t.price, t.time_travel, t.location, pi.full from product as p inner join menu_items as i inner join product_relationships as r inner join product_tours as t inner join productImage as pi on p.id = r.object_id and i.id = r.category_id and t.id_product = p.id and p.id = pi.productId where i.id = '$catid' and p.content_type = '$this->_content_type' and pi.isDefault = 'Yes' and p.status = 1 order by p.date desc";
               if(count($select) > 0){
               return $this->_db->fetchAll($select);
          
               } else {
        	       throw new Zend_Exception('error!');
               }
	   }
	   
	   
	     public function getProductsByTag($ident, $sort = null, $order = null)
		  {
            
            if (($sort == 'name') && ($order == 'asc')){
        	    $sortOrder = ' order by p.name asc';
            }elseif (($sort == 'name') && ($order == 'desc')){
        	     $sortOrder = ' order by p.name desc';
            }elseif (($sort == 'price') && ($order == 'asc')){
        	     $sortOrder = ' order by pt.price asc';
            }elseif (($sort == 'price') && ($order == 'desc')){
        	     $sortOrder = ' order by pt.price desc';
            }elseif (($sort == 'time') && ($order == 'asc')){
        	     $sortOrder = ' order by pt.time_travel asc';
            }elseif (($sort == 'time') && ($order == 'desc')){
        	     $sortOrder = ' order by pt.time_travel desc';
            }else {
        	    $sortOrder = '';
        	   // throw new Zend_exception('error url');
            }
             $select = "SELECT p.*, pt.price, pt.time_travel, pt.discountPercent, pt.location, pi.full   
             			FROM product as p 
             			INNER JOIN product_tags as t 
             			INNER JOIN tags as s 
             			INNER JOIN product_tours as pt 
             			INNER JOIN productImage as pi 
             			ON t.product_id = p.id 
             			AND t.tag_id = s.id 
             			AND pt.id_product = p.id 
             			AND p.id = pi.productId 
             			WHERE s.ident = '$ident' 
             			AND pi.isDefault = 'Yes' $sortOrder";
               if(count($select) > 0){
               return $this->_db->fetchAll($select);
          
               } else {
        	       throw new Zend_Exception('error!');
               }
	   }
	   
	  public function getDetailsByProduct($ident)
		  {
		  $select = $this->_db->select()
						->from("product as p")
		 				->joinInner(
		 				'product_tours as t', 't.id_product = p.id',
                        array("t.time_travel","t.location","t.communication","t.price","t.schedule","t.others")) 
         
						->where('p.ident = ?', $ident)
						->where('p.content_type = ?', $this->_content_type);
     
       if(count($select) > 0){
       return $this->_db->fetchRow($select);
       } else {
	       throw new Zend_Exception('error!');
       }
	       } 
	 
	 public function getCountProductByMenu($mid)
	{
		$select = $this->_db->select()
						->from("product as p", array("COUNT(*) as COUNT"))
						->JoinInner("product_relationships as r", "r.object_id = p.id")
						->JoinInner("menu_items as m", "r.category_id = m.id")
						->where("m.id = ?", $mid);
		 	return $this->_db->fetchRow($select);			
	}

	 
	     public function getlistProducts(){
    		$select = $this->_db->select()
        					->from(array('p' => 'product'), array('id','name','shortDescription','status'))
        					->joinInner(array('t' => 'product_tours'), 'p.id = t.id_product', array('t.price','featured'))
        					->order('p.id desc');
            $currentProducts = $this->_db->fetchAll($select);
            if(count($currentProducts) > 0) {
                return $currentProducts;
            }else{
                return null;
            }
    
    	}  
    	
  
    	
    	public function getOtherProductsFromCats($cats, $pid)
		{
			 $select = $this->_db->select()
			 			->from("product as p")
						->joinInner("product_tours as t", "p.id = t.id_product", array("t.time_travel","t.communication","t.schedule", "t.price"))
		 				->joinInner("productImage as i",
		 				'i.productId = p.id', array('full'))
		 				->where('p.id != ?', $pid)
		 				->where('i.isDefault = ?', 'Yes')		 			
		 				->where('i.content_type = ?', 'product')		 			
						->where('t.location IN (?)', $cats)
						->limit(3);
    
			$currentProducts = $this->_db->fetchAll($select);
			return $currentProducts;
		}
		
		public function getOtherTravelGuideFromCats($cats, $pid)
		{
			 $select = $this->_db->select()
			 			->from("product as p")
						->joinInner("product_relationships as r",
		 				'r.object_id = p.id', array('object_id'))
		 				->joinInner("productImage as i",
		 				'i.productId = p.id', array('full'))
		 				->where('p.id != ?', $pid)
		 				->where('i.isDefault = ?', 'Yes')		 			
						->where('r.category_id IN (?)', $cats)
						->limit(3);
    
			$currentProducts = $this->_db->fetchAll($select);
			return $currentProducts;
		}
	  
	   public function relatedProducts($catId, $subcatId = null)
	   {
	       //print_r($catId);
           
        $select = "SELECT p.* FROM product as p inner join product_relationships as t on p.id = t.object_id WHERE category_id = '$catId' ";
       if(count($select) > 0){
       return $this->_db->fetchRow($select);
       } else {
	       throw new Zend_Exception('error!');
       }
           
           /*
          	$select = $this->_db->select()
        					->from(array('p' => 'product'), array('id','name','shortDescription'))
        					->joinInner(array('t' => 'product_relationships'), 'p.id = t.object_id');
		   //$select = $this->select();
		   	
		   		if($subcatId == null){
			   		$select->where('category_id = ?', $catId);
		   		}else{
			   	//	$select->where('subcategoryId = ?', $subcatId);
		   		}
                //Zend_Debug::dump($select);
		  // exit();
		   $result = $this->fetchAll($select);
           
		   return $result;	*/  
	   }
	   
	   public function featureProducts()
	   {
		    $select = "select p.id, p.ident, p.name, p.tags, t.price, t.time_travel, i.full from product as p inner join product_tours as t left join productImage as i on t.id_product = p.id and i.productId = p.id where p.content_type = '$this->_content_type' and i.isDefault = 'Yes' and t.featured = 1 and p.status = 1 order by p.date desc limit 12";
		    $result = $this->_db->fetchAll($select);
		    return $result;
	   }
	   
	   public function promotionProducts()
	   {
		    $select = "select p.id, p.ident, p.name, p.shortDescription, p.tags, t.price, t.location, t.time_travel, t.discountPercent, i.full from product as p inner join product_tours as t left join productImage as i on t.id_product = p.id and i.productId = p.id where t.discountPercent != 0 and p.content_type = '$this->_content_type' and i.isDefault = 'Yes' order by p.date desc LIMIT 2";
		    $result = $this->_db->fetchAll($select);
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
	   
	   public function countNumber(){
		   $count = 'SELECT count(id) FROM `product` WHERE `content_type` = "'.$this->_content_type.'"';
		   $t= $this->_db->fetchRow($count);
		   return $t['count(id)'];
	   }
	   
	/*   public function delete($id)
	   {
		   $delete = "delete from product where id = $id";
		   $this->_db->query($delete);
		   
		    $delete2 = "delete from product_tours where id_product = $id";
		   $this->_db->query($delete2);
		   
		    $delete3 = "delete from product_relationships where object_id = $id";
		   $this->_db->query($delete3);
		   
		    $delete4 = "delete from productImage where productId = $id and content_type = 'product'";
		   $this->_db->query($delete4);
		   
		    $delete5 = "delete from product_tags where product_id = $id";
		   $this->_db->query($delete5);
	   }
	   */
	   
	   public function delVideo($id)
	   {
		   $delete = "delete from product where id = $id";
		   $this->_db->query($delete);
		   
		   $delete3 = "delete from product_relationships where object_id = $id";
		   $this->_db->query($delete3);
	   }
	   
       /*tungpk*/
       
       public function getlistProductsByContenType($content_type, $limit = 8){
    		$select = $this->_db->select()
        					->from(array('p' => 'product'), array('id','name','ident','shortDescription','description','content_type'))
        ->joinInner(array('pi' => 'productImage'), 'p.id = pi.productId', array('pi.full'));
            $select->where('isDefault = ?', 'Yes');					
            if($content_type != null){
			  		$select->where('p.content_type = ?', $content_type);
		   	}
		   	$select->order('p.id desc');
		   	$select->limit($limit);
            $currentProducts = $this->_db->fetchAll($select);
            if(count($currentProducts) > 0) {
                return $currentProducts;
            }else{
                return null;
            }
    
    	}  
    	
        public function getMenuInfo($ident_cattour){
            
			$se = "select id, name, description, link, image from menu_items  where link = '$ident_cattour'";
			
			$result = $this->_db->fetchAll($se);
		    return $result;
            
            
            
            
        }
        
        
         public function getCountNews()
		 {
		 $select = $this->_db->select()
						->from("product as p", array("COUNT(*) as COUNT"))
						->where("p.content_type = ?", 'new');
		 	return $this->_db->fetchRow($select);			
		}
		
		 public function getCountVideo()
		 {
		 $select = $this->_db->select()
						->from("product as p", array("COUNT(*) as COUNT"))
						->where("p.content_type = ?", 'video');
		 	return $this->_db->fetchRow($select);			
		}
		
		public function getCountImage()
		 {
		 $select = $this->_db->select()
						->from("productImage as i", array("COUNT(*) as COUNT"));
						
		 	return $this->_db->fetchRow($select);			
		}
       
   }