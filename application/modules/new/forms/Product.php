<?php
class New_Form_Product extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        
          $ns = new Zend_Session_Namespace('language');
	    $lang = $ns->lang;
	   if (empty($ns->lang)){
			$lang = 'vi';
			 }
       
        $id = $this->createElement('hidden', 'id');
      
        $id->setDecorators(array('ViewHelper'));
       
        $this->addElement($id);
        
     
        $name = $this->createElement('text', 'name');
       
        $name->setLabel('Tiêu đề: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',60);
        $name->setAttrib('onkeyup',"ChangeToSlug();");
        $name->setAttrib('autocomplete',"off");
        
        
        
         $slug = $this->createElement('text', 'ident');
       
        $slug->setLabel('Đường dẫn: ');
        $slug->setRequired(TRUE);
        $slug->setAttrib('size',60);
        
       
        
        $date = $this->createElement('text', 'date');
        $date->setRequired(TRUE);
        $date->setDecorators(array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml'
      )))
      	);
      	
 
      
     $mdlMenu = new Model_MenuItem();
      $select = $mdlMenu->select();
       // $select->where('parent = ?', 0);
        $select->where('menu_id = ?', 2);
        $select->where('lang = ?', $lang);
        $select->order('position asc');
        $menus = $mdlMenu->fetchAll($select);
        
        $recursive = new Louis_System_Recursive($menus->toArray());
        if($lang == 'vi'){
	        $sub = 7;
        }else{
	        $sub = 136;
        }
        $newArr = $recursive->buildArray($sub);
       
       $new_arr = array();
       foreach($newArr as $key=>$val):
       if($val['parent'] != 0){
	     $new_arr[$val['id']] = '----'.$val['name'];  
       }else{
       $new_arr[$val['id']] = $val['name'];
       }
       endforeach;
     
     
    
     
     $multi = new Zend_Form_Element_Multiselect('parentID');
$multi->setMultiOptions($new_arr);
$multi->setLabel('Chọn danh mục: ');
$multi->setAttrib('style','width:200px');
$multi->setRegisterInArrayValidator(false)->setRequired(true);


      
      
        
     // create new element
    $description = $this->createElement('textarea', 'shortDescription');
    // element options
    $description->setLabel('Miêu tả ngắn: ');
    $description->setRequired(TRUE);
    $description->setAttrib('cols',82);
    $description->setAttrib('rows',4);
    // add the element to the form
    
    
    // create new element
    $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Nội dung');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form
    
 
    
     $tag = $this->createElement('text', 'tags');
       
        $tag->setLabel('Từ khóa: ');
       // $tag->setDescription('Nhập các tags ngăn cách nhau bởi dấu phẩy.');
        $tag->setAttrib('style','width:300px;');
        
       
      $cover =  $this->addElement('Select', 'cover', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_upload_cover.phtml',
      )))
      
      ));
      
      /*
        
      $status =  $this->addElement('checkbox', 'status', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_status.phtml',
                         'List' => (isset($this->_attribs['status']))?$this->_attribs['status']:0
      )))
      
      ));
      */
            
   
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Lưu',
        							'attribs' => array('class' => 'btn btn-info', 
        							'onClick' => 'form.submit();this.disabled=true')
        											
        								));
        								
        								
         $this->addElements( array (
        
                            $name,
                            $slug,
                            $date,
                            $multi,
                            $description,
                            $content,
                            $tag,
                            $cover,
                          //  $status,
                           $submit
                            )
                );
    
    
 
        
        $this->addDisplayGroup(array(
        
                    'name',
                    'ident',
                    'shortDescription',
                    'description',
        
            ),'left');
        
        $col = $this->getDisplayGroup('left');
        $col->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','class'=>'col-md-8'))
        ));
    
    
    $this->addDisplayGroup(array(
        			'parentID',	
                    'date',       
                    'tags',
                    'cover',
                  //  'status',
                    'submit'
        
            ),'right');
        
        $col = $this->getDisplayGroup('right');
        $col->setDecorators(array(
        
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag',array('tag'=>'div','class'=>'col-md-4'))
        ));
    
								
        								
        								
        								
}
}