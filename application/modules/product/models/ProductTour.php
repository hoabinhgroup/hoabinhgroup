<?php
class Product_Model_ProductTour extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'product_tours';
       protected $_primary  = 'id';
       protected $_product_model;
       
      public function __construct(){
	   $this->_product_model = new Product_Model_Product();
	    $this->_db = Zend_Registry::get('db');
		}
    		
	   public function create($data)
		  {
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->id_product = $data['id_product'];
	   		$row->location = $data['location'];
	   		$row->time_travel = $data['time_travel'];
	   		$row->communication = $data['communication'];
	   		$row->price = $data['price'];
	   		$row->others = $data['others'];
	   		$row->discountPercent = $data['discountPercent'];
	   		$row->schedule = $data['schedule'];
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
	     $row->price = $data['price'];
	     $row->others = $data['others'];
		 $row->communication = $data['communication'];
		 $row->location = $data['location'];
		 $row->time_travel = $data['time_travel'];
		 $row->discountPercent = $data['discountPercent'];
		 $row->schedule = $data['schedule'];
		 $row->save();
         return true;
        }else{
	        throw new Zend_Exception("Cập nhật thất bại!");
        }
        	
        }	
   
   	 	
   	 	
   	function get_details($options = array()) {

        $select = $this->_db->select()
        					->from(array('p' => 'product'), array('p.id as pid','date','name','ident'))
        					->joinInner('product_tours as t', 't.id_product = p.id')
        					->joinInner("productImage as i", "i.productId = p.id", array("full"))
        					->where("p.status = ?", 1)
        					->where("i.content_type = ?", 'product')
        					->where("i.isDefault = ?", 'Yes');  
        					     					
        $id = $this->get_array_value($options, "id");
        if ($id) {
            $select->where('p.id = ?', $id);
        }        
         $id_product = $this->get_array_value($options, "id_product");
        if ($id_product) {
           $select->where('id_product = ?', $id_product);
        } 
        
         $featured = $this->get_array_value($options, "featured");
        if ($featured) {
           $select->where('p.featured = ?', $featured);
        }         
        
        $select->order("p.id Desc");  
        
         $limit = $this->get_array_value($options, "limit");
        if ($limit) {
           $select->limit($limit);
        }  
            				
       	$result = $this->_db->fetchAll($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    }
    //$sortid = null, $price = null, $limit = ''
    function get_locations($options = array())
    {
	    $select = $this->_db->select()
				 ->from("product as p")
				 ->joinLeft("product_tours as t","t.id_product = p.id", array("location", "id_product","price","time_travel","communication","discountPercent","schedule","tour_type", "travel_type"))
				 ->joinLeft("productImage as i", "i.productId = p.id", array("full"))
				 ->where("p.status = ?", 1) //AND price < 2000000
				 ->where("i.content_type = ?", 'product')  //AND price < 2000000
				 ->where("i.isDefault = ?", 'Yes');  //AND price < 2000000
				 
		$id_menu = $this->get_array_value($options, "menu_id");	
			if($id_menu)
			{
				$select->where("FIND_IN_SET($id_menu,t.location)>0");
			}		 
			   
		$price =  $this->get_array_value($options, "price");	   		
			 if($price)
			     		{		
				     
					 $price = explode(',',$price); 
	
					 	if(is_array($price)){
					 	$price_arr = $price;
					}else{
						$price_arr = array($price);
						}
					
				      $stt = 0;
				foreach($price_arr as $val): $stt++;
				   if($val == 'price_1'){
					  $sql = "t.price < 2000000";   	   
			       }
			      
			      if($val == 'price_2'){
				      $sql = "t.price > 2000000 AND t.price < 4000000";				 			       
			      }
			      
			      if($val == 'price_3'){
				      $sql = "t.price > 4000000 AND t.price < 6000000";				    
			      }
			      
			       
			        if($stt == 1)
				      $select->where($sql);	
				      else
				      $select->orWhere($sql);
				   endforeach;  
			      }
			      
			      
			   		
			   		
			   $sortid =  $this->get_array_value($options, "sortid");		      
			     if($sortid){
			      if($sortid == 1){
				   $select->order('t.price ASC');   
			      }elseif($sortid == 2){
				    $select->order('t.price DESC');    
			      }elseif($sortid == 3){
				    $select->order('t.time_travel ASC');    
			      }elseif($sortid == 4){
				    $select->order('t.time_travel DESC');    
			      }else{
				     $select->order('p.date DESC');  
			      }
		     } 
			 $limit =  $this->get_array_value($options, "limit");	
	
				 if($limit){
					 $select->limit($limit);
				 }
			
			
	 
				 
			 $per_page = $this->get_array_value($options, "per_page");
        if ($per_page) {
	       $page = $this->get_array_value($options, "page");
           $select->limitPage($page, $per_page);
           
        }	 
			
			$tour = $this->get_array_value($options, "tour");
			$travel = $this->get_array_value($options, "travel");
			
			
			
			if($tour)
				{
		 			
				$tour = explode(',',$tour); 
	
					 	if(is_array($tour)){
					 	$tour_arr = $tour;
					}else{
						$tour_arr = array($tour);
						}
				
					$i = 0;
				foreach($tour_arr as $vt): $i++;
				if($vt == 'tour_1'){
					$sql = "FIND_IN_SET('tour_1',t.tour_type)>0";
				}
				
				if($vt == 'tour_2'){
				$sql = "FIND_IN_SET('tour_2',t.tour_type)>0";	
				}
				
				if($vt == 'tour_3'){
				 $sql = "FIND_IN_SET('tour_3',t.tour_type)>0";	
				}
				
				 $select->where($sql);	
				endforeach;		
				
			}
			
			
			if($travel)
				{
		 			
				$travel = explode(',',$travel); 
	
					 	if(is_array($travel)){
					 	$travel_arr = $travel;
					}else{
						$travel_arr = array($travel);
						}
				
				
				foreach($travel_arr as $vtr): 
				if($vtr == 'travel_1'){
					$sql = "FIND_IN_SET('travel_1',t.travel_type)>0";
				}
				
				if($vtr == 'travel_2'){
				$sql = "FIND_IN_SET('travel_2',t.travel_type)>0";	
				}
				
				if($vtr == 'travel_3'){
				 $sql = "FIND_IN_SET('travel_3',t.travel_type)>0";	
				}
				
				 $select->where($sql);	
				endforeach;		
				
			}
				   
       
       return $this->_db->fetchAll($select);
    
			
         
        
    }
    
  		       
   }