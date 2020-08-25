<?php
class Form_Group extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);
        // create new element
        $name = $this->createElement('text', 'title');
        // element options
        $name->setLabel('Tiêu đề: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        // add the element to the form
        $this->addElement($name);
 

    // create new element
    $description = $this->createElement('textarea', 'description');
    // element options
    $description->setLabel('Miêu tả: ');
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);
    // add the element to the form
    $this->addElement($description);
    // create new element
   
   
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}