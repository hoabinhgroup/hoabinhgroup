<?php
class Product_Form_TravelGuideEdit extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tiêu đề : ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        
        $this->addElement($name);
        
         $slug = $this->createElement('text', 'ident');
       
        $slug->setLabel('Đường dẫn: ');
        $slug->setRequired(TRUE);
        $slug->setAttrib('size',40);
        
        $this->addElement($slug);
        

		 $this->addElement('Select', 'parentID[]', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-travel-guide-edit.phtml',
      )))
      ));
      
       $this->addElement('Select', 'location', array(
	       'required' =>false,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-location.phtml',
                        'List' => (isset($this->_attribs['location']))?$this->_attribs['location']:array()
      )))
      ));
    
	

     // create new element
    $description = $this->createElement('textarea', 'shortDescription');
    // element options
    $description->setLabel('Miêu tả ngắn: ');
    $description->setRequired(TRUE);
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);
    // add the element to the form
    $this->addElement($description);
    // create new element
    $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Nội dung');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form
    $this->addElement($content);
    
    
    
       $this->addElement('text', 'date', array(
	       'required' =>false,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml',
                         'List' => (isset($this->_attribs['date']))?$this->_attribs['date']:array()
      )))
      ));
      
         $tag = $this->createElement('text', 'tags');
       
        $tag->setLabel('Tags: ');
       // $tag->setDescription('Nhập các tags ngăn cách nhau bởi dấu phẩy.');
        $tag->setAttrib('size',60);
        $this->addElement($tag);
      
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}