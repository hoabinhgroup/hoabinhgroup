<?php
class Model_StatTracker extends Louis_Db_Table_Abstract 
     {
        protected $_name    = 'statTracker';
        
    public function init(){
	   		parent::init();
		}	
		

    public function get_details($options = array()) {

       
        $select = $this->_db->select()
        					->from(array('st' => $this->_name));
        						
        
        
       	$result = $this->_db->fetchAll($select);	

      
       	
       	if(count($result) > 0) {
                return $result;
            }else{
                return null;
            }				
    	
    }	
    
    public function add()
    {
	    	
    }
    
    public function trackBy($type = 'trackByPage',$per_page = 0, $offset = 0)
    {
	     $where = '';
	    if($per_page != 0){
		   $where = " LIMIT $offset, $per_page"; 
	    }
	   if($type == 'trackByPage') {
	     $trackBy = $this->_db->query("
	    	 SELECT DAY(FROM_UNIXTIME(thedate_visited)) as period, page, COUNT(DISTINCT IP) AS total 
			 FROM statTracker 
			 WHERE DAY(FROM_UNIXTIME(thedate_visited)) = DAY(NOW()) 
			 GROUP BY page 
			 ORDER BY total DESC 
			 $where"); 
		}elseif($type == "trackBySource") {
		 $trackBy = $this->_db->query("
	    	 SELECT DAY(FROM_UNIXTIME(thedate_visited)) as period, from_page as page, COUNT(DISTINCT IP) AS total 
			 FROM statTracker 
			 WHERE DAY(FROM_UNIXTIME(thedate_visited)) = DAY(NOW()) 
			 GROUP BY from_page  
			 ORDER BY total DESC 
			 $where");	
		}
		return $trackBy->fetchAll();	 
			
    }
      
      
    public function getVisits($stepDay = 0)
    {
	    $getVisits = $this->_db->query("
	    	 SELECT thedate_visited AS visitDays, COUNT( DISTINCT IP ) AS ip 
			 FROM statTracker 
			 WHERE DATE(FROM_UNIXTIME(thedate_visited)) = DATE(NOW() - INTERVAL ".$stepDay." DAY) AND browser NOT LIKE '%bot%'"); 
			 
		 if($getVisits){
		return $getVisits->fetchAll();
			}
    } 
    
     public function getViews($stepDay = 0)
    {
	    $getViews = $this->_db->query("
	    	 SELECT thedate_visited AS viewDays, DATE( FROM_UNIXTIME( thedate_visited ) ) as period,
	    	 COUNT(*) AS total 
			 FROM statTracker 
			 WHERE DATE(FROM_UNIXTIME(thedate_visited)) = DATE(NOW() - INTERVAL ".$stepDay." DAY) 
			 AND browser NOT LIKE '%bot%' 			 
			 GROUP BY period");
			 
		return $getViews->fetchAll();
    } 
    
    public function getBounceRate($stepDay = 0)
    {
	    $getBounceRate = $this->_db->query("
	    	 SELECT thedate_visited AS visitDays, COUNT( DISTINCT IP ) AS ip 
			 FROM statTracker 
			 WHERE DAY(FROM_UNIXTIME(thedate_visited)) = DAY(NOW() - INTERVAL ".$stepDay." DAY) 
			 AND browser NOT LIKE '%bot%' 
			 GROUP BY page 
			 HAVING ip = 1"
			 );	
			 
		if($getBounceRate){
		return $getBounceRate->fetchAll();
		}
    } 
     
	   
   }