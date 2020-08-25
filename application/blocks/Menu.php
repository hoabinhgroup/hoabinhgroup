 <?php
class Block_Menu extends Zend_View_Helper_Abstract{
    public function Menu(){
        //echo "Menu";
		$mdlMenuItems = new Model_MenuItem();
	    $menuItems = $mdlMenuItems->getItemsByMenu(2)->toArray();

			foreach ($menuItems as $item):
			 $label = $item['label'];
            if(!empty($item['link'])) {
                $uri = 'http://'.$_SERVER['SERVER_NAME'].$item['link'];
            }else{
                $uri = '/page/open/id/' . $item['page_id'];
            }
            
				$db = Zend_Db_Table::getDefaultAdapter();
				
				$select = $db->select()
					 ->from('menu_items')
					 ->where('parent = ?', $item['id'])
					 ->order('position');
					 
				$result = $db->fetchAll($select);
				
				$count = count($result);
				
				if($count > 0){
					?>
			
					<li class="parent dropdown ">
					<a class="dropdown-toggle" data-toggle="dropdown" href="<?=$uri?>"><span class="menu-title"><?=$label?></span><b class="caret"></b></a>
					
					<div class="dropdown-menu">
						<div class="dropdown-menu-inner">
					<div class="row"><div class="mega-col col-xs-12 col-sm-12 col-md-12" data-type="menu"><div class="mega-col-inner">
					<ul>
					
			<?php foreach ($result as $k=>$v):
				 if(!empty($v['link'])) {
                $suri = 'http://'.$_SERVER['SERVER_NAME'].$v['link'];
            }else{
                $suri = '/page/open/id/' . $v['page_id'];
            }
					 ?>
	<li class=""><a href="<?=$suri?>"><span class="menu-title"><?=$v['label']?></span></a></li>
					<?php endforeach; ?>
					
				
				</ul></div></div></div></div></div></li>	
					
					<?php
				} else {
					?>
					
             <li class="">
					<a href="<?=$uri?>"><span class="menu-title"><?=$label?></span></a>
				
			</li>  
               <?php
               }
			endforeach;
				
    }
} 