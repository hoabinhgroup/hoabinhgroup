<?php
class Louis_View_Helper_Content extends Zend_View_Helper_Abstract{
        
       public function content()
		 {
			 return $this;
		 }
		 
	  public function createBreadcrum($id, $category_tbl, $menu_id) {
		$db = Zend_Db_Table::getDefaultAdapter();
    $s = "SELECT * FROM ".$category_tbl." WHERE menu_id = '".$menu_id."' and id = '".$id."'";
    $row = $db->fetchRow($s);

    if($row['parent'] == 0) {
        return '<li> <a href="/'.$row['link'].'">'.$row['name'].'</a> <span class="divider">/</span></li>';
    } else {
        return $this->createBreadcrum($row['parent'],$category_tbl, 2).' <li> <a href="/'.$row['link'].'">'.$row['name'].'</a> <span class="divider">/</span></li>';
      }
          }		

		  public function getUsernameFB($uid){
    $url = "http://graph.facebook.com/".$uid;
	$res = json_decode( file_get_contents( $url ));
	
	return $res;
	}


    } 