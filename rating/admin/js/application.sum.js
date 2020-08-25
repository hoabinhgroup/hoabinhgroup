	//SUMMARY PAGE

$(document).ready(function(){	

	//showByID('editform',false);

	//delete button
	$('body').append('<button id="confirm-delete-btn" type="button" class="btn btn-danger fixed-btn"><i class="icon-remove-sign icon-white"></i> REMOVE SELECTED</button>');
	showByID('confirm-delete-btn',false);
	
	$("table th a").tooltip();		
	$("table .progress").tooltip();
	
	$("#editform button").tooltip();
	
	//$("table tbody .btn-details").tooltip({title:'details'});
	$("table tbody .btn-edit").tooltip({title:'edit'});
	$("table tbody .btn-check").tooltip({title:'select'});
	$("table tbody .btn-link").tooltip({title:'located here', placement:'right'});
	$("table tbody tr td span > a").tooltip();
	
	$('a').has('.btn-link').click(function() {
		$(this).attr('target', '_blank');
		window.open($(this).prop('href'));
        return false;
	});
	
	
	
	
	//load subitems
	$(".subitemref").click(function() {
		var $this=$(this);
		
		var id=$this.data("id");		
		//var sortoptions=$('#sortoptions').value;
		var params=$this.attr('href')+'&n='+id+'&rnd='+Math.random();
		var $insertAfter = $this.closest('tr');//
		var $icon=$this.children('i');
		
		
		if(!$icon.hasClass('icon-resize-full')){
			$icon.removeClass('icon-resize-small').addClass('icon-resize-full');	
			$insertAfter.parent().children('.newloaded'+id).hide();
		}else{				
			$icon.removeClass('icon-resize-full').addClass('icon-resize-small');
			if($insertAfter.hasClass('wasloaded')){			
				//simply show it
				$insertAfter.parent().children('.newloaded'+id).show();				
			}else{	
		
				$loading='<tr class="warning" id="loadingrow"><td colspan="8"><i class="icon-download"></i> loading...</td></tr>';
				$insertAfter.after($loading);
		
				//send data to php
				$.get('php/getsubitems.php?'+params, function(data){
					var obj = JSON && JSON.parse(data) || $.parseJSON(data);			
					
					if(obj.error) $('#message-box').html('<div class="alert alert-error">ERROR: '+obj.error+'</div>');	
					else{
					
						var $toappend='';
						
						var selected=false;
						if($insertAfter.hasClass('error')) selected=true;
						
						////////////////// output subitems ///////////
						for (i = 0; i < obj.length; ++i) {
							
							if(selected) selectedNumber++;
							
							$toappend+='<tr id="'+obj[i].n+'" data-parentid="'+id+'" class="'+(selected?'error':'warning')+' newloaded'+id+' mesubitem"> <td>';
							$toappend+='&nbsp;&nbsp;&nbsp;&nbsp;<a href="'+obj[i].link+'" target="_blank"><i class="icon-share btn-link"></i></a> ';
							if(!obj[i].title) obj[i].title='no title';
							$toappend+='<span><input type="hidden" value="'+obj[i].title+'" />';

							obj[i].title=((obj[i].title).length>30)?(obj[i].title).substr(0, 28):obj[i].title;

							$toappend+='<a href="item.php?id='+obj[i].n+'&amp;" title="show item details">'+obj[i].title+'</a></span></td><td>';
							$toappend+='<div class="progress progress-warning progress-striped" title="'+Math.round(obj[i].mean)+'% ('+(Math.round(obj[i].mean/2)/10)+' / 5 stars)">';
							$toappend+='<div class="bar" style="width: '+obj[i].mean+'%;"></div></div></td>';
							$toappend+='<td>'+obj[i].votes+'</td><td></td><td></td><td></td>';
							$toappend+='<td>'+((obj[i].time)?obj[i].time:'')+'</td>';
							$toappend+='<td><div class="pull-right"><a class="btn-edit" href="#" ><i class="icon-pencil"></i></a><a class="btn-check" href="#" ><i class="icon-'+(selected?'minus':'plus')+'"></i></a></div></td></tr>';
							
						}
						///////////////////////////////////////////////
						
						$('table tbody tr#loadingrow').remove();
						//add some actions
						$insertAfter.after($toappend);
						$insertAfter.addClass('wasloaded');	
						
						$('table tbody tr.newloaded'+id+' td span > a').tooltip();
						$('table tbody tr.newloaded'+id+' .progress').tooltip();	
						$('table tbody tr.newloaded'+id+' .btn-edit').tooltip({title:'edit'});
						$('table tbody tr.newloaded'+id+' .btn-check').tooltip({title:'select'});
						$('table tbody tr.newloaded'+id+' .btn-link').click(function() {
							$(this).attr('target', '_blank');
							window.open($(this).prop('href'));
							return false;
						}).tooltip({title:'located here', placement:'right'});
						
						
						$('table tbody tr.newloaded'+id+' .btn-check').click(function(event,prolongate){
							return selectRow($(this), true,prolongate);
						});
						
						
						
						//// edit button
						$('table tbody tr.newloaded'+id+' .btn-edit').click(function(){ return editRow($(this),true);});
						
						
					}		
				});
			}
		}
		
		$this.tooltip('hide');
        return false;
	});
	
	
	

	/*
	$('#ratingbar').rating({
			//DEFAULT SETTINGS
			phpPath				: '',				
			stars				: 1, 				
			onClick             : onClick,				
			textHover:'%pointed%',				
			textLoading:'',
			showThankDelay:0,
			textMain:'%mean%'
		}); 

	$('#popularitybar').rating({phpPath: '',stars: 1,showText:false});
	$('#popularitybar').tooltip({title:getPopularity});

	function getRating(n){
		if(typeof n == 'undefined') var n=$('#ratingbar').rating('value');
		return Math.round(n/2)/10+' / 5 stars ('+Math.round(n*10)/10+'%)';
	}
	function getPopularity(){return Math.round($('#popularitybar').rating('value')*10)/10+'%';}
	  
	function onClick(n){
		$('#ratingbar').rating('value',n); //set mean value to user defined
		$('#ratingbar').rating('option','enable', true ); //enable 		

		//recalculate popularity
		var p=Math.random()*100;
		$('#popularitybar').rating('value',p);
	}

	*/


	var selectedNumber=0;

	 //// select button
	$('table tbody .btn-check').click(function(event, prolongate){return selectRow($(this), false, prolongate);});
	
	
	function selectRow($this, issubitem, prolongate){
		var $row= $this.closest('tr');	
		var $icon=$this.children('i');
		var id=$row.attr("id");
		
		//exit edit mode
		var id2=document.getElementById("editform-id").value;
		if(id2!='' && typeof id2!='undefined' && id==id2){
			showByID(id2, true); 
			$('#editform-date').datepicker('hide');		
			$('#editform').hide().appendTo($('table tbody'));		
		}
		
		//console.log("Select row "+id+" "+(issubitem?"sub":"main")+" prolongate?"+prolongate);
		
		if($row.hasClass('error')){
			if(issubitem){ 
				$row.removeClass('error').addClass('warning');
				var parentid=$row.data("parentid");				
				if(prolongate!==true && $('table tbody tr.newloaded'+parentid+'.error').length<1){
					//console.log('trigger parent '+$('table tbody tr#'+parentid+'.error'));
					var $totrigger=$('table tbody tr#'+parentid+'.error .btn-check');
				}
			}else{
				$row.removeClass('error');
				$('table tbody tr.newloaded'+id+'.error .btn-check').trigger('click',true);
			}
			$icon.removeClass().addClass('icon-plus');	
			selectedNumber--;			
			
		}
		else{
			if(issubitem){ 
				$row.removeClass('warning').addClass('error');
				var parentid=$row.data("parentid");	
				if(prolongate!==true && $('table tbody tr.newloaded'+parentid+'.warning').length<1){
					var $totrigger=$('table tbody tr#'+parentid+' .btn-check').not('.error');
				}
			}else{
				$row.addClass('error');
				$('table tbody tr.newloaded'+id+'.warning .btn-check').trigger('click',true);
			}
			$icon.removeClass().addClass('icon-minus');	
			selectedNumber++;			
			
		}
		
		if(selectedNumber>0) showByID('confirm-delete-btn',true);
		else showByID('confirm-delete-btn',false);
		
		if(selectedNumber<=0){
			$('table thead .btn-check i').removeClass().addClass('icon-plus');			
		}
		
		if($totrigger) $totrigger.trigger('click',true);
	
		return false;
	};
	

	
	
	//// select all button
	$('table thead .btn-check').click(function(){
	
		//exit edit mode
		var id=document.getElementById("editform-id").value;
		if(id!='' && typeof id!='undefined'){
			showByID(id, true); 
			$('#editform-date').datepicker('hide');		
			$('#editform').hide().appendTo($('table tbody'));		
		}
		
		
	
		//select all
		var $rows= $(this).closest('table').find('tbody tr').not('#editform');
		var $icon=$(this).children('i');
				
		if($icon.hasClass('icon-plus')){
			
			$icon.removeClass().addClass('icon-minus');			
			
			$('table tbody tr.warning').removeClass('warning');
			
			$rows.addClass('error');			
			$rows.find('.btn-check i').removeClass().addClass('icon-minus');	
			selectedNumber=$rows.length;
			
		}
		else{
			$icon.removeClass().addClass('icon-plus');						
			$rows.removeClass('error');
			
			$('table tbody tr.mesubitem').addClass('warning');
			
			$rows.find('.btn-check i').removeClass().addClass('icon-plus');			
			selectedNumber=0;
		}
		
		if(selectedNumber>0) showByID('confirm-delete-btn',true);
		else showByID('confirm-delete-btn',false);
		return false;
	});
	
	
	//delete selected
	$('#confirm-delete-btn').click(function(){		
		var mainid=Array();
		var subid=Array();
		
		//exit edit mode
		var id=document.getElementById("editform-id").value;
		if(id!='' && typeof id!='undefined'){
			showByID(id, true); 
			$('#editform-date').datepicker('hide');		
			$('#editform').hide().appendTo($('table tbody'));		
		}
		
		$rows=$('table tbody tr.error').each(function(){
			var $tr= $(this);
			if($tr.hasClass('mesubitem')) subid.push($tr.attr('id'));			
			else mainid.push($tr.attr('id'));
		});
		
		var sql=mainid.join()+'|'+subid.join();
		
		if( sql!='|' ){
			sql+='&ref='+encodeURIComponent(document.URL);
			var $demobox=$('#demo-info');
			//alert($demobox+' | '+$demobox.length);
			if($demobox.length>0) $demobox.html('<div class="alert alert-info">Deleting database records is disabled in DEMO</div>');
			//send sql to php
			else{
				window.open('php/deleteitem.php?n='+sql, '_self');
				/*
				$.get('php/deleteitem.php?&n='+sql, function(data){					
					if(data-1==0){ 
						//deleted succesfully						
						selectedNumber=0; 
						showByID('confirm-delete-btn',false);
						$('table tbody tr.error').remove();						
					}else{
						if($demobox.length>0) $demobox.html('<div class="alert alert-info">'+data+'</div>');
						else alert(data);
					}							
				});
				*/
			}
		}
		return false;
	});

	
	//// edit button
	$('table .btn-edit').click(function(){ return editRow($(this),false);});
	
	
	function editRow($this, issubitem){
		$this.tooltip('hide');
		$('#editform-date').datepicker('hide');
		
		var $row= $this.closest('tr');			
		var tds=$row.children('td');
		
		var id=document.getElementById("editform-id");
		var old_id=id.value;
		var new_id=$row.attr('id');
		
		//show prev. edited row
		if(old_id!='' && typeof old_id!='undefined' && old_id!=new_id){
			showByID(old_id, true); 
		}
		
		
		highlight('editform-title',false);
		if(!issubitem){
			highlight('editform-dt',false);
			highlight('editform-date',false);
		}
		
		//show edit form
		$('#editform').insertBefore($row.hide()).show();
		
		//fill in edit form
		id.value=new_id;
		
		if(!issubitem){
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
			
			var datestr=tds[4].innerHTML;
		}
		
		
		$('#editform-mean, #editform-popularity').tooltip('destroy');
		document.getElementById("editform-title").value=($(tds[0]).find('input:first').attr('value'));			
		document.getElementById("editform-mean").innerHTML=(tds[1].innerHTML);
		document.getElementById("editform-n").innerHTML=(tds[2].innerHTML);
		if(!issubitem){
						
			$("#editform-date").show();
			$("#editform-dt").show();
			$("#editform .input-append").show();
			
			document.getElementById("editform-popularity").innerHTML=(tds[3].innerHTML);			
			document.getElementById("editform-date").value=(datestr=='just closed'?'now':datestr);
			document.getElementById("editform-dt").value=dtarray[0];
			document.getElementById("editform-dtmarker").innerHTML=dtarray[1];
		}else{
				
			$("#editform-date").hide();
			$("#editform-dt").hide();
			$("#editform .input-append").hide();
			
			document.getElementById("editform-popularity").innerHTML='';			
			document.getElementById("editform-dtmarker").innerHTML='';
			
		}
		$("#editform .progress").tooltip();
		
		return false;
	};


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
						
			var ismainitem=(dt1=='')?false:true;
			
			var sql='n='+id;
			
			
			if(title!=$(tds[0]).find("input:first").attr("value")){ 
				sql+='&t='+encodeURIComponent(title);
			}
			
			
			if(ismainitem){
				var isNow=date.replace(/^\s+|\s+$/g, "");
				
				if(isNow=='now'){ sql+='&d=now'; date='just closed';}
				else if(date!=tds[4].innerHTML) {					
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
				shorttitle=escapeHtml(shorttitle);
				$(tds[0]).find('span > input:first').attr('value',title);
				$(tds[0]).find('span > a:first').html(shorttitle); 				
				
				if(ismainitem){
					tds[4].innerHTML=(date==-1)?'':(date);				
					tds[5].innerHTML=dt;
				}
			}
				
			
			showByID(id, true);
		}
		$('#editform-date').datepicker('hide');
		$(this).tooltip('hide');
		//showByID("editform", false);	//hide form
		$('#editform').hide().appendTo($('table tbody'));
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
		
		$('#editform').hide().appendTo($('table tbody'));
		
		
		return false;		
	});

	var btns=$('#editform .input-prepend .btn');

	$(btns[0]).click(function(){
		//var d=new Date();
		document.getElementById("editform-date").value='now';//d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
	});

	$(btns[1]).click(function(){
		document.getElementById("editform-date").value='';
	});

	



	$('#editform-date').datepicker({format: 'dd/mm/yyyy'}).on('changeDate', function(ev){$('#editform-date').datepicker('hide');});
	
	
	$('.dropdown-menu li a').click(function(){
		var txt=$(this).html();
		txt=txt.charAt(0);
		if(txt.charCodeAt(0)==8734){
			$('#editform-dt').attr('value',txt);
			txt='&nbsp;';			
		}
		
		$('#editform-dtmarker').html(txt);
	});
	
 
	
	
	//filters
	
	//$('#filterdivs1').children('div').hide();
	
	//$('#filterdivs1').hide();
	//$('#filters1').hide();
	//$('#filterapply').hide();
	initFilters();
	
	
	function initFilters(){
		var btns=$('#filters1').children('button');
		
		btns.each(function(index){			
			var btn=$(this);			
			if (btn.hasClass('active')) {btn.addClass(btn.attr('data-class-toggle'));}
			else{
				$('#filterdivs1').children('div:nth-child('+(index+1)+')').hide();				
			}
		});
		
	}
	
	$('#filterbtn').click(function(){		
		$('#datebegin').datepicker('hide');
		$('#dateend').datepicker('hide');
		var flag=toggle(document.getElementById('filters1'));
		showByID('filterapply', flag);
		showByID('filterdivs1', flag);
	}).tooltip();
	
	
	$('.close').click(function(){
		var div=$(this).closest('div');
		var index = div.index()+1;	
		$(this).closest('div').hide();
		
		var btn=$('#filters1').children('button:nth-child('+index+')');		
		btn.button('toggle');
		colorIt(btn);
	});
	
		
	
	$('#filters1').children('button').click(function(){
		var index = $("button").index(this);		
		var div=$('#filterdivs1').children('div:nth-child('+index+')')[0];
		colorIt($(this));
		toggle(div);		
	});
	
	
	function colorIt(e){
		//alert(e[0].innerHTML);
		if (e.hasClass('active')) {
			e.removeClass(e.attr('data-class-toggle'));
		} else {
			e.addClass(e.attr('data-class-toggle'));
		}
	}
	
	$('#filterapply').click(function(){
		var filter='';		
		$('#filterdivs1').children('div').each(function(index){
			var div=$(this);
			if(!isHidden(this)){
				switch (index){
					case 0:
					var str=div.children('input')[0].value;
					str=str.replace("|","");
					str=str.replace(/^\s+|\s+$/g, "");					
					filter+=str+"|";
					break;
					
					case 1:
					case 2:
					var inp=div.children('input');
					var min=parseFloat(inp[0].value);
					var max=parseFloat(inp[1].value);
					if(isNaN(min)) min='';
					if(isNaN(max)) max='';
					
					if(min=='' && max=='') filter+='|';
					else filter+=min+','+max+'|';
					break;
					
					case 3:
					case 4:
					var inp=div.find('button')[1];
					var ispressed=$(inp).hasClass('active');
					filter+=ispressed?'1':'2';	//first or second is pressed			
					filter+='|';					
					break;
					
					case 5:
					var inp=div.children('input');
					
					var min=(inp[0].value);//date2number
					var max=(inp[1].value);//date2number
					
					//if(isNaN(min)) min='';
					//if(isNaN(max)) max='';
					//else max+=60*60*24;
					
					if(min=='' && max=='') filter+='|';
					else filter+=min+','+max+'|';
					break;
					
				}
				
				
			}else filter+='|';
			
		});
		
		if(filter=='||||||') filter='';
		else filter=encodeURIComponent(filter);
		
		window.open('index.php?f='+filter, '_self');
		
	});
	
	$('#datebegin').datepicker({format: 'dd/mm/yyyy'}).on('changeDate', function(ev){$('#datebegin').datepicker('hide');});
	$('#dateend').datepicker({format: 'dd/mm/yyyy'}).on('changeDate', function(ev){$('#dateend').datepicker('hide');});
	$("#filteroffnow").tooltip({placement:'right'});
	
	//filters end here
	
	
	function toggle(e) {		
		if(e.style.display == 'none'){ e.style.display = ''; return true;}
		else{ e.style.display = 'none'; return false;}
	}
	
	function isHidden(e) {		
		if(e.style.display == 'none') return true;
		else return false;
	}
 
 
	function date2number(d){
		var regs=d.split(/-|\//);	//day, mon, year
		var d=new Date(regs[2], (regs[1])-1, regs[0]);
		var dt=d.getTimezoneOffset()*60;
		return Math.max(1,Math.round((d.getTime())/1000)+dt); //in sec
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


	 var entityMap = {
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': '&quot;',
		"'": '&#39;',
		"/": '&#x2F;'
	};

	function escapeHtml(string) {
		return String(string).replace(/[&<>"'\/]/g, function (s) {
		  return entityMap[s];
		});
	}


	function showByID(id,flag) {
		var e = document.getElementById(id);
		if(e.style.display == 'none' && flag) e.style.display = '';
		else if(!flag) e.style.display = 'none';
	}

	
});
