/**
 * @name jquery.5stars.js
 * @author Sandi http://codecanyon.net/user/Sandi?ref=sandi
 * @version v2.0
 * @date 2015
 * @category jQuery plugin
**/

;(function($) {
	
	
	function fiveStars($thisObj, params) {
		
		var effect=this;
				
		var settings = jQuery.extend({},{
			
			//DEFAULT SETTINGS
			
			php					: '',					//path to manager.php file. If not provided - display mode will be activated (no stats load/update)
			id					: '',					//item ID, must be unique for each item
			property			: '',					//property within item. For parent-child ratings
			title				: '',					//item short description 
			displaymode			: false,				//if true - no database required, values taken from html
			rewrite				: false,				//if true - user can change the rate he gave before
			haschild			: false,				//if true - stars will be disabled and mean value from children will be loaded
			showmean			: true,					//if false - user's rate will be shown instead of mean
			delayed				: false,				//if true - user's rate will not be send untill submit function is called
			//required			: false,				//if true - it must be rated before delayed submission
			debug				: false,				//if true - error messages will be displayed
			
			skin				: 'skins/skin.png',		//path to skin file. Can point to several files: 'file1|file2|file3' for back, front, hover skins
			stars				: 5, 					//number of stars
            step				: 1, 					//round every 'step' stars. For example step=0.5 will cause rounding to half star on hover
			round				: true,					//if true - mean value(loaded from DB) will be also rounded 
            decimals			: 1,					//number of decimal digits
			cursor				: 'pointer', 			//cursor CSS property
			
			load				: true,					//load stats on start or not
			enable				: true,					//enable/disable mouse
			animate				: false,				//apply animation
			easing				: 5,					//easing coefficient (if animate==true)
			
			showtext			: true,					//show text messages
			tooltip				: true,					//show tooltip on mouse over: true,false, 'mobile', 'desktop'
			tooltiphtml			: '',					//html formatted template for tooltip. Use %text to output the texthover
			tooltipclass		: 'rating-tooltip',		//CSS class to apply to tooltip
			tooltipeasing		: 2.5,					//easing coefficient for tooltip
			textlocation		: 'bottom',				//'top', 'bottom' or ID of the dom element
			minvotes			: 0,					//minimum votes required to show main text (see textmain below)
			textstart			: 'rate to see statistics',		//text shown on start if load=false
			textloading			: '...loading',			//text on stats loading
			textminvotes		: '%r votes required',			//if number of votes<minvotes this text will be shown	
			textmain			: '%ms / %maxs (from %v votes)',		//main text, shown then stats are loaded		
			texthover			: 'very bad|bad|average|good|perfect', //text shown on mouse move in format 'txt1|txt2|txt3|...|txtN'. Which part of this text will be shown depends on pointed value 
			textthanks			: 'thank you',						//text shown on user vote
			//textrequired		: 'please rate me',		//text shown if item was not rated (in case of delaed submit)
			textdelay			: 1500,					//delay in milliseconds to show textthanks, if ==0 no textthanks will be shown
			
			ondata              : function(){},			//triggers then stats loaded from DB
			onmove              : function(){},			//triggers then user changes value by mouse moving
			onlieve             : function(){},			//triggers then user move mouse away
			onclick             : function(){}			//triggers then user voted
			
			//,custom				:''						//custom user data
			
		} ,params);
					
			
		//variables
		
		var layers;				
		var layersSettings=new Array();		
		var timerId=-1;
		var tttimerId=-2;
		var w=0;
		var skinWidth=0;
		var	skinHeight=0;
		var	message='';
		var uniqueID='stars_'+Math.floor(Math.random()*10000)+'_'+(new Date()).getTime()+'_'+Math.floor(Math.random()*10000);
		var pointeduservalue=-1;
		var pressedvalue=0;
		var meanValue=0;
		var userValue=0;
		var totalVotes=0;
		var $textField;
		var textArray;
		var statsloaded=false;
		var justVoted=false;
		var initialMouseStatus=true;
		var hardDisable=false;
		var initcomplete=false;
		var pointedtext='';
		var pointedindex=0;
		var pressedtext='';
		var multiplier=Math.pow(10,settings['decimals']);
		var $tooltip;
		var tooltipVisible=false;
		var tooltipx=0;
		var tooltiptargetx=0;
		var touch = null;
		var isPC = null;
		var pressed=false;
		
		/* ------ PRIVATE FUNCTIONS ------ */
		
		
		var echo = function() {
			if (window.console && settings['debug']) console.log(arguments);
		};
		
		//init UI
		var init = function () {
			
			touch = ( 'ontouchstart' in document.documentElement ) ? true : false;
			isPC = (/Mobile/i.test(navigator.userAgent) && !/ipad/i.test(navigator.userAgent) )? false : true;
		
			applySkin();
			startTimer();			
			updateText(); 
			initControls();
			connectToDB();
			tooltipCheck();
		};
		
		
		var tooltipCheck = function(){
			if(touch && !isPC){			
				if(settings['tooltip']=='mobile') settings['tooltip']=true;
				else if(settings['tooltip']=='desktop') settings['tooltip']=false;
			}else{
				if(settings['tooltip']=='desktop') settings['tooltip']=true;
				else if(settings['tooltip']=='mobile') settings['tooltip']=false;			
			}
			if(settings['tooltip']===true){
				if(!checkRatingTooltipClass(settings['tooltipclass'])) settings['tooltip']=false;
			}	
		}
		
		////////// DB FUNCTIONS ////////////////
		
		var connectToDB = function(){
			echo('connect',arguments);
			//if in view-mode
			if(settings['displaymode']){				
				settings['enable']=false;				
				initialMouseStatus=false;
				initControls();
				parsePHPResponce(totalVotes+'|'+meanValue+'|99999|', false);
				return;
			}
		
			//dont load stats if load setting = false
			if(arguments[0]!=true && settings['load']!=true){				
				text(settings['textstart']);
				return;
			}
			
			//if no item ID provided
			if(settings['id']==''){
				statsloaded=true;
				updateText();
				return;
			}
			
			//if no php file provided
			if(settings['php']==''){						
				
				//make use of local storage if available
				if (!html5_storage_support()){
					statsloaded=true;
					updateText();
					return;
				}
				else{
					
					if(arguments[0]){
						//save user rate
						meanValue=Math.min(Math.max(userValue,0),100);
						localStorage.setItem(settings['id'].concat('::',settings['property']),meanValue);
						totalVotes=1;
						//if not rewritable - disable it
						if(settings['rewrite']===false){disable();hardDisable=true;}
						
					}else{
						//load user rate
						var v=localStorage.getItem(settings['id'].concat('::',settings['property']));
						if(v){
							if(typeof v == 'string') meanValue=parseValue(v);
							else meanValue=v;
							totalVotes=1;
							
							//if not rewritable - disable it
							if(settings['rewrite']===false){disable();hardDisable=true;}
							
						}else {meanValue=0;totalVotes=0;}
					}
					
					parsePHPResponce(totalVotes+'|'+meanValue+'|'+(arguments[0]?(settings['textdelay']/1000):0)+'|', arguments[0]?true:false);
					
					return;
				}
								
			}
			
			/*
			//if we display only users rate 
			if(settings['showmean']===false){// && html5_storage_support()
				//and user doesn't vote
				
				if(arguments[0]!=true){
					//load user rate from localstorage
					var v=localStorage.getItem(settings['id'].concat('::',settings['property']));
					
					if(v){
						if(typeof v == 'string') meanValue=parseValue(v);
						else meanValue=v;
						totalVotes=1;
						parsePHPResponce(totalVotes+'|'+meanValue+'|'+(arguments[0]?(settings['textdelay']/1000):0)+'|', arguments[0]?true:false);
						return;
					}else {
						meanValue=0;totalVotes=0;
					}
					
				}else{
					//if rated
					//save user rate in localstorage
					meanValue=Math.min(Math.max(userValue,0),100);
					localStorage.setItem(settings['id'].concat('::',settings['property']),meanValue);	
					totalVotes=1;
				}
			}
			*/
			
			
			if((settings['delayed']===false || arguments[0]!=true) && settings['php']!='' && (!settings['haschild'] || settings['showmean'])){
				send_ajax_request(arguments[0]);
			}
			
		};
		
		
		var send_ajax_request = function(){
			disable();
						
			var isRated=false;
			dataString = ''; 
			var dataString=dataString.concat('id=',encodeURIComponent(settings['id']));
			if(settings['property']!='') dataString=dataString.concat('&p=',encodeURIComponent(settings['property']));
			if(settings['rewrite']===true) dataString=dataString.concat('&rw=1');
			if(settings['showmean']===false) dataString=dataString.concat('&u=1');		
			
			if(arguments[0]){
				
				statsloaded=false;
				
				
				if(settings['title']!='') dataString=dataString.concat('&i=',encodeURIComponent(settings['title']));				
				dataString=dataString.concat('&r=',Math.min(Math.max(userValue,0),100),'&ref=',encodeURIComponent(document.URL));
				isRated=true;

				//also add title from parent 
				var tempid=settings['id'].replace(/['"\s]/g, '_').replace(/[^a-z0-9_]/gi, '');
				var parent=$( ".rating_system_class_id_" + tempid).first();
				
				
				//if(parent.rating('votes')<1){				
					var parenttitle=parent.rating('option','title');					
					if (typeof parenttitle == 'undefined' || parenttitle==''){
						parenttitle=parent.rating('id');						
					}
					
					dataString=dataString.concat('&pt=',encodeURIComponent(parenttitle));					
				//}
				
				/*
				if(settings['custom']!=''){					
					//add users data from data-custom
					dataString=dataString.concat('&ud[custom]=',encodeURIComponent(settings['custom']));
				}else{
					//else: add users data from i.e. Textarea 				
					if(settings['property']!=''){
						var $relatedItems=$('*[data-property="'+settings['property']+'"][data-id="'+settings['id']+'"]'); 
					}else{
						var $relatedItems=$('*[data-id="'+settings['id']+'"]');
					}
					
					$relatedItems.each(function (){
						if ($(this).data(plugin)===undefined){
							var title=$(this).data("title");
							if(title===undefined) title='';
							dataString=dataString.concat('&ud[',encodeURIComponent(title),']=',encodeURIComponent($(this).val()));
						}
					});
				}
				*/
			
				
			}
			
			
			var jqxhr = $.ajax({
				type : isRated?'POST':'GET',
				url : settings['php'],
				dataType : 'text',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				data: dataString, 
				cache: false,
				////// support next versions of jQuery
				done : function(data){ //success
					parsePHPResponce(data, isRated);					
				},				
				fail : function(XMLHttpRequest, textStatus, errorThrown) { //error
					text('PHP problem | '+settings['php']+' | '+errorThrown,settings['debug']);
					disable();
				},
				
				///// support preveous versoins of jQuery
				success : function(data){					
					parsePHPResponce(data, isRated);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {					
					text('PHP problem | '+settings['php']+' | '+errorThrown,settings['debug']);
					disable();
				}
				
			});
		};
		
		
		var html5_storage_support = function() {			
			try { return typeof window.localStorage == 'object';}
			catch (e) {return false;}
		};
		
		
		var parsePHPResponce = function(data, isRated){			
		    	echo('parse',arguments);
		
			disable();
			var r = data.split('|'); //'n|mean|time|connectedvotes;connectedrates|error';
			
			//check for error message
			if (r[4]!='' && typeof r[4] != 'undefined'){
				text('Error: '+r[4],settings['debug']);
				if(typeof settings['ondata'] == 'function') settings['ondata']({id:settings['id'], value:meanValue, votes:totalVotes, error:r[3]}, $thisObj);
			}else{
				
				statsloaded=true;
				totalVotes=parseInt(r[0]); 
				meanValue=parseFloat(r[1]);
				
				
				if(totalVotes==0 && meanValue==0){
					//clear local 
					if(html5_storage_support()){
						localStorage.removeItem(settings['id'].concat('::',settings['property']));
					}
				}
				
				
				stopTimer();
								
				layersSettings[1].target=(totalVotes>=settings['minvotes'])?getRoundPercent(meanValue):0;
								
				if(!settings['animate']){
					layersSettings[1].value=layersSettings[1].target;
				}
				//setLayerWidth(2, 0, false);
			
				if(isRated && settings['textdelay']>0)	setTimeout(startTimer,settings['textdelay']);
				else startTimer();
				
			//	forgetVote();
				updateText(isRated);
				
				//keep in mind that it could be disabled by user!!
				//enable mouse
				if(initialMouseStatus){
					
					
					if(hardDisable===false){
					
						r[2]=parseInt(r[2]);
						
						var timetonextvote=(r[2]>500)?500000:Math.max(0,r[2]*1000);  //ie problem solved
						
						if(isRated && settings['textdelay']>0) timetonextvote=Math.max(timetonextvote,settings['textdelay']);						
						
						if(timetonextvote>0) {setTimeout(enable,timetonextvote);}
						else{enable();}
					}
					
					
					//reload affected items rates (same id, for which property is not set)
					if(isRated && settings['property']!=''){
						if ((r[3]!='' && typeof r[3] != 'undefined') || settings['php']==''){
							var connectedItem=r[3].split(';');
							connectedItem[0]=parseInt(connectedItem[0]);
							connectedItem[1]=parseFloat(connectedItem[1]);
							var tempid=settings['id'].replace(/['"\s]/g, '_').replace(/[^a-z0-9_]/gi, '');					
							
							$( ".rating_system_class_id_" + tempid ).each(function (){
								var $parentObj=$(this);
								if($parentObj[plugin]("option","showmean")===false){
									//calculate sum of children
									$parentObj[plugin]("getsumofchildren");									
								}else{
									if($parentObj[plugin]("votes")<connectedItem[0]+(settings['rewrite']?1:0)){
										$parentObj[plugin]("votes",connectedItem[0]);
										$parentObj[plugin]("value",connectedItem[1]);
										$parentObj[plugin]("option","enable",false);
									}else{
										$parentObj[plugin]("reload");
									}
								}
								
							});						
						}				
					}else if(!isRated && settings['property']!=''){
						
						var tempid=settings['id'].replace(/['"\s]/g, '_').replace(/[^a-z0-9_]/gi, '');					
						
						$( ".rating_system_class_id_" + tempid ).each(function (){
							var $parentObj=$(this);
							if($parentObj[plugin]("option","showmean")===false){
								//calculate sum of children
								$parentObj[plugin]("getsumofchildren");	
							}
						});
						
					}

					
				}
				
				if(typeof settings['ondata'] == 'function') settings['ondata']({id:settings['id'], value:meanValue, votes:totalVotes, error:''}, $thisObj);
				
			}
			
		};
		
		
		
		var unfreeze = function(){
			startTimer();
		};
		
		var forgetVote = function(){
			justVoted=false;
			updateText();
		};
		
		var enable = function(){			
			////trace('enable',true);
			
			settings['enable']=true;
			initControls();	
			echo('enabled');
		};
		
		var disable = function(){
			settings['enable']=false;
			initControls();	
			echo('disable');
		};
		
		
		
		// TEXT FUNCTIONS
		
		// update text
		var updateText = function(isRated){				
			var t;
			if(justVoted && settings['textdelay']>0) {
				t=settings['textthanks'];
				setTimeout(forgetVote,settings['textdelay']);
			}
			else if(!statsloaded) t=settings['load']?settings['textloading']:settings['textstart'];
			else if(settings['minvotes']>totalVotes) t=settings['textminvotes'];			
			else t=settings['textmain'];			
			text(t,true,isRated);
		};
		
		var text = function(t){			
			if((!settings['showtext'] && !settings['tooltip']) || arguments[1]===false) return;
			
			if(t.replace(/^\s+|\s+$/gm,'')=='') t='&nbsp;';
			else t=replaceKeyWords(t);
			
			echo('text',t);
			
			if(initcomplete) {			
				
				if(settings['tooltip']===true && arguments.length==1){
					
					//if tooltip template is here
					if(settings['tooltiphtml']!=''){
						var temphtml=replaceKeyWords(settings['tooltiphtml']);
						t=temphtml.replace(/%text/gi, t);
					}
					
					$tooltip.children('div').html(t);
				}else if(settings['showtext']===true){
					$textField.html(t);
					
					/*
					if(arguments[2]===true && settings['textdelay']>0 && isPC){
						$textField.fadeOut( settings['textdelay'], function() {
							$textField.fadeIn(400);
						});
					}
					*/
				}
			}
			
		};
		
		var replaceKeyWords = function(t){
			if(typeof t != 'string') return t;	
			t=t.replace(/%v/gi, totalVotes);											//total votes
			t=t.replace(/%ms/gi, (Math.round(getStarsFromPercent(meanValue)*multiplier)/multiplier));	//mean rate in stars
			t=t.replace(/%ps/gi, (Math.round(getStarsFromPercent(userValue)*multiplier)/multiplier));	//pointed rate in stars
			t=t.replace(/%maxs/gi, settings['stars']);								//number of stars (maximum)
			t=t.replace(/%m/gi, Math.round(getRoundPercent(meanValue)));				//mean rate in %
			t=t.replace(/%p/gi, Math.round(getRoundPercent(userValue)));				//pointed rate in %
			t=t.replace(/%rr/gi, (settings['minvotes']-totalVotes));					//votes still req. to display stats 
			t=t.replace(/%r/gi, settings['minvotes']);								//minimum votes to display stats
			t=t.replace(/%i/gi, pointedindex);
			return t;
		};
		
		
		
		// MOUSE CONTROLS
		
		var initControls = function () {			
			echo('initControls: '+settings['enable']);
			if (!touch || isPC){
				//desktop
				//onMouseLieveWindow(false);
				$('#'+uniqueID).unbind('mouseover mousemove mousedown mouseup mouseleave', mouseHandler);							
				if(settings['enable']==true){
					$('#'+uniqueID).bind('mouseover mousemove mousedown mouseup mouseleave', mouseHandler);
					//onMouseLieveWindow(true);				
				}
				
				$('#'+uniqueID).css('cursor', settings['enable']?settings["cursor"]:'auto');			
			}
			
			if(touch){
				//mobile
				
				$('#'+uniqueID).unbind('touchstart touchend touchmove', mouseHandler);			
				if(settings['enable']==true){
					$('#'+uniqueID).bind('touchstart touchend touchmove', mouseHandler);				
				}
			}
			
		};
		
		
		var onMouseLieveWindow = function(flag){			
			if (document.addEventListener) {
				if(flag) document.addEventListener("mouseout", mouseHandler, false);
				else document.removeEventListener("mouseout", mouseHandler, false);
			}
			else if (document.attachEvent) {
				if(flag) document.attachEvent("onmouseout", mouseHandler);
				else document.detachEvent("onmouseout", mouseHandler);
			}			
		};
		
		
		// Get the coordinates based on input type
		var getMouseCoordinates = function( e ){
			var scroll = $('html').scrollLeft();
			if (! scroll) scroll = $('body').scrollLeft();
			
			if(touch && (e.type=='touchstart' || e.type=='touchend' || e.type=='touchmove')){				
				if(e.type!='touchstart') e.preventDefault();				
				var tch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];							
				var x=tch.pageX;			
			}else {		
				var x = e.clientX;
			}	
		
			x=x + scroll- $('#'+uniqueID).offset().left+0.0001;
			if (x > w) x = w;
			else if (x<1) x = 1;
			x=getCeil(x);
			
			return x;
		};
		
		
		///////////////////////////////////////////////////////////
		var mouseHandler = function (e) {	
			e = e ? e : window.event;
			
			//console.log(e.type);
			
			switch (e.type){
			case 'mouseover':
				$('#'+uniqueID).unbind('mouseover', mouseHandler);
				stopTimer();			
				setLayerWidth(1, 0, false,true);	//hide mean value layer
			
			case 'touchstart':	
				if(e.type=='touchstart') $('#'+uniqueID).unbind('touchstart', mouseHandler);	
				
				var x=getMouseCoordinates(e);
				var v=x*100/w;
				userValue=Math.round(v*multiplier)/multiplier; //round to x.x%
			
				tooltipx=x;
				tooltiptargetx=x;
				
				//show tooltip
				updateHoverText(x);
				showTooltip(true);
				moveTooltip(x);
			
				
				if(e.type=='touchstart'){
					pressed=true;
					mouseDown();
				}
			
			break;
			
			case 'mousemove':
			case 'touchmove':
			
				var x=getMouseCoordinates(e);
				//console.log('x: '+ x);
				if(x>0){
					var v=x*100/w;
					userValue=Math.round(v*multiplier)/multiplier; //round to x.x%
					tooltiptargetx=x;
					
					if(settings['tooltip']){
						//moveTooltip(x);
						tooltipTimer(true);
					}else{
						updateHoverText(x);
					}
					
					
					setLayerWidth(1, 0, false,true);		
					setLayerWidth(2, x, false);	//users layer	
					if(pointeduservalue!=v && typeof settings['onmove'] == 'function'){
						pointeduservalue=v;
						settings['onmove'](v, $thisObj);
					}
				}else{
					tooltipTimer(true);
				}
				
			break;
			
			
			case 'mousedown': 				
				pressed=true;
				mouseDown();				
			break;
			
			
			case 'touchend':		
				$('#'+uniqueID).bind('touchstart', mouseHandler);
			case 'mouseup':	
				if(e.type=='mouseup') $('#'+uniqueID).bind('mouseover', mouseHandler);
				//hide tooltip
				showTooltip(false);
				
				if(pressed===false) return;
				
				disable();						
				mouseDown();
				
				justVoted=true;	
				pressedvalue=pointeduservalue;
				pressedtext=pointedtext;
				
				if(settings['delayed']===false){
					updateText();
					pointeduservalue=-1;
				}else{
					enable();				
				}
				
				connectToDB(true);
				if(typeof settings['onclick'] == 'function') settings['onclick'](userValue, $thisObj);
			break;
			
			
			case 'mouseout':
			case 'onmouseout':				
				$('#'+uniqueID).bind('mouseover', mouseHandler);
				var from = e.relatedTarget || e.toElement;
				if (!from || from.nodeName == "HTML"){}
				else return;				
				
			case 'mouseleave':
				$('#'+uniqueID).unbind('mouseover', mouseHandler);
				$('#'+uniqueID).bind('mouseover', mouseHandler);
				pressed=false;
				
				//hide tooltip
				showTooltip(false);
				
				//changed
				if(settings['delayed']!=true){
					layersSettings[1].target=(settings['minvotes']>totalVotes)?0:getRoundPercent(meanValue);
						updateText();
				}
				else if(justVoted){
					layersSettings[1].target=(settings['minvotes']>totalVotes)?0:getRoundPercent(pressedvalue);		
					text(pressedtext);
				}else updateText();
				
				var v;
				if(settings['animate']){
					layersSettings[1].value=userValue;
					v=userValue;					
				}else{
					layersSettings[1].value=layersSettings[1].target;
					v=meanValue;
				}
				
				
				if(settings['delayed']===false){	
					pointeduservalue=-1;
				}
				
				setLayerWidth(2, 0, false,true);	
				setLayerWidthPercent(1, (settings['minvotes']>totalVotes)?0:v, (settings['step']>0 && settings['round']));
								
				startTimer();
				if(typeof settings['onlieve'] == 'function') settings['onlieve']($thisObj);
			
			break;
			}
			
		};
		
		
		var updateHoverText = function(x){
			if(settings['showtext'] || settings['tooltip']){	
				var n=textArray.length;
				if(n>=1){
					var n2=(tooltipx>x && settings['tooltipeasing']>0)?(Math.floor(x*n/w)-1):(Math.ceil(x*n/w)-1);
					n2=(n2<0)?0:(n2>n-1?n-1:n2); //just in case
					pointedindex=n2+1;
					text(textArray[n2]);//
					pointedtext=textArray[n2];
					
				}
			}
		};
		
		
		
		var moveTooltip = function(x){
			
			
			if(settings['tooltip']){
				tooltipx=x;
				var $tspan=$tooltip.children('div');
				var tw=$tspan.outerWidth(true)+(skinWidth*settings['step']);
				var th=$tspan.outerHeight(true)+9;
				
				if(settings['showtext'] && settings['textlocation']=='top') th=th-$textField.outerHeight(true);
				
				$tooltip.css('top', -Math.round(th) ).css('left', Math.floor(x-tw/2)+$('#'+uniqueID).offset().left-$thisObj.offset().left).width(0); //$('#'+uniqueID).offset().left
			}
		
		};
		
		
		var mouseDown = function () {			
			layersSettings[1].target=(settings['minvotes']>totalVotes)?0:meanValue;			
			layersSettings[1].value=userValue;
			
			setLayerWidth(2, 0, false,true);
			setLayerWidthPercent(1, userValue, (settings['step']>0 && settings['round']));					
		};
		
		
		var showTooltip = function(flag){
			if(flag){
				if(settings['tooltip']===true && tooltipVisible===false){
					//tooltipTimer(true);
					//$('#'+uniqueID).append($tooltip);
					$thisObj.prepend($tooltip);
					tooltipVisible=true;
				}
			}else{
				//hide tooltip
				if(tooltipVisible===true){
					tooltipTimer(false);
					$tooltip.remove();
					tooltipVisible=false;
				}
			}
		};
		
		
		///////////////////////////////////////////////////////////////////////////////////////////
		
		
		//enter frame
		
		var onEnterFrame = function (){
			
			var dw,n=0;
			
			//possible extention for future (can animate several layers)
			for (var i=1; i<2; i++){			 
				dw=(layersSettings[i]['target']-layersSettings[i]['value']);
				if(dw==0){
					n++;
					//continue;
				}else if(Math.abs(dw)<1) {
					layersSettings[i]['value']+=dw;
				}else{
					if(settings['easing']<=0) layersSettings[i]['value']+=dw;
					else layersSettings[i]['value']+=dw/(1+settings['easing']);
				}	
				setLayerWidthPercent(i, layersSettings[i]['value'], (settings['step']>0 && settings['round']));//false
			}	
			
			if(n==1) stopTimer();
			
		};
		
		
		
		// start timer 
		var startTimer = function(){	
			if(timerId==-1){
				setLayerWidthPercent(1, layersSettings[1]['value'], (settings['step']>0 && settings['round']));
				timerId=setInterval(onEnterFrame, 20);
			}			
		};
		
		
		// stop timer
		var stopTimer = function(){
			clearInterval(timerId);
            timerId = -1;
		};
		
		
		// start timer for tooltip
		var tooltipTimer = function(flag){	
			if(settings['tooltip']){
				if(flag){
					if(tttimerId==-2){
						tttimerId=setInterval(ttonEnterFrame, 20);
					}
				}else{
					clearInterval(tttimerId);
	            	tttimerId = -2;
				}
			}
		};
	
	
		var ttonEnterFrame = function (){
			var dw=(tooltiptargetx-tooltipx);
			if(Math.abs(dw)<1) {
				tooltipTimer(false);
			}else{
				if(settings['tooltipeasing']>0) dw=dw/(1+settings['tooltipeasing']);
			}	
			
			//move tt
			updateHoverText(tooltipx+dw);
			moveTooltip(tooltipx+dw);
		};
		
		
		// layers manipulation
		var setLayerWidth = function(i, width, round, dontupdatebg){		
			if(round==true) width=getRound(width);
			
			width=Math.min(Math.round(width),w*settings['stars']);
			
			layers[i].style.width=width+'px';
			
			if(i>0 && !dontupdatebg){
				
				layers[0].style.backgroundPosition= -width+'px 0';
				layers[0].style.left= width+'px';
				layers[0].style.width=(w-width)+'px';
			}			
		};
       
		var setLayerWidthPercent = function(i, width, round){
			width=Math.round(w*width/100);
			setLayerWidth(i, width, round);
		};
		
		
		// Math functions
		
		var getCeil = function(n){
			if(settings['step']>0)	return Math.ceil(n/(skinWidth*settings['step']))*(skinWidth*settings['step']);
			return n;
		};
		
		
        var getRound = function(n){
			if(settings['step']>0)	return Math.round(n/(skinWidth*settings['step']))*(skinWidth*settings['step']);
			return n;
		};
        
		var getRoundPercent = function(n){
			if(settings['round']) return getRound(n*w/100)*100/w;
			else return n;
		};
		
		var getStarsFromPercent = function(n){
			if(settings['step']>0 && settings['round']) return Math.round(n*w/(100*skinWidth*settings['step']))*settings['step'];
			return n*w/(100*skinWidth);
		};
	
	
		
		//get and store id, data-title, data-value and data-settings 
		var parseIndividualSettings = function (){
			//item ID (for flexibility reasons can be set in different places)
			// id priority (descending):			
			// 1) data-settings='id:ID'
			// 2) data-id='ID'
			// 3) settings id ( .rating({id:'ID'}))
			// 4) id (from <DIV id='ID'></DIV>)
						
			if(settings['id']==''){
				var id=$thisObj.attr("id");
				if(id!=undefined) settings['id']=id;
			}
			
			var settings_string;	
			//parse any data-xxx
			for(var prop in settings) {
				if(settings.hasOwnProperty(prop)){
					if(typeof settings[prop] != 'function'){				
						settings_string=$thisObj.data(prop);
						if(typeof settings_string!='undefined'){
							if(typeof settings_string == 'string'){
								settings_string=settings_string.replace('/\t\n\r/i', '');
								settings_string=settings_string.replace(/^\s+|\s+$/g, '');
								
								if(prop=='id' || prop=='title'){
									settings[prop]=settings_string;
								}else settings[prop]=parseValue(settings_string);
								
							}else settings[prop]=settings_string;
													
							checkSettings(prop);							
						}
					}
				}
			}
			
			
			// data-value (usable then display mode is on. No stats loaded from DB)
			settings_string=$thisObj.data("value");			
			if(typeof settings_string!='undefined' && settings_string!=''){
				meanValue=parseFloat(settings_string);
				meanValue=meanValue<0?0:meanValue>100?100:meanValue;
			}
			
			// data-votes
			settings_string=$thisObj.data("votes");			
			if(typeof settings_string!='undefined' && settings_string!=''){
				totalVotes=parseInt(settings_string);
				totalVotes=totalVotes<0?0:totalVotes;
			}
			
			// parse data-settings 	
			settings_string = $thisObj.data("settings");
			if(typeof settings_string!='undefined' && settings_string!=''){					
				settings_string=settings_string.replace('/\t\n\r/i', '');				
				var settings_array = settings_string.split(";");
				for (var j=0; j<settings_array.length; j++){
					var name_val=settings_array[j].split(":");						
					name_val[0]=name_val[0].replace(/\s/g, '');
					if(typeof settings[name_val[0]]!='undefined' && name_val.length>=2){						
						name_val[1]=name_val[1].replace(/^\s+|\s+$/g, '');						
						settings[name_val[0]]=parseValue(name_val[1]);						
						checkSettings(name_val[0]);
					}
				}
			}	
			
			checkSettings('enable');
			checkSettings('haschild');
			checkSettings('texthover');
			
		};
		
		
		// converts strings to boolean or number
		var parseValue = function (x) {	
			var lower=x.toLowerCase();
			if(lower==String("false") || lower==String("no")) return false;
			if(lower==String("true") || lower==String("yes")) return true;
			if(isNaN(parseFloat(x))==false) return parseFloat(x);
			return x;
		};
		
		
		
		//confirm settings (fool proof)
		var checkSettings = function(sname){
			switch(sname){
				case 'id':				settings['id']=String(settings['id']); break;
				case 'easing':			if(settings['easing']<0) settings['easing']=0; break;
				case 'step':			if(settings['step']<0) settings['step']=0; break;
				case 'stars':			if(settings['stars']<0) settings['stars']=0;	 break;
				case 'minvotes':		if(settings['minvotes']<0) settings['minvotes']=0;	 break;
				case 'decimals':		if(settings['decimals']<0) settings['decimals']=0;	
										multiplier=Math.pow(10,settings['decimals']); break;
				case 'textdelay':		if(settings['textdelay']<0) settings['textdelay']=0;	 break;
				case 'texthover':		
					if(typeof settings['texthover']=='string') textArray=settings['texthover'].split('|');	 
					else{textArray=[]; textArray[0]=settings['texthover'].toString();}
					break;
				case 'haschild':		if(settings['haschild']) {settings['enable']=false; initialMouseStatus=false; if(settings['php']==''){settings['showmean']=false;}}	 break;
				case 'enable':			initialMouseStatus=settings['enable'];	 break;
			}
		
		};
		
		
		//skin functions
		
		var getSkinSize = function(isupdate){			
			initcomplete=false;
			var tempImg = $('<img "/>');
			var path=settings['skin'].split('|')[0];			
			var img = new Image();
			img.onload = function() {				
				skinWidth= this.width;
				skinHeight= this.height;
				//$thisObj.empty();
				if((skinWidth==0 || skinHeight==0) && settings['debug']){ 
					$textField=$('<div class="rating-text" style="z-index:3;">detected skin '+path+' size '+skinWidth+'x'+skinHeight+' please check the path to skin file.</div>');			
					$thisObj.empty(); //clear object
					$thisObj.append($textField);	//append				
				}else{
					if(isupdate){
						applySkin(true);
						updateText();
						startTimer();						
						initControls();
					}
					else init();
				}
			};
			
			img.onerror=function() {				
				if(settings['debug']){ 
					$textField=$('<div class="rating-text" style="z-index:3;">cannot load skin '+path+'. Please check the path to skin file.</div>');			
					$thisObj.empty(); //clear object
					$thisObj.append($textField);	//append				
				}
				
			};
			img.src = path;			
		};
		
		
			
		var applySkin = function(isupdate){
			//prepare variables based on skin size
			var paths=settings['skin'].split('|');
			var isoneskin=(paths.length==1)?true:false;
			var h=parseInt(skinHeight/(isoneskin?3:1));
			w=settings['stars']*skinWidth;				
			
			
			
			if(settings['property']==''){			
				var tempid=settings['id'].replace(/['"\s]/g, '_').replace(/[^a-z0-9_]/gi, '');
				
				$thisObj.addClass( "rating_system_class_id_" + tempid);
			}
			
			
			//preventing possible problems with positioning
			var cssPosition=$thisObj.css('position');
			if(cssPosition=='static')	$thisObj.css('position', 'relative');	
			
			
			//add layers
			
			paths[0]=paths[0].replace(/^\s+|\s+$/g, "");
			paths[1]=(paths.length<2)?paths[0]:paths[1].replace(/^\s+|\s+$/g, "");
			paths[2]=(paths.length<3)?paths[1]:paths[2].replace(/^\s+|\s+$/g, "");
			var layerCommonStyle='position:absolute;top:0px;left:0px;padding:0;margin:0;background:';			
			var divs='<div id="'+uniqueID+'" style="display:block;position:relative;overflow:hidden;width:'+w+'px;height:'+h+'px;top:0px;left:0px;">';
			divs+='<div style="'+layerCommonStyle+'url('+paths[0]+') repeat-x;width:'+w+'px;height:'+h+'px;z-index:0;"></div>';
			divs+='<div style="'+layerCommonStyle+'url('+paths[1]+') 0px '+(isoneskin?-h:0)+'px repeat-x;width:0px;height:'+h+'px;z-index:1;"></div>';
			divs+='<div style="'+layerCommonStyle+'url('+paths[2]+') 0px '+(isoneskin?-h*2:0)+'px repeat-x;width:0px;height:'+h+'px;z-index:2;"></div>';
			divs+='</div>';
			
			//if(settings['tooltip']===true){
				$tooltip=$('<div class="'+settings['tooltipclass']+'" style="position:relative; z-index:9999;"><div style="position: absolute;"></div></div>');
			//}
			
			$textField=$('<div class="rating-text" style="z-index:3;"></div>');
			
			if(settings['textlocation']!='top' && settings['textlocation']!='bottom'){
				
				var obj;
				if(settings['textlocation']=='_id') obj=$('#_'+settings['id']);
				else obj=$('#'+settings['textlocation']);
				
				if(obj.length==1) $textField=obj;
				else settings['textlocation']='bottom';
			}
						
			$thisObj.empty(); //clear object
			if(settings['textlocation']=='top') $thisObj.append($textField,divs);	//append		
			else if(settings['textlocation']=='bottom') $thisObj.append(divs,$textField);	//append		
			else {				
				$thisObj.append(divs);
			}
			layers = $('#'+uniqueID).children('div');			
			//$textField.html(uniqueID);
			
			if(settings['textlocation']=='top' || settings['textlocation']=='bottom'){
			//	$textField=$textField.children('div');
			}
			
			var v=(settings['minvotes']<=totalVotes || (meanValue>0 && !initcomplete))?meanValue:0;
			
			if(isupdate==true){
				layersSettings[1]={value:v, target:v};
			}else{				
				layersSettings[1]={value:(settings['animate'])?0:v, target:v};			
				//layersSettings[0]={value:100, target:100}; 	//possible extension for future
				//layersSettings[2]={value:0, target:0}; 		//possible extension for future
				//setLayerWidthPercent(1, meanValue, (settings['step']>0 && settings['round']));
			}
			
			if(settings['stars']==0){
				$thisObj.css('height', '0px');
				w=1;
			}
			
			//possible problems with positioning avoided
			if(cssPosition=='static') $thisObj.css('position', 'static');
			
			initcomplete=true;
		};
		
		
		var resetstats = function(){
			stopTimer();
			meanValue=0;
			userValue=0;
			totalVotes=0;
			statsloaded=false;
			justVoted=false;			
			connectToDB();
		};
		
				
		////////////
		
		
		/* ------ PUBLIC FUNCTIONS ------ 
			option(property:String, value) 			
			enable()			
			disable()			
			value()
			votes()
		
		*/
				
		
		//options set/get
		this.option = function(prop, n){
			
			if(typeof prop != 'undefined'){
				
				prop=prop.replace(/^\s+|\s+$/g, "");				
				if(typeof settings[prop] === 'undefined'){return $thisObj;}			
				if(typeof n == 'undefined'){return settings[prop];}
				
				if(typeof n == 'string'){
					n=n.replace(/^\s+|\s+$/g, '').toLowerCase();					
					settings[prop]=parseValue(n);
				}else if(typeof n == 'boolean') settings[prop]=n;
				else if(typeof n == 'number') settings[prop]=n;
				
				//check settings
				checkSettings(prop);
				
				//do some action on update settings
				if(prop=='enable' && initcomplete){initControls();}
				else if(prop=='cursor' && initcomplete){	
					$('#'+uniqueID).css('cursor', settings['enable']?settings["cursor"]:'auto');
				}
				else if(prop=='id'){resetstats();}
				else if(prop=='showmean'){resetstats();}
				else if(prop=='tooltip'){tooltipCheck();}
				else if(prop=='showtext' && initcomplete){
					if(settings['showtext']) updateText();
					else $textField.html('');
				}
				else if(prop=='skin'){getSkinSize(true);}	
				else if(initcomplete && (prop=='round' || prop=='step')){
					layersSettings[1].target=(totalVotes>=settings['minvotes'])?getRoundPercent(meanValue):0;
					if(!settings['animate']) layersSettings[1].value=layersSettings[1].target;
					updateText();
					startTimer();
				}	
				else if(prop=='decimals' && initcomplete){
					updateText();
				}else if(prop=='textlocation' && initcomplete){
					if(settings['textlocation']=='top') $textField.prependTo($thisObj);
					else if(settings['textlocation']=='bottom') $textField.appendTo($thisObj);
					else{
						var obj=$('#'+settings['textlocation']);				
						if(obj.length==1){
							$textField.appendTo(obj);
						}
					}
				}
				else if(prop=='stars' && initcomplete){					
					w=settings['stars']*skinWidth;
					$('#'+uniqueID)[0].style.width=w+'px';					
					updateText();
					var temp=layersSettings[1].value;
					layersSettings[1].value=0;
					startTimer();
					layersSettings[1].value=temp;
				}
				else if(prop=='load' && initcomplete){
					statsloaded=false; updateText();
				}
				else if(settings['showtext']==true && initcomplete){
					if(prop.substr(0,4)=='text') updateText();
				}				
				return $thisObj;
			}
		};
		

		this.value = function(n){
			if(typeof n == 'undefined') return meanValue;
			n=n<0?0:n>100?100:n;

			//n=(totalVotes>=settings['minvotes'])?getRoundPercent(n):0;
			
			layersSettings[1]={target:n, value:meanValue};			
			meanValue=n;			
			updateText();			
			if(arguments[1]==true) layersSettings[1].value=n;
			if(initcomplete) startTimer();
			return $thisObj;
		};
		
		this.votes = function(n){
			if(typeof n == 'undefined') return totalVotes;
			else{
				n=n<0?0:n;
				totalVotes=n;	
				updateText();
				return $thisObj;
			}
		};
		
		this.id = function(id){
			if(typeof id === 'undefined') 
				return settings['id'];
			settings['id']=String(id);
			resetstats();
			return $thisObj;
		};
				
		this.reload = function(){
			resetstats();
			return $thisObj;
		};
		
		this.getsumofchildren = function(){
			if(settings['haschild']!=''){
				var $relatedItems=$('*[data-id="'+settings['id']+'"]').not($thisObj);
				
				var totalUserRate=0;
				var count=0;
				
				$relatedItems.each(function (){		
					var $obj=$(this);
					if(typeof $obj.data(plugin) !== 'undefined'){
						totalUserRate+=$obj[plugin]("value");
						count++;
						
					}
				});
				
				totalUserRate=totalUserRate/count;
				totalVotes=(count==0 || totalUserRate==0)?0:1;
				statsloaded=true;
				$thisObj[plugin]("value",totalUserRate);
				
				//save in local data if necessary
				if(settings['php']=='' && settings['haschild']!==false && html5_storage_support()){
					localStorage.setItem(settings['id'].concat('::',settings['property']),totalUserRate);
					//$thisObj[plugin]("votes",count);
				}
				
			}
			return $thisObj;
		};
		
		
		this.submit = function(){
			//var d = new Date();
			//if(settings['required']===true && (layersSettings[1].value!=pressedvalue || userValue==0)){
			//	text(settings['textrequired'],true);
			//}else 
			
			if(settings['delayed']===true && layersSettings[1].value>0 && initialMouseStatus===true && settings['haschild']===false && userValue>0 && pressedvalue>0){
				
				userValue=layersSettings[1].value;
				justVoted=true;	
				pointeduservalue=-1;
				pressedvalue=0;
				if(settings['php']!='') send_ajax_request(true);
				userValue=0;				
			}
			return $thisObj;
		};
		
		/*
		this.getdatatosubmit = function(){
			var me={};
			me['id']=settings['id'];
			me['delayed']=settings['delayed'];
			me['layervalue']=layersSettings[1].value;
			me['userValue']=userValue;
			me['pressedvalue']=pressedvalue;
			me['initialMouseStatus']=initialMouseStatus;
			me['haschild']=settings['haschild'];
			me['property']=settings['property'];			
			me['required']=settings['required'];			
			//me['self']=$thisObj;
			return me;
		}
		*/
		
		/* ------ START ------ */
		
		parseIndividualSettings();
		getSkinSize(false);
		
	}

	
	// plugin name
	var plugin = 'rating';
	
	
	
	
	
	
	/* ------ ENTRY POINT FOR DELAYED SUBMISSION ------ */
	
	// plugin name
	var pluginsubmit = 'rating_submit';
	
	
	//TO DO?: make load and delayed submit in one big request
	$.fn[pluginsubmit] = function() {
		if (this.length == 0) return false;	
		var prev_id=-1;
		var delay=0;
		var delta_delay=50;
		//form the object to submit		
		this.each(function (){
			var $thisObj= $(this);					
			//var id=$thisObj[plugin]('getdatatosubmit');					
			var id=$thisObj[plugin]('id');
			if(prev_id!=id){
				prev_id=id;
				delay=0;
				$thisObj[plugin]("submit");
			}else{				
				delay+=delta_delay;
				setTimeout(function() {
					$thisObj[plugin]("submit");
				}, delay);				
			}
			
		});		
	};
		
		
		
	/* ------ ENTRY POINT  ------ */
		
	// everything starts here
	$.fn[plugin] = function(settings) {
		if (this.length == 0) return false;	
		
		var methodName = arguments[0];
		
		if (typeof methodName === 'string') { 
			
			var args = Array.prototype.slice.call(arguments, 1);
			
			
			//if (typeof methodName == 'string'){
				//alert('reseived: '+methodName+'('+args+')');
				if(methodName == 'submit'){	//
					
					return this.each(function (){
						var $thisObj= $(this);
						var instance= $thisObj.data(plugin);
						if(typeof instance[settings] === 'function')
							return instance[settings].apply($thisObj[0], args);
					});
				
				}else{
					
					//do not modify it! or connected items will not be updated correctly : line ca. 226
					var $thisObj= $(this);
					var instance= $thisObj.data(plugin);
					if(typeof instance[settings] === 'function'){						
						return instance[settings].apply($thisObj[0], args);
					}
									
				}
				
			//}
		
		}else if (typeof settings === "object" || !settings){
			return this.each(function (){
				var $thisObj= $(this);
				//var instance= $thisObj.data(plugin);
				//create rating
					if (typeof settings === 'object' || !settings){											
						return $thisObj.data(plugin,  new fiveStars($thisObj, settings));	
					}
			});
		
		}
		
		//return false;
		
		
	};
	
	
	
})(jQuery);


//check if tooltip css is defined (for 100% compartibility with v1)
var ratingtoolclasses={};
function checkRatingTooltipClass(classname){
	if(typeof ratingtoolclasses[classname] === 'undefined'){ 
		var csss = document.styleSheets;
		for(var j in csss){
			var rules = csss[j].rules || csss[j].cssRules;
			
			for (var i in rules){
			    if (typeof rules[i]['selectorText'] != 'undefined' && rules[i]['selectorText'].indexOf(classname) >= 0){
			        ratingtoolclasses[classname]=true;
			        return true;
			    }
			}
		}
		ratingtoolclasses[classname]=false;
	}
	return ratingtoolclasses[classname];
};