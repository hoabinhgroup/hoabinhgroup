	//SUMMARY PAGE

$(document).ready(function(){	

	//delete button
	$('body').append('<button id="confirm-delete-btn" type="button" class="btn btn-danger fixed-btn"><i class="icon-remove-sign icon-white"></i> REMOVE SELECTED</button>');
	showByID('confirm-delete-btn',false);

	$("table th a").tooltip();		
	$("table .progress").tooltip();
	
	$("table tbody .btn-check").tooltip({title:'select'});
	$("table tbody tr td > a").tooltip();
	$(".icon-ban-circle").tooltip({title:'banned'});
	$("h1 a").tooltip({placement:'right'});


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
		return false;
	});
	
	//// select all button
	$('table thead .btn-check').click(function(){
		var $rows= $(this).closest('table').find('tbody tr');	
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
			var id=$('#thisitemid').attr('value');
			var pid=$('#parentitemid').attr('value');
			if(pid !==undefined) sql+='&pid='+pid;			
			sql+='&id='+id+'&ref='+encodeURIComponent(document.URL);
			var $demobox=$('#demo-info');
			//alert($demobox+' | '+$demobox.length);
			if($demobox.length>0) $demobox.html('<div class="alert alert-info">Deleting database records is disabled in DEMO</div>');
			//send sql to php
			else window.open('php/deletevote.php?n='+sql, '_self');
			//$.get('php/deleteitem.php?&n='+sql, function(data){
			//	alert("Data Loaded: " + data);
			//});
		}
		
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
					
					case 2:
					var inp=div.children('input');
					
					var min=(inp[0].value);//date2number
					var max=(inp[1].value);//date2number
					
					//if(isNaN(min)) min='';
					//if(isNaN(max)) max='';
					//else max+=60*60*24;
					
					if(min=='' && max=='') filter+='|';
					else filter+=min+','+max+'|';
					break;
					
					case 1:
					var inp=div.children('input');
					var min=parseFloat(inp[0].value);
					var max=parseFloat(inp[1].value);
					if(isNaN(min)) min='';
					if(isNaN(max)) max='';
					
					if(min=='' && max=='') filter+='|';
					else filter+=min+','+max+'|';
					break;
					
				}
				
				
			}else filter+='|';
			
		});
		
		if(filter=='|||') filter='';
		else filter=encodeURIComponent(filter);
		
		var id=$('#thisitemid').attr('value');		
		window.open('item.php?id='+id+'&f='+filter, '_self');
		
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

	function showByID(id,flag) {
		var e = document.getElementById(id);
		if(e.style.display == 'none' && flag) e.style.display = '';
		else if(!flag) e.style.display = 'none';
	}

	
	function date2number(value){		
		regs=value.split(/-|\//);
		if(regs.length==3) {
			regs[0]=regs[0].replace(/\D/g, '');				
			regs[1]=regs[1].replace(/\D/g, '');				
			regs[2]=regs[2].replace(/\D/g, '');				
			if(regs[0] < 1 || regs[0] > 31) return '';
			else if(regs[1] < 1 || regs[1] > 12)return '';
			else if(isNaN(regs[2]) || parseInt(regs[2])<1971) regs[2]=1970;
		} else return '';
		
		var d=new Date(regs[2]*1.0, (regs[1])-1, regs[0]*1.0);
		var dt=d.getTimezoneOffset()*60;
		return Math.max(1,Math.round((d.getTime())/1000)+dt); //in sec
	}
	
	
	
});
