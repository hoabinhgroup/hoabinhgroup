<?php
class Product_Model_ProductTravelGuide extends Louis_Db_Table_Abstract 
     {
       protected $_name    = 'product_travel_guide';
       protected $_primary  = 'id';
       protected $_content_type  = 'travel_guide';
       
    		
	   public function create($data)
		  {
	   		//tạo san pham mới
	   		$row = $this->createRow();
	   		$row->id_product = $data['id_product'];
	   		$row->location = $data['location'];
	   		$id = $row->save();
	   		return $id;
   	 	}
   	 	
   	  public function updateProduct($id, $data)
		{
				
    // lấy dữ liệu từ id
    $row = $this->find($id)->current();
    
    if($row) {
        // update cột trong page table
	   		$row->location = $data['location'];
		 $row->save();
         return true;
        }else{
	        throw new Zend_Exception("Cập nhật thất bại!");
        }
        	
        }	
        
        
    function get_one_custom($options = array()) {

        $select = $this->_db->select()
        					->from(array("p" => 'product'))
        					->joinInner("product_travel_guide as g","g.id_product = p.id", array("g.location"))
							->joinInner("productImage as i", "i.productId = p.id", array("full"))
				 //->where("p.status = ?", 1) //AND price < 2000000
				 ->where("i.content_type = ?", 'product')
				 ->where("i.isDefault = ?", 'Yes');  //AND price < 2000000;
        					        					
        $id = $this->get_array_value($options, "id");
        if ($id) {
            $select->where('p.id = ?', $id);
        } 
        $ident = $this->get_array_value($options, "ident");
        if ($ident) {
            $select->where('p.ident = ?', $ident);
        }          
         $id_product = $this->get_array_value($options, "id_product");
        if ($id_product) {
           $select->where('g.id_product = ?', $id_product);
        }   
        
          $status = $this->get_array_value($options, "status");
        if ($status) {
           $select->where('p.status = ?', $status);
        }           				
       	$result = $this->_db->fetchRow($select);	
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    }     
   
   	 	
   	 	
   	function get_details($options = array()) {

      
        $select = $this->_db->select()
        					->from(array('g' => $this->_name))
        					->joinInner('product as p', 'p.id = g.id_product', array("p.id as pid","name", "ident","shortDescription"))        			
        					->joinInner('productImage as i', 'p.id = i.productId', array("full"))
        					->where("i.content_type = ?", 'product')  		
        					->where("i.isDefault = ?", 'Yes');     		
        $id = $this->get_array_value($options, "id");
        if ($id) {
            $select->where('pid = ?', $id);
        }        
        
         $id_product = $this->get_array_value($options, "id_product");
        if ($id_product) {
           $select->where('g.id_product = ?', $id_product);
        } 
        
       $order = $this->get_array_value($options, "order"); 
       if ($order) {
        $select->order($order);
        }else{
	    $select->order("pid desc");    
        }
        
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
    
    public function getlistProductsByCats($listId = array())
	  	{
		  
		  $select = $this->_db->select()->distinct()
						->from(array('p' => 'product'))
		 				->joinInner(
		 				'product_relationships as r',
		 				'r.object_id = p.id') 
                         ->joinInner(
		 				'menu_items as m',
		 				'r.category_id = m.id', array("m.name as menu_title")) 
						->where('m.id IN (?)', $listId)
						->group('p.id')
						->order('p.id desc');
    
			$currentProducts = $this->_db->fetchAll($select);
			
			if(count($currentProducts) > 0) {
				return $currentProducts;
				}else{
				return null;
    		}

		} 
		
	public function getProducts($featured = 0)
	{
		 	      $select = $this->_db->select()
						->from(array('p' => 'product'), array("id","ident","name","shortDescription"))
                         ->joinInner(
		 				'productImage as i',
		 				'i.productId = p.id', array("full")) 
						->where('p.content_type = ?', $this->_content_type)
						->where('i.isDefault = ?', 'Yes')
						->where('p.status = ?', 1);
				if($featured == 1){
					$select->where('p.featured = ?', 1);
				}		
						
					$select->order('p.id desc');
    
			$currentProducts = $this->_db->fetchAll($select);
			
			if(count($currentProducts) > 0) {
				return $currentProducts;
				}else{
				return null;
    		}

	}	
	
	public function getProductsByCategory($ident = 'am-thuc')
	{
		 $select = $this->_db->select()
						->from(array('p' => 'product'), array("p.id as pid","p.ident","p.name as pname","p.shortDescription"))
						->joinInner(
		 				'product_travel_guide as g',
		 				'g.id_product = p.id') 
						->joinInner(
		 				'product_relationships as r',
		 				'r.object_id = p.id') 
						->joinInner(
		 				'menu_items as m',
		 				'm.id = r.category_id') 
                         ->joinInner(
		 				'productImage as i',
		 				'i.productId = p.id', array("full")) 
		 				->where('m.link = ?', $ident)
						->where('p.content_type = ?', $this->_content_type)
						->where('i.isDefault = ?', 'Yes')
						->where('p.status = ?', 1);
						
					$select->order('pid desc');
					
						$currentProducts = $this->_db->fetchAll($select);
			
			if(count($currentProducts) > 0) {
				return $currentProducts;
				}else{
				return null;
    		}
	}
	
	
	public function get_locations()
    {
	    $select = $this->_db->select()
				 ->from("product as p")
				 ->joinInner("product_travel_guide as g","g.id_product = p.id", array("g.location"))
				 ->joinInner("productImage as i", "i.productId = p.id", array("full"))
				 ->where("p.status = ?", 1) //AND price < 2000000
				 ->where("p.content_type = ?", $this->_content_type) //AND price < 2000000
				 ->where("i.isDefault = ?", 'Yes'); 
			
		/*	if ($location_arr != null){	 
				$locations = explode(',',$location_arr);
				$stt = 0;
				echo '<pre>';
				print_r($locations);
				echo '</pre>'; die();
			foreach($locations as $obj): $stt++;
			$sql = "g.location = $obj";
			if($stt == 1){
			$select->where($sql); 	
			}else{
			$select->orWhere($sql); 	
			}
			
			endforeach;
			}
			*/   
       return $this->_db->fetchAll($select);
    }

	public function get_travel_guide_relation_locations($location_arr)
	{
		
	$locations_travel_guide = $this->get_locations();
	
	$ptg = array();
	foreach($locations_travel_guide as $kg=>$vg):
	
		 $exp_location_g = explode(',', $vg['location']);
	
		  foreach($location_arr as $kg_obj):
		  if(in_array($kg_obj, $exp_location_g)){
			$ptg[] = array(
			'id' => $vg['id'],
			'ident' => $vg['ident'],
			'name' => $vg['name']		
			); 
			  
			  }
		  
		  endforeach;
	endforeach;
	    
	return $ptg;
			
	}
  		       
   }