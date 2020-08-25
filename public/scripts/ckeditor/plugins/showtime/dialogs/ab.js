CKEDITOR.dialog.add("showtime",function(e){

 var date=new Date();
 var months = new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

 var h=date.getHours();
 var nh=h;
 var m=date.getMinutes();
 var s=date.getSeconds();
 var a;
 if(h>12){a="PM"; nh=h-12;}
 if(h<=12){a="AM"; nh=h;}
 
 //Create different time formats
 var t1 = months[parseInt(date.getMonth())]+' '+date.getDate()+" @ " + nh +  ":" + m + " " + a;
 var t2 = date.getFullYear()+'-'+parseInt(date.getMonth()+1) +'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
 var t3 = date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();

 return {
  title:'Show Time',
  resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
  minWidth:300,
  minHeight:100,
  onShow:function(){ 
  },
  onLoad:function(){ 
    dialog = this; 
    this.setupContent();
  },
  onOk:function(){
  },
  contents:[
  {  id:"info",
    name:'info',
    label:'Tab',
    elements:[

     {
      id : 'format',
      type : 'select',
      label : 'Format',
      accessKey : 'T',
      items :
      [
       [ t1 ],
       [ t2 ],
       [ t3 ]
      ]
     },
     {  
      type:'html',
      html:'<span style="">'+'Select the date format'+'</span>'
     }
    ]
  }
  ],
  buttons:[{
   type:'button',
   id:'okBtn',
   label: 'Set',
   onClick: function(){
      addCode(); //function for adding time to the source
   }
  }, CKEDITOR.dialog.cancelButton],
};

 //function for adding time to the source
 function addCode(){

  //get the value of 'format' field in the 'info' tab of the dialog box
  var t = dialog.getValueOf('info', 'format');
  if(t.length == 0){
   alert('Please select date format.')
   return false;
  }

  var myEditor = CKEDITOR.instances.editor1;

  //insert the time into the HTML source code
  myEditor.insertHtml(t);

  return false;

 };

});