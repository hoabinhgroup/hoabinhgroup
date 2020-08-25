<?php
class Form_Page extends Zend_Form
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
        $name = $this->createElement('text', 'name');
        // element options
        $name->setLabel('Tiêu đề: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        $name->setAttrib('onkeyup',"ChangeToSlugPage();");
        // add the element to the form
        $this->addElement($name);
        
        
         // element options
        $name_clean = $this->createElement('text', 'name_clean');
        $name_clean->setLabel('Đường dẫn: ');
        $name_clean->setRequired(TRUE);
        $name_clean->setAttrib('size',40);
        // add the element to the form
        $this->addElement($name_clean);

        // create new element
       /* $headline = $this->createElement('text', 'headline');
        // element options
        $headline->setLabel('Headline: ');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size',50);
    // add the element to the form
    $this->addElement($headline);  */
    // create new element

    // create new element
    $description = $this->createElement('textarea', 'description');
    // element options
    $description->setLabel('Description: ');
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);
    // add the element to the form
    $this->addElement($description);

   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}