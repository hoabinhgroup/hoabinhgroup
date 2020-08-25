<?php
class Product_Form_VideoEdit extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tiêu đề: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        
        $this->addElement($name);
        
        
 
    // create new element
        $this->addElement('Select', 'parentID[]', array(
	     'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_video-multi-level-edit.phtml',
      )))
      ));
     
    
     // create new element
    $description = $this->createElement('text', 'shortDescription');
    // element options
    $description->setLabel('Link video: ');
	$description->setRequired(TRUE);
    $description->setAttrib('size',40);
    
    // add the element to the form
    $this->addElement($description);
    // create new element
   /* $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Nội dung:');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form
    $this->addElement($content);*/
    
    
    $this->addElement('text', 'date', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml',
                         'List' => (isset($this->_attribs['date']))?$this->_attribs['date']:array()
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