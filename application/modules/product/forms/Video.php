<?php
class Product_Form_Video extends Zend_Form
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
                        'viewScript' => '_video-multi-level.phtml',
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
    
    
    
    $this->addElement('text', 'date', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml',
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