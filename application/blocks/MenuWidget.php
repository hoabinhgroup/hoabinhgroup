<?php
  class Block_MenuWidget extends Zend_View_Helper_Abstract{
	  public function MenuWidget(){
		  
		   $ns = new Zend_Session_Namespace('language');
			  $lang = $ns->lang;
			  if (empty($ns->lang)){
			  $lang = 'vi';
			  }
			  
		  $file_menu = APPLICATION_PATH . '/cache/menu/'.$lang.'/menu.html';
		     
		     $expire = 86400; // 24 hours 
		     
			  
			 
		     
			 if (file_exists($file_menu) &&
			 filemtime($file_menu) > (time() - $expire))
			 {
			 // Uunserialize data từ cache file
			 $menu = unserialize(base64_decode(file_get_contents($file_menu)));
  
			}else {
		     
		             $mdlMenuItem = new Model_MenuItem();
		             $menu = $mdlMenuItem->getItemsByMenu(2, $lang);
		             
		             // Serialize data và push vào cache file
					 $OUTPUT_menu = base64_encode(serialize($menu));
					 $fp_menu = fopen($file_menu,"w");
					 fputs($fp_menu, $OUTPUT_menu);
					 fclose($fp_menu); 
		         }    

		            $recursive = new Louis_System_RecursiveMenu($menu->toArray());
		          return $result =  $recursive->buildArrayUl(0);

		  
	  }	  
	} 	
	
	