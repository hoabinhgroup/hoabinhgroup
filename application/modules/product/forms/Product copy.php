<?php
class Product_Form_Product extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tên sản phẩm: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        
        $this->addElement($name);
        
         $price = $this->createElement('text', 'price');
       
        $price->setLabel('Giá: ');
        $price->setRequired(TRUE);
        $price->setAttrib('size',20);
        $this->addElement($price);
        // create new element
       /* $headline = $this->createElement('text', 'headline');
        // element options
        $headline->setLabel('Headline: ');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size',50);
    // add the element to the form
    $this->addElement($headline);  */
    // create new element
     $db = Zend_Db_Table::getDefaultAdapter();
	
    $mycats = $db->select()
        ->from('menu_items', array('id', 'name'))
        ->where('menu_id = ?', 5)
        ->where('parent = ?', 0)
        ->query()
        ->fetchAll();
        
   
    
     $mycatOptions[0] = 'Chọn danh mục';
    
      foreach( $mycats as $mycat ) {
     $mycatOptions[$mycat['id']] = $mycat['name'];
    }
    
   
  
    if( count($mycatOptions) > 0 ) {
      $this->addElement('Select', 'categoryId', array(
        'label' => 'Category',
        'multiOptions' => $mycatOptions,
      ));
    }
    
     
    
     $this->addElement('Select', 'subcategory_id', array(
        'label' => '',
         'RegisterInArrayValidator' => false,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_formSubcategory.phtml',
      )))
      ));
    
     // create new element
    $description = $this->createElement('textarea', 'shortDescription');
    // element options
    $description->setLabel('Description: ');
    $description->setRequired(TRUE);
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);
    // add the element to the form
    $this->addElement($description);
    // create new element
    $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Content');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form
    $this->addElement($content);
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}