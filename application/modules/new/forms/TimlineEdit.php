<?php
class New_Form_TimlineEdit extends Zend_Form
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
        $name->setAttrib('autocomplete',"off");
        
        
        
        
         $date = $this->createElement('text', 'date');
        $date->setRequired(TRUE);
        $date->setDecorators(array(array('ViewScript', array(
                        'viewScript' => '_select-box-date.phtml',
                         'List' => (isset($this->_attribs['date']))?$this->_attribs['date']:array()
      )))
      	);
                
 
    // create new element
    /*  $this->addElement('Select', 'parentID[]', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_select-box-multi-level-edit.phtml',
      )))
      ));
       */
       
       $db = Zend_Db_Table::getDefaultAdapter();
       $mdlMenu = new Model_MenuItem();
       $mID = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        $select = 'select category_id from product_relationships where object_id = "'.$mID.'"';
       
       $result = $db->fetchAll($select);
  
       foreach($result as $key=>$val):
       		$parentArray[] = $val['category_id'];
       endforeach;

	   $select = $mdlMenu->select();
       // $select->where('parent = ?', 0);
        $select->where('menu_id = ?', 2);
        $select->where('lang = ?', $lang);
        $select->order('position asc');
        
        $menus = $mdlMenu->fetchAll($select);  
        
        $recursive = new Louis_System_Recursive($menus->toArray());
          if($lang == 'vi'){
	        $sub = 168;
        }else{
	        $sub = 181;
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
$multi->setRegisterInArrayValidator(false)->setRequired(true);
$multi->setValue($parentArray);

          
    // create new element
    $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Nội dung');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form

/*$elementDecorators = array( array('ViewHelper'), 

    array('HtmlTag',
        array(
        'tag' => 'span',
        'class' => 'lbl',
        'placement' => 'append',
        )
    ),

);
*/

/*
		$elementDecorators	= array(
   'Viewhelper',
  array('HtmlTag',
        array(
        'tag' => 'span',
        'class' => 'lbl',
        'placement' => 'append',
        )
    ),
    
   array(
       array('label' => 'HtmlTag'), 
       array('tag' => 'label'),
       array('placement' => 'prepend')
       ),
    array(
       array('dt' => 'HtmlTag'), 
       array('tag' => 'dt', 'id' => 'checkbox-label', 'placement' => 'prepend', 'text' => 'Hiển thị:'),
       
       )
   );


    $explicit = new Zend_Form_Element_Checkbox('explicit ','explicit',
                                                array('checkedValue'  => 1,
                             'uncheckedValue' => 0,)
                        );
      $explicit->setLabel('Hiển thị')
      			->setAttrib('class','ace ace-switch')
	  		    ->setValue(0);
	  		   */
	  		/*   $explicit->setDecorators(array(
   'Viewhelper',
   array(
       array('label' => 'HtmlTag'), 
       array('tag' => 'label')
   ),
   array(
       array('span' => 'HtmlTag'), 
       array('tag' => 'span', array('placement' => 'append')))
   )
);*/

//$explicit->setDecorators($elementDecorators);
	  		  
   
        
         $cover =  $this->addElement('Select', 'cover', array(
         'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_upload_cover.phtml',
                        'List' => ((isset($this->_attribs['cover']))?$this->_attribs['cover']:''),
                        'pid' => ((isset($this->_attribs['pid']))?$this->_attribs['pid']:'')
      )))
      
      ));
  
      
      
   // $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    $submit = $this->addElement('submit', 
        							'submit',
        					array('label' => 'Lưu',
        							'attribs' => array('class' => 'btn btn-info')
        											
        								));
        								
         								
         $this->addElements( array (
        
                            $name,
                            $date,
                            $multi,
                            $content,
                            $cover,
                          
                           $submit
                            )
                );
    
    
 
        
        $this->addDisplayGroup(array(
        
                    'name',
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
					'cover',
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