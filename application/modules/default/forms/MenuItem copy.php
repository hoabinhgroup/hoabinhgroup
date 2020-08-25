<?php
class Form_MenuItem extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);
        // create new element
        $menuId = $this->createElement('hidden', 'menu_id');
        // element options
        $menuId->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($menuId);
       
        // $a = null;
       
          $this->addElement('Select', 'parentID', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-multi-level.phtml',
                        
      )))
      ));
      	
        // create new element
        $label = $this->createElement('text', 'name');
        // element options
        $label->setLabel('Tiêu đề: ');
        $label->setRequired(TRUE);
        $label->addFilter('StripTags');
        $label->setAttrib('size',40);
        // add the element to the form
        $this->addElement($label);
        // create new element
        $pageId = $this->createElement('select', 'page_id');
        // element options
        $pageId->setLabel('Xin chọn 1 trang để link đến: ');
        $pageId->setRequired(true);
        // populate this with the pages
        $mdlPage = new Model_Page();
        $pages = $mdlPage->fetchAll(null, 'name');
        $pageId->addMultiOption(0, 'None');
        if($pages->count() > 0) {
            foreach ($pages as $page) {
                $pageId->addMultiOption($page->id, $page->name);
				} 
			}
        // add the element to the form
        $this->addElement($pageId);
        // create new element
        $link = $this->createElement('text', 'link');
        // element options
        $link->setLabel('hoặc đường dẫn khác: ');
        $link->setRequired(false);
        $link->setAttrib('size',50);
        // add the element to the form
        $this->addElement($link);
       // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
       /*
       $checkbox = $this->addElement('checkbox', 'isHome', array(
	   	'label' => 'Hien thi o trang chu',
	   	'name' => 'isHome',
	   	'checkedValue' => 'Yes',
	   	'uncheckedValue' => 'No',
	   	));
	   	$checkbox->setChecked(true);
*/

 $description = $this->createElement('textarea', 'description');
    // element options
    $description->setLabel('Description: ');
    $description->setRequired(TRUE);
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);
    // add the element to the form
    $this->addElement($description);
    
    
 $is_conveyance_required = new Zend_Form_Element_Checkbox('is_conveyance_required', array('label' => 'Hiển thị ở trang chủ','checkedValue'  => 'Yes','uncheckedValue' => 'No',));

   $is_conveyance_required->addDecorators(array(
        array('HtmlTag', array('tag' => 'label')),
        array('Label', array('tag' => 'Hien thi menu trang chu')),
   ));
    // if($a[0]['isHome'] == 'Yes'){
   //$is_conveyance_required->setValue('Yes');
   $is_conveyance_required->setChecked( false );
  // }
   $this->addElement($is_conveyance_required);
       
       $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
    }
}
