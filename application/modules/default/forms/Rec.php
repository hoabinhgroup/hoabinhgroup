<?php
class Form_Rec extends Zend_Form
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
        
		$name = $this->createElement('text', 'title');
       
        $name->setLabel('Tiêu đề: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',60);
        $name->setAttrib('onkeyup',"ChangeToSlugRec();");
        $name->setAttrib('autocomplete',"off");
        
        
        
         $slug = $this->createElement('text', 'ident');
       
        $slug->setLabel('Đường dẫn: ');
        $slug->setRequired(TRUE);
        $slug->setAttrib('size',60);
        
        
         $people_arr = array();
         $people_a = range(1, 100);
         foreach($people_a as $k=>$v):
         $people_arr[$v] = $v;
         endforeach;
      
       
           $people = new Zend_Form_Element_Select('people');
$people->setMultiOptions($people_arr);
$people->setLabel('Số người cần tuyển: ');
$people->setRegisterInArrayValidator(false)->setRequired(true);


		$salary = $this->createElement('text', 'salary');
       
        $salary->setLabel('Mức lương: ');
        $salary->setRequired(TRUE);
        $salary->setAttrib('size',30);
        $salary->setAttrib('placeholder','VD: Từ 7 -10 triệu');
        
        $exp = $this->createElement('text', 'exp');
       
        $exp->setLabel('Yêu cầu kinh nghiệm: ');
        $exp->setRequired(TRUE);
        $exp->setAttrib('size',30);
        $exp->setAttrib('placeholder','VD: 1 năm trở lên');
       // $salary->setAttrib('autocomplete',"off");

	   $deg = $this->createElement('text', 'deg');
       
        $deg->setLabel('Yêu cầu bằng cấp: ');
        $deg->setRequired(TRUE);
        $deg->setAttrib('size',30);
        $deg->setAttrib('placeholder','VD: Tốt nghiệp cao đẳng');
        
        $job = $this->createElement('text', 'job');
       
        $job->setLabel('Chức vụ: ');
        $job->setRequired(TRUE);
        $job->setAttrib('size',30);
        $job->setAttrib('placeholder','VD: Nhân viên, Trưởng phòng...');
        
         $work_style = $this->createElement('text', 'work_style');
       
        $work_style->setLabel('Hình thức làm việc: ');
        $work_style->setRequired(TRUE);
        $work_style->setAttrib('size',30);
        $work_style->setAttrib('placeholder','VD: Toàn thời gian');
        
        $sex = $this->createElement('text', 'sex');
       
        $sex->setLabel('Yêu cầu giới tính: ');
        $sex->setRequired(TRUE);
        $sex->setAttrib('size',30);
        $sex->setAttrib('placeholder','VD: Nam/ Nữ');
        
        $age = $this->createElement('text', 'age');
       
        $age->setLabel('Yêu cầu độ tuổi: ');
        $age->setRequired(TRUE);
        $age->setAttrib('size',30);
        $age->setAttrib('placeholder','VD: 22 - 35 tuổi');
       
         $company = array(
	         '0' => 'Chọn Công ty',
	         '1' => 'Hòa Bình Group',
	         '2' => 'Đà Nẵng Event',
	         '3' => 'Vietnamevent',
         );

      
       
           $companies = new Zend_Form_Element_Select('company');
$companies->setMultiOptions($company);
$companies->setRegisterInArrayValidator(false)->setRequired(true);



      $work = array(
	         '1' => 'Hà Nội',
	         '2' => 'Đà Nẵng',
	         '3' => 'TP. HCM',
         );

       
           $work_location = new Zend_Form_Element_Multiselect('location');
$work_location->setMultiOptions($work);
$work_location->setAttrib('style','width:200px');
$work_location->setLabel('Địa điểm làm việc: ');
$work_location->setRegisterInArrayValidator(false)->setRequired(true);

if(isset($this->_attribs['location'])){
$work_location->setValue($this->_attribs['location']);
        }
        
        $model_group = new Model_Group();
        $work_group = $model_group->get_details();

        
         $arr_group = array();
       foreach($work_group as $key=>$val):
	   $arr_group[$val['id']] = $val['title'];
       endforeach;
       
   $groups = new Zend_Form_Element_Select('work_group');
$groups->setMultiOptions($arr_group);
$groups->setLabel('Chọn danh mục: ');
$groups->setAttrib('style','width:200px');
$groups->setRegisterInArrayValidator(false)->setRequired(true);
       
       
        $contact = $this->createElement('text', 'contact');
       
        $contact->setLabel('Người liên hệ: ');
        $contact->setRequired(TRUE);
        $contact->setAttrib('size',30);
        $contact->setAttrib('placeholder','');
        
        $address = $this->createElement('text', 'address');
       
        $address->setLabel('Địa chỉ Cty: ');
        $address->setRequired(TRUE);
        $address->setAttrib('size',30);
        $address->setAttrib('placeholder','');
        
         $email = $this->createElement('text', 'email');
       
        $email->setLabel('Email Liên hệ: ');
        $email->setRequired(TRUE);
        $email->setAttrib('size',30);
        $email->setAttrib('placeholder','');
        
         $mobile = $this->createElement('text', 'mobile');
       
        $mobile->setLabel('Điện thoại liên hệ: ');
        $mobile->setRequired(TRUE);
        $mobile->setAttrib('size',30);
        $mobile->setAttrib('placeholder','');
        
        $date = $this->createElement('text', 'expired');
        $date->setRequired(TRUE);
        $date->setDecorators(array(array('ViewScript', array(
                        'viewScript' => '_select-box-expired.phtml',
                        'List' => (isset($this->_attribs['expired']))?$this->_attribs['expired']:''
      )))
      	);
      	
 
    
    // create new element
    $content = $this->createElement('textarea', 'description');
    // element options
    $content->setLabel('Nội dung');
    $content->setRequired(TRUE);
    $content->setAttrib('cols',50);
    $content->setAttrib('rows',12);
    // add the element to the form
    
 

      
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
                            $people,
                            $salary,
                            $exp,
                            $deg,
                            $job,
                            $work_style,
                            $sex,
                            $age,
                            $contact,
                            $email,
                            $address,
                            $mobile,
                            $companies,
                            $work_location,
                            $groups,
                            $content,
                          //  $status,
                           $submit
                            )
                );
    
    
 
        
        $this->addDisplayGroup(array(
        
                    'title',
                    'ident',
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
        			'people',	
        			'salary',	
        			'exp',	
        			'deg',	
        			'job',	
        			'work_style',	
        			'sex',	
        			'age',	
        			'contact',	
        			'address',	
        			'email',	
        			'mobile',	
        			'company',	
        			'location',	
        			'work_group',	
                    'expired',       

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