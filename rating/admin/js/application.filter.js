	//SUMMARY PAGE

$(document).ready(function(){	

	//index() bez nichego mozet sdelat' problemmj
	
	//filters
	$('#mainfilter li a').click(function(){
		var txt=$(this).html();
		mainfilterlabel.innerHTML=txt;
		
		var index = $(this).index('li')+1;
		alert(index);
	});
	
	
	
	$('.close').click(function(){
		var div=$(this).closest('div');
		var index = div.index()+1;
	
		$(this).closest('div').hide();
		$('#filters1').children('button:nth-child('+index+')').button('toggle');

	});
	
		
	$('#filterdivs1').children('div').hide();
	$('#filters1').children('button').click(function(){
		var index = $("button").index(this)+1;
		
		var div=$('#filterdivs1').children('div:nth-child('+index+')')[0];
		toggle(div);
	});
	//filters end here
	
	
	

	var selectedNumber=0;

	 //// select button
	$('table tbody .btn-check').click(function(){
		var $row= $(this).closest('tr');	
		var $icon=$(this).children('i');
				
		if($row.hasClass('error')){
			$row.removeClass('error');
			$icon.removeClass().addClass('icon-plus');	
			selectedNumber--;
		}
		else{
			$row.addClass('error');
			$icon.removeClass().addClass('icon-minus');	
			selectedNumber++;
		}
		
		if(selectedNumber>0) showByID('confirm-delete-btn',true);
		else showByID('confirm-delete-btn',false);
		
		if(selectedNumber<=0){
			$('table thead .btn-check i').removeClass().addClass('icon-plus');
			
		}
		
		return false;
	});
	

	//// select all button
	$('table thead .btn-check').click(function(){
		var $rows= $(this).closest('table').find('tbody tr').not('#editform');
		var $icon=$(this).children('i');
				
		if($icon.hasClass('icon-plus')){
			$icon.removeClass().addClass('icon-minus');			
			$rows.addClass('error');			
			$rows.find('.btn-check i').removeClass().addClass('icon-minus');	
			selectedNumber=$rows.length;
			
		}
		else{
			$icon.removeClass().addClass('icon-plus');						
			$rows.removeClass('error');
			$rows.find('.btn-check i').removeClass().addClass('icon-plus');			
			selectedNumber=0;
		}
		
		if(selectedNumber>0) showByID('confirm-delete-btn',true);
		else showByID('confirm-delete-btn',false);
		return false;
	});
	
	
	//delete selected
	$('#confirm-delete-btn').click(function(){		
		var sql='';
		
		$rows=$('table tbody tr').each(function(){
			var $tr= $(this);
			if($tr.hasClass('error')){
				sql+=$tr.attr('id')+',';
			}
		});
		
		
		if( sql!='' ){
			sql+='&ref='+encodeURIComponent(document.URL);
			var $demobox=$('#demo-info');
			//alert($demobox+' | '+$demobox.length);
			if($demobox.length>0) $demobox.html('<div class="alert alert-info">Deleting database records is disabled in DEMO</div>');
			//send sql to php
			else window.open('php/deleteitem.php?n='+sql, '_self');
			//$.get('php/deleteitem.php?&n='+sql, function(data){
			//	alert("Data Loaded: " + data);
			//});
		}
		
	});

	
	//// edit button
	$('table .btn-edit').click(function(){
		$(this).tooltip('hide');
		$('#editform-date').datepicker('hide');
		
		var $row= $(this).closest('tr');			
		var tds=$row.children('td');
		
		var id=document.getElementById("editform-id");
		var old_id=id.value;
		var new_id=$row.attr('id');
		
		//show prev. edited row
		if(old_id!='' && typeof old_id!='undefined' && old_id!=new_id){
			showByID(old_id, true); 
		}
		
		
		highlight('editform-title',false);
		highlight('editform-dt',false);
		highlight('editform-date',false);
		
		//show edit form
		$('#editform').insertBefore($row.hide()).show();
		
		//fill in edit form
		id.value=new_id;
		
		var dt=(tds[5].innerHTML);
		var dtarray=['','&nbsp;'];
		if(dt){
			dtarray=dt.split(' ');
			
			dtarray[0]=parseFloat(dtarray[0]);
		
			if(isNaN(dtarray[0])) dtarray=['','&nbsp;'];
			else{
				dtarray[1]=dtarray[1].charAt(0);	
			}
		}
		
		$('#editform-mean, #editform-popularity').tooltip('destroy');
		document.getElementById("editform-title").value=($(tds[0]).find('input:first').attr('value'));			
		document.getElementById("editform-mean").innerHTML=(tds[1].innerHTML);
		document.getElementById("editform-n").innerHTML=(tds[2].innerHTML);
		document.getElementById("editform-popularity").innerHTML=(tds[3].innerHTML);			
		document.getElementById("editform-date").value=(tds[4].innerHTML);
		document.getElementById("editform-dt").value=dtarray[0];
		document.getElementById("editform-dtmarker").innerHTML=dtarray[1];
		$("#editform .progress").tooltip();
		
		return false;
	});


	//confirm edit
	$('#editform-submit .btn-ok-edit').click(function(){			
		var id=document.getElementById("editform-id").value;
		if(id!='' && typeof id!='undefined'){				
			var row=document.getElementById(id);
			var tds=$(row).children('td');
			//update data
			
			var title=document.getElementById("editform-title").value;
			title=title.replace(/^\s+|\s+$/g, "");
			if(title=='') title='empty title';
			var dt0=document.getElementById("editform-dt").value;
			var dt1=document.getElementById("editform-dtmarker").innerHTML;
			var date=document.getElementById("editform-date").value;
			
			
			var sql='n='+id;
			
			
			if(title!=$(tds[0]).find("input:first").attr("value")){ 
				sql+='&t='+encodeURIComponent(title);
			}
			
			
			if(date!=tds[4].innerHTML) {					
				var d=validateDate(document.getElementById("editform-date"));
				
				if(d==0) return;
				else if(d==-1){
					date=-1;
					sql+='&d=-1';
				}else{
					date=d;
					sql+='&d='+date2number(date); //to sec
				}
			}
			
			var seconds;
			dt0=parseFloat(dt0);
			dt1=dt1.charAt(0);
			if(dt1==' ' || isNaN(dt0)){ seconds=-1; dt0='&#8734;'; dt1='';}
			else{
				if(dt1=='d'){ seconds=dt0*3600*24; dt1=dt0==1?'day':'days';}
				else if(dt1=='h'){ seconds=dt0*3600;dt1=dt0==1?'hour':'hours';}
				else if(dt1=='m'){ seconds=dt0*60;dt1='min.';}
				else if(dt1=='s'){ seconds=dt0;dt1='sec.';}
				else seconds=-1;
			}
			
			var dt=dt0+' '+dt1;
			if(dt!=tds[5].innerHTML) {
				sql+='&dt='+seconds; //in sec
			}
			
			
			
			
			
			if(sql!='') {				
				var $demobox=$('#demo-info');
				if($demobox.length>0){
					$demobox.html('<div class="alert alert-info">Editing database records is disabled in DEMO. All changes will be rolled back after page refreshing</div>');
				}else{
					//send data to php				
					$.get('php/edititem.php?'+sql, function(data){
						$('#message-box').html((data=='1')?'':'<div class="alert alert-error">ERROR: '+data+'</div>');
					});
				}
				
				//update fields
				var shorttitle=(title.length>30)?title.substring(0, 28)+'..':title;
				$(tds[0]).find('span > input:first').attr('value',title);
				$(tds[0]).find('span > a:first').html(shorttitle); 				
				tds[4].innerHTML=(date==-1)?'':(date);				
				tds[5].innerHTML=dt;
			}
				
			
			showByID(id, true);
		}
		$('#editform-date').datepicker('hide');
		$(this).tooltip('hide');
		//showByID("editform", false);	//hide form
		$('table tbody').append($('#editform').hide());
		return false;
	});

	//cancel edit
	$('#editform-submit .btn-cancel-edit').click(function(){			
		var id=document.getElementById("editform-id").value;
		if(id!='' && typeof id!='undefined'){
			showByID(id, true); 
		}
		$('#editform-date').datepicker('hide');
		$(this).tooltip('hide');
		//showByID("editform", false);
		
		$('table tbody').append($('#editform').hide());
		
		return false;		
	});

	var btns=$('#editform .input-prepend .btn');

	$(btns[0]).click(function(){
		var d=new Date();
		document.getElementById("editform-date").value=d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
	});

	$(btns[1]).click(function(){
		document.getElementById("editform-date").value='';
	});

	



	$('#editform-date').datepicker({format: 'dd/mm/yyyy'});
	
	
	$('#editformdropdown li a').click(function(){
		var txt=$(this).html();
		txt=txt.charAt(0);
		if(txt.charCodeAt(0)==8734){
			$('#editform-dt').attr('value',txt);
			txt='&nbsp;';			
		}
		
		$('#editform-dtmarker').html(txt);
	});
	
 
	
	
 
 
	function date2number(d){
		var regs=d.split(/-|\//);	//day, mon, year
		return Math.max(1,Math.round(((new Date(regs[2], parseInt(regs[1])-1, regs[0])).getTime())/1000)); //in sec
	}

	function highlight(id,flag){
		var txt=document.getElementById(id);
		if(flag) txt.style.backgroundColor = '#fba';
		else txt.style.backgroundColor='';
	}

	function validateDate(field){
		var errorMsg = "";

		if(field.value != '') {
		
		regs=field.value.split(/-|\//);
		  if(regs.length==3) {
			regs[0]=regs[0].replace(/\D/g, '');				
			regs[1]=regs[1].replace(/\D/g, '');				
			regs[2]=regs[2].replace(/\D/g, '');				
			if(regs[0] < 1 || regs[0] > 31) {
				errorMsg = "Invalid value for day: " + regs[0];
			} else if(regs[1] < 1 || regs[1] > 12) {
				errorMsg = "Invalid value for month: " + regs[1];
			} else if(isNaN(regs[2]) || parseInt(regs[2])<1971) 
				regs[2]=1970;
			} else {
				errorMsg = "Invalid date format: " + field.value;
			}
		}else{
			
			return -1;
		}

		if(errorMsg != "") {
		  alert(errorMsg);
		  field.focus();
		  field.style.backgroundColor = '#fba';
		  return 0;
		}
		
		
		field.style.backgroundColor = '#bfa';
		//if(regs[1]<10) regs[1]='0'+regs[1];
		//if(regs[2]<10) regs[2]='0'+regs[2];
		return regs[0]+'/'+regs[1]+'/'+regs[2];
	  }





	function showByID(id,flag) {
		var e = document.getElementById(id);
		if(e.style.display == 'none' && flag) e.style.display = '';
		else if(!flag) e.style.display = 'none';
	}
	
	function toggle(e) {		
		if(e.style.display == 'none') e.style.display = '';
		else e.style.display = 'none';
	}

	
});
