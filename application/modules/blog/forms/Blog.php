<?php
class Blog_Form_Blog extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $id = $this->createElement('hidden', 'id');
   
        $id->setDecorators(array('ViewHelper'));

        $this->addElement($id);
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        
		/*
         $this->addElement('hidden', 'authorID', array(
		 'value' => $identity->id
		 ));
       */
      
        $authorID = $this->createElement('hidden', 'authorID');
   
        $authorID->setDecorators(array('ViewHelper'));
        
        $authorID->setValue($identity->id);

        $this->addElement($authorID);
 
        $name = $this->createElement('text', 'name');
    
        $name->setLabel('Blog Name: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
   
        $this->addElement($name);
        // create new element
       /* $headline = $this->createElement('text', 'headline');
        // element options
        $headline->setLabel('Headline: ');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size',50);
    // add the element to the form
    $this->addElement($headline);  */
    
    $image = $this->createElement('file', 'image');
 
    $image->setLabel('Image: ');
    $image->setRequired(FALSE);

    $image->setDestination(APPLICATION_PATH . '/../public/images/blog');
 
    $image->addValidator('Count', false, 1);
   
    $image->addValidator('Size', false, 302400);
 
    $image->addValidator('Extension', false, 'jpg,png,gif');
 
    $this->addElement($image);
 
    $description = $this->createElement('textarea', 'description');
   
    $description->setLabel('Description: ');
    $description->setRequired(TRUE);
    $description->setAttrib('cols',40);
    $description->setAttrib('rows',4);

    $this->addElement($description);
 
    $content = $this->createElement('textarea', 'content');

    $content->setLabel('Content');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);

    $this->addElement($content);
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Submit',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
}
}