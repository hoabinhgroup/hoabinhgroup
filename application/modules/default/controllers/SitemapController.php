<?php
     
    class SitemapController extends Louis_Controller_Action
    {
        public function indexAction()
        {
            // action body
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
     
     
            $mdlMenuItem = new Model_MenuItem();
		    $menu = $mdlMenuItem->getItemsByMenu(2);
		    $menu = $menu->toArray();
         //   $news_tbl = new Model_DbTable_News();
		 	 $mdlPage = new New_Model_Product();
			$recentPages = $mdlPage->getRecentPages();

    //  $news = $news_tbl->get_news();
            $fh = fopen($_SERVER['DOCUMENT_ROOT']."/sitemap.xml", 'w') or die("can't open file");
            $time = date("Y-m-d")."T".date("H:i:s+00:00");
     
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9    
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
            xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                   ';                

            if($menu)
                foreach($menu as $key=>$sitemap)

                    $xml .= '<url>
            <loc>http://hoabinh-group.com/'.$sitemap['link'].'</loc>
                           <lastmod>'.$time.'</lastmod>
                           <changefreq>monthly</changefreq>
                           <priority>0.8</priority>
                   </url>
                   ';                
				   	
				 
     if($recentPages)
                foreach($recentPages as $page){
             
	      $link =  'http://hoabinh-group.com/tin-tuc/'; 
       
                    $xml .= '<url>                      
                    <loc>'.$link.$page['ident'].'.html</loc>
                           <changefreq>weekly</changefreq>
                           <priority>1.0</priority>
                   </url>
                   ';
             }     
            $xml .='</urlset>';
    
            fwrite($fh, $xml);
            fclose($fh);
            
            
        
    }
    }