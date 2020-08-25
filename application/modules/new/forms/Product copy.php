<?php
class New_Form_Product extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tiêu đề tin: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        
        $this->addElement($name);
        
        $this->addElement('select','content_type', array( 
    'label' => 'Chọn kiểu', 'value' => 'new', 
    'multiOptions' => array( 'new' => 'Kiểu tin tức','tin-noi-bo' => 'Kiểu tin nội bộ', 'brand' => 'Kiểu thương hiệu', 'single' => 'Kiểu trang đơn'), ) );
        // create new element
       /* $headline = $this->createElement('text', 'headline');
        // element options
        $headline->setLabel('Headline: ');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size',50);
    // add the element to the form
    $this->addElement($headline);  */
    // create new element
        $this->addElement('Select', 'parentID[]', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-multi-level.phtml',
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
    
     $tag = $this->createElement('text', 'tags');
       
        $tag->setLabel('Tags: ');
       // $tag->setDescription('Nhập các tags ngăn cách nhau bởi dấu phẩy.');
        $tag->setAttrib('size',60);
        $this->addElement($tag);
    
     $this->addElement('text', 'date', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml'
      )))
      ));
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}