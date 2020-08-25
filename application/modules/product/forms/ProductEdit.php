<?php
class Product_Form_ProductEdit extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tên Tour: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',60);
        $name->setAttrib('onkeyup',"ChangeToSlug();");
        $name->setAttrib('autocomplete',"off");
        $this->addElement($name);
        
         $slug = $this->createElement('text', 'ident');
       
        $slug->setLabel('Đường dẫn: ');
        $slug->setRequired(TRUE);
        $slug->setAttrib('size',60);
        
        $this->addElement($slug);
        
 
    // create new element
      $this->addElement('Select', 'parentID[]', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-multi-level-edit.phtml',
      )))
      ));
      
      $price = $this->createElement('text', 'price');
       
        $price->setLabel('Giá Tour: ');
       // $price->setRequired(TRUE);
        $price->setAttrib('size',20);
        $this->addElement($price);
        
        
          $discount = $this->createElement('text', 'discountPercent');
       
        $discount->setLabel('(%) Giảm giá: ');
        $discount->setDescription('Chỉ nhập số nếu có');
        $discount->setAttrib('size',20);
        $this->addElement($discount);
  
      
       $this->addElement('Select', 'location', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-location.phtml',
                        'List' => (isset($this->_attribs['location']))?$this->_attribs['location']:array()
      )))
      ));
    
	  $time_travel = $this->createElement('text', 'time_travel');
        $time_travel->setLabel('Thời gian: ');
        $time_travel->setDescription('Nhập khoảng thời gian. VD: 4 days 3 nights nhập là 4.3 ');
        $time_travel->setRequired(TRUE);
        $time_travel->setAttrib('size',20);
        $this->addElement($time_travel);
        
        
         $this->addElement('Select', 'communication[]', array(
	       'required' =>true,
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-communication.phtml',
                         'List' => (isset($this->_attribs['communication']))?$this->_attribs['communication']:array()
      )))
      ));
      
      
      $schedule = $this->createElement('text', 'schedule');
       
        $schedule->setLabel('Lịch trình: ');
        $schedule->setRequired(FALSE);
        $schedule->setAttrib('size',40);
        
        $this->addElement($schedule);
          
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
    
      $others = $this->createElement('textarea', 'others');
    // element options
    $others->setLabel('Bảng giá & điều khoản');
    $others->setRequired(TRUE);
    $others->setAttrib('cols',50);
    $others->setAttrib('rows',12);
    // add the element to the form
    $this->addElement($others);
    
    
       $this->addElement('text', 'date', array(
	       'required' =>true,
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