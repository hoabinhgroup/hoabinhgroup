<?php 
class SearchController extends Louis_Controller_Action{ 
   
		
	   public function indexAction(){
	    $this->view->search_category = $this->_request->getParam('search_category');
	    $this->view->sub_category = $this->_request->getParam('sub_category');
	    $this->view->duration = $this->_request->getParam('duration');
	    $db = Zend_Db_Table::getDefaultAdapter();
	    $query = '';
    	$category = $this->_request->getParam('search_category');
    	$sub_category = $this->_request->getParam('sub_category');
    	$duration = $this->_request->getParam('duration');
    	
    	if(($category != null) and ($sub_category != null)){
	    	$cat = $sub_category;
    	}elseif (($category != null) and ($sub_category == null)){
	    	$cat = $category;
    	}else{
	    	$cat = $sub_category;
    	}
    	
    	$pageCurrent = 1;
    	
    	if (($cat != 0) && ($duration != 0)){
	    	$query .= ' where r.category_id = "'.$cat.'" and t.time_travel like "'.$duration.'"';
    	}elseif(($cat != 0) && ($duration == 0)){
	    	$query .= ' where r.category_id = "'.$cat.'"';
    	}elseif(($cat == 0) && ($duration != 0)){
	    	$query .= ' where t.time_travel like "'.$duration.'"';
    	}
    	
    	$select = 'select p.*, t.time_travel, t.price, t.discountPercent from product as p inner join product_relationships as r inner join product_tours as t on p.id = t.id_product and p.id = r.object_id'.$query.' group by p.id';
    	$data = $db->fetchAll($select);
    	
    
    $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	 
	$paginator->setItemCountPerPage(5);
	
	$this->view->currentPage = $page = $this->_request->getParam('page', 1);
	
	$paginator->setCurrentPageNumber($page);

	$this->view->paginator = $paginator;
	
	$pagecount =  $paginator->getPages()->pageCount;



     if ($pageCurrent > $pagecount){
	  //  throw new Zend_exception('Không tồn tại trang '.$pageCurrent);
	  $this->view->message =  'Chưa có dữ liệu';
      }

    	//echo $category;
    	
    	
    	}
    
    public function buildAction()
    {
	    set_time_limit(500); //500 sec limit
	    $index = Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes');
	    
	    $page = new New_Model_Product();
	    $options = array(
		    'content_type' => 'new',
		    'lang' => 'vi',
	    );
	    $currentPages = $page->get_details($options);
	    

	    if(count($currentPages) > 0) {
	    foreach ($currentPages as $p) {
		
                // $page = new CMS_Content_Item_Page($p->id);
                 $doc = new Zend_Search_Lucene_Document();
                 // you use an unindexed field for the id because you want the id
                 // to be included in the search results but not searchable
                 $doc->addField(Zend_Search_Lucene_Field::unIndexed('id',
                    $p['id']));
                 // you use text fields here because you want the content to be searchable
                 // and to be returned in search results
                 $doc->addField(Zend_Search_Lucene_Field::text('name',
                     $p['name'], 'UTF-8'));
                // $doc->addField(Zend_Search_Lucene_Field::text('description',
                   //  $p['description'], 'UTF-8'));
                 // add the document to the index
                 $index->addDocument($doc);
				}
	     }
	     	$index->commit();
			$index->optimize();
         // pass the view data for reporting
         $this->view->indexSize = $index->numDocs();
	    
	   
    }
    
    public function entireAction()
    {
	    if($this->_request->isPost()) {
             $keywords = $this->_request->getParam('searchword');
             // search chinh xac tu va ket hop tu
            // $phrase_keywords = explode(' ', $keywords);
       
          // $query = new Zend_Search_Lucene_Search_Query_Phrase($phrase_keywords);
            $query = Zend_Search_Lucene_Search_QueryParser::parse($keywords);
             $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes');
             $hits = $index->find($query);
             $this->view->results = $hits;
             $this->view->keywords = $keywords;
         }else{
             $this->view->results = null;
}
    }
    
    public function testAction(){
	   
	    
    }
    
   
}