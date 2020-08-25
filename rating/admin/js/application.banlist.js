	//SUMMARY PAGE

$(document).ready(function(){	

	//delete button
	$('body').append('<button id="confirm-delete-btn" type="button" class="btn btn-info fixed-btn"><i class="icon-ok-circle icon-white"></i> UNban SELECTED</button>');
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
				
		if($row.hasClass('info')){
			$row.removeClass('info');
			$icon.removeClass().addClass('icon-plus');	
			selectedNumber--;
		}
		else{
			$row.addClass('info');
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
			$rows.addClass('info');			
			$rows.find('.btn-check i').removeClass().addClass('icon-minus');	
			selectedNumber=$rows.length;
			
		}
		else{
			$icon.removeClass().addClass('icon-plus');						
			$rows.removeClass('info');
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
			if($tr.hasClass('info')){
				sql+=$tr.attr('id')+',';
			}
		});
		//alert('php/unbanlist.php?n='+sql);
		
		if( sql!='' ){
			sql+='&ref='+encodeURIComponent(document.URL);
			var $demobox=$('#demo-info');
			//alert($demobox+' | '+$demobox.length);
			
			if($demobox.length>0) $demobox.html('<div class="alert alert-info">Deleting database records is disabled in DEMO</div>');
			//send sql to php
			else window.open('php/unbanlist.php?n='+sql, '_self');
			//$.get('php/deleteitem.php?&n='+sql, function(data){
			//	alert("Data Loaded: " + data);
			//});
		}
		
	});


	

	function showByID(id,flag) {
		var e = document.getElementById(id);
		if(e.style.display == 'none' && flag) e.style.display = '';
		else if(!flag) e.style.display = 'none';
	}

	
});
