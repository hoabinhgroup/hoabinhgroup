$(document).ready(function(){

if(html5_storage_support()) toggle('lockalstorageexample');
			
			
	$('.stars').rating({php: 'admin/php/manager.php'});
	
	$('.starstime').rating({
		php: 'admin/php/manager.php', 
		showtext: false,
		textdelay: 2000,
		step:0,
		animate:true				
	});
	
	$('.skinstar').rating({
		php: 'admin/php/manager.php', 
		showtext: false,
		textdelay: 2000								
	});
	
	$('.offline').rating({
		id				: 	'offlinemode',
		minvotes		: 	1,				
		textdelay		: 	1500,
		textlocation	: 	'top',
		textminvotes	:	'do you like it?',
		textmain		:	'you gave %ms out of %maxs stars',
		texthover		: 	'<span style="color:#aa5555;">are you serios?</span>|only two stars?|better then nothing...|I like you to :)|<span style="color:#559955;">You are the best!</span>'
	});
	
	$('.lovestar').rating({				
		php				: 	'admin/php/manager.php', 
		skin			: 	'skins/hurt_3.png|skins/hurt_2.png|skins/hurt_1.png',
		minvotes		: 	0,	
		stars			:	1,
		step			:	1,
		textdelay		: 	1500,
		textthanks		: 	'thank you :) &nbsp;',
		textloading		: 	'&nbsp;',				
		textmain		:	'%v people love this feature &nbsp;',
		texthover		: 	'+1 &nbsp;'
	});
	
	
	$('.externallink').click(function() {
		window.open($(this).prop('href'));
        return false;
	});
	
});


function html5_storage_support() {			
	try { return typeof window.localStorage == 'object';}
	catch (e) {return false;}
}
function toggle(id) {		
	var e = document.getElementById(id);
	if(e.style.display == 'none'){ e.style.display = ''; return true;}
	else{ e.style.display = 'none'; return false;}
}