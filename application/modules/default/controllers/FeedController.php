<?php
class FeedController extends Louis_Controller_Action
   {
      
		
		public function rssAction()
		{
			//$this->_helper->layout->disableLayout();
			
			
			// build the feed array
			$feedArray = array();
			// the title and link are required
			$feedArray['title'] = 'Recent Pages';
			$feedArray['link'] = 'http://tour.louis-intelli.com';
			// the published timestamp is optional
			$feedArray['published'] = Zend_Date::now()->toString(Zend_Date::TIMESTAMP);
			// the charset is required
			$feedArray['charset'] = 'UTF8';
			
			
			$mdlPage = new Product_Model_Product();
			$recentPages = $mdlPage->getRecentPages();
	
			if(is_array($recentPages) && count($recentPages) > 0) {
    foreach ($recentPages as $page) {
	    
        // create the entry
        $entry = array();
        $entry['guid'] = $page['id'];
        $entry['title'] = $page['name'];
        if ($page['content_type'] == 'tour'){
        $entry['link'] = 'http://tour.louis-intelli.com/touris/' . $page['ident'] . '.html';
        }elseif($page['content_type'] == 'hotel') {
	     $entry['link'] =  'http://tour.louis-intelli.com/hotels/' . $page['ident'] . '.html';  
        }
        elseif($page['content_type'] == 'new') {
	     $entry['link'] =  'http://tour.louis-intelli.com/news/' . $page['ident']. '.html';  
        }elseif($page['content_type'] == 'flight-booking') {
	     $entry['link'] =  'http://tour.louis-intelli.com/flight-booking/' . $page['ident']. '.html';  
        }elseif($page['content_type'] == 'car-rental') {
	     $entry['link'] =  'http://tour.louis-intelli.com/car-rental/' . $page['ident']. '.html';  
        }elseif($page['content_type'] == 'conference') {
	     $entry['link'] =  'http://tour.louis-intelli.com/conference-mice/' . $page['ident']. '.html';  
        }
        elseif($page['content_type'] == 'testimonial') {
	     $entry['link'] =  'http://tour.louis-intelli.com/testimonial/' . $page['ident']. '.html';  
        }else {
	       $entry['link'] =  'http://tour.louis-intelli.com/' . $page['ident']. '.html'; 
        }
        $entry['description'] = $page['shortDescription'];
        $entry['content'] = $page['shortDescription'];
        // add it to the feed
        $feedArray['entries'][] = $entry;
        
     
    }
      // create an RSS feed from the array
	   $feed = Zend_Feed::importArray($feedArray, 'rss');
	   // now send the feed
	   $this->_helper->viewRenderer->setNoRender();
	   $this->_helper->layout->disableLayout();
	   $feed->send();
}
		}
}
