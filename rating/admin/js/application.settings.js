$(document).ready(function(){	

$('.dropdown-menu li a').click(function(){
		var txt=$(this).html();
		txt=txt.charAt(0);
		if(txt.charCodeAt(0)==8734){
			$('#dt').attr('value',txt);
			
			txt='&nbsp;';			
		}
		$('#dts').attr('value',txt);
		$('#dtmarker').html(txt);
		$('#ss2').hide();
	});	
});