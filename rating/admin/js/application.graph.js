$(document).ready(function(){

	var monthNames=["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
	
	var options = {
		bars: { show: true,  barWidth:24 * 60 * 60 * 10 , align:"left", fillColor: { colors: [ { opacity: 0.4 }, { opacity: 0.4 } ] } },
		xaxis: { mode: "time", tickLength:5, timeformat: "%d/%0m/%y", ticks:6, monthNames:monthNames},
		yaxis: { min:0, tickDecimals:0},
		colors: ["#004586", "#ff420e", "#ffd320"],
		grid: { hoverable: true, clickable: true }
	};
	
	function tickFormatter(v, axis) {
        return v+" %<br>("+(Math.round(v/2)/10)+" / 5 stars)";
    }
	
	var options2 = {		
 		bars: { show: true,  barWidth:20 , align:"left", fillColor: { colors: [ { opacity: 0.4 }, { opacity: 0.4 } ] } },
		xaxis: { min:0,max:100,tickLength:5, ticks:5, tickFormatter: tickFormatter},
		yaxis: { min:0, tickDecimals:0},
		colors: ["#ff420e", "#ffd320"]		
	};
	
	
	
    var data = [];
    var placeholder = $("#placeholder");
    var placeholder2 = $("#placeholder2");
    
    //$.plot(placeholder, data, options);

    // fetch one series, adding to what we got
    var alreadyFetched = {};
	
	
	
	getDataNow('m','');
	
	setTimeout(function() {getVoteVSRate(10); }, 100);
	
	
	
	
	
    
    $("div.datanavigation").find("a").click(function(){
		var button = $(this); 
		var parent=button.parent();		
		if(parent.hasClass("disabled") || parent.hasClass("active")) return;
		
		var i=parent.index();
		var pi=parent.parent().index();
		var j=pi*3+i;
		var c=3+i;
		var a=$("div.datanavigation").find("a");
		
		a.parent().removeClass("active");
		$(a[c]).parent().addClass('active');
		
		var tmpl=['d','m','y'];
		var str='';
		
		for(var k=c;k<6;k++){
			str+=tmpl[i+(k-c)]+"="+(k==c?$(a[j]).html():$(a[k]).html())+"&";
		}
		
		
		getDataNow(tmpl[i],str);
		
		
	});
	
	 $("div.step").find("a").click(function(){
		var button = $(this);  
		var i = button.index();
		var tmpl=[5,10,20];
		if(button.hasClass('disabled')) return;
		$("div.step").find("a").removeClass('disabled');
		button.addClass('disabled');
		getVoteVSRate(tmpl[i]);
	});
	
	
   
    $("div.interval").find("a").click(function(){
		var button = $(this);  
		// find the URL in the link right next to us
		var tmpl=['d','m','y'];		
        var i = button.index();	
		var a=$("div.datanavigation").find("a");		
		a.parent().removeClass("active");
		$(a[3+i]).parent().addClass('active');
		//$("div.interval").find("a").css("font-weight","normal");
		//button.css("font-weight","bold");
		getDataNow(tmpl[i],'');
	});
	
	
	
	function getVoteVSRate(n) {
		options2.bars.barWidth=n;
		var dn=n;
		dataurl="php/jsondatarate.php?dn="+n+"&rnd="+Math.floor((Math.random()*999999));
				
		function onDataReceived2(series) {			
			var data=[];
			data.push(series);         
            $.plot(placeholder2, data, options2);			
         }
        
        $.ajax({
            url: dataurl,
            method: 'GET',
            dataType: 'json',
            success: onDataReceived2
        });
	}
	
	var currentView='m';
	
	function clickGraph(event, pos, item) {
        if (item) {
			 var x = item.datapoint[0];
            //alert("You clicked point " + item.dataIndex + " x,y " + x );
			var a=$("div.datanavigation").find("a");		
			a.parent().removeClass("active");
			$(this).css('cursor','default');
			
			if(currentView=='m'){
				currentView='d';
				$(a[3]).parent().addClass('active');
			}
			else if(currentView=='y'){
				currentView='m';
				$(a[4]).parent().addClass('active');
			}
			else if(currentView=='d'){
				currentView='y';
				$(a[5]).parent().addClass('active');
			}
			
			//var d=new Date();
			//x=x*1.0-d.getTimezoneOffset()*60000;
			//x+=1000;
			getDataNow(currentView,'t='+x);
        }
    }
	
	$("#placeholder").bind("plotclick", clickGraph);
	 var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {   
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;				
				$(this).css('cursor','pointer');
			}
		}
		else {
			$(this).css('cursor','default');
			previousPoint = null;            
		}
        
    });
	
	
	function getDataNow(dataurl,set) {
        currentView=dataurl;
		if(dataurl=='d'){
			options.bars.barWidth=60 * 60 * 1000;
			options.xaxis.timeformat="%h:%M<br>%d %b %y";
			options.xaxis.ticks=6;
			
			options.colors=["#0065A6"];
			//options.grid.hoverable=false;
			//options.grid.clickable=false;
		}else if(dataurl=='m'){
			options.bars.barWidth=24 * 60 * 60 * 1000;
			options.xaxis.timeformat="%d %b<br>%y";
			options.xaxis.ticks=6;
			
			options.colors=["#004586"];
			options.grid.hoverable=true;
			options.grid.clickable=true;
		}else if(dataurl=='y'){
			options.bars.barWidth=30* 24 * 60 * 60 * 1000;
			options.xaxis.timeformat="%d %b<br>%y";
			options.xaxis.ticks=6;
			
			options.colors=["#002566"];
			options.grid.hoverable=true;
			options.grid.clickable=true;
		}
		
		dataurl="php/jsondata.php?dt="+dataurl+"&"+set+"&rnd="+Math.floor((Math.random()*999999));
		
		//$("#datapoints").attr('href',dataurl);
		
		// then fetch the data with jQuery
        function onDataReceived(series) {		  
			// let's add it to our current data
            //if (!alreadyFetched[series.label]) {
            //alreadyFetched[series.label] = true;
				options.xaxis.min=series.mm[0];
				options.xaxis.max=series.mm[1];
			
				if(series.ddt){
					var a=$("div.datanavigation").find("a");
					for(var i=0;i<9;i++){
						a[i].innerHTML=series.ddt[i][0];
						if(i>5){							
							if(series.ddt[i][1]==1) $(a[i]).parent().addClass('disabled');	
							else $(a[i]).parent().removeClass('disabled');
						}						
					}
				}
				data=[];
                data.push(series);
           // }
   		
            // and plot all we got
            $.plot(placeholder, data, options);//
         }
        
        $.ajax({
            url: dataurl,
            method: 'GET',
            dataType: 'json',
            success: onDataReceived
        });
    }

	
	
	var donutsettings={
		series: {
			pie: { 
				innerRadius: 0.5,
				show: true				
			}
		},
		grid: {
			// hoverable: true
			
		}
	};
	
	
	var colors=["#edc240", "#afd8f8", "#cb4b4b", "#4da74d", "#9440ed","#004586", "#ff4209", "#ffd320", "#579d1c", "#7e0021"];
	//var colors=["#004586", "#ff4209", "#ffd320", "#579d1c", "#7e0021", "#83caff", "#314004", "#aecf00"];
	
	setTimeout(function() {loadDonutData(1); }, 300);
	setTimeout(function() {loadDonutData(2); }, 400);
	
	
	
	
	function loadDonutData(n){		
		var dataurl="php/jsongettop.php?flag="+n;
		function onDataReceived(series) {			
			
			if(n==2) donutsettings.legend={position:"nw"};
			else donutsettings.legend={};
			$.plot($("#donut"+n), series, donutsettings);
			
			//$("#donut"+n).bind("plothover", pieHover);
			//$("#donut"+n).bind("plotclick", pieClick);
			$("#donut"+n).find(".legendtitle").tooltip();
			
			var maxr=150;
			var minr=80;
			var len=series.length;
			
			var k=(series[0].data-series[len-1].data);
			k=(k==0)?series[0].data:(maxr-minr)/k;
			
			var r;
			
			var i;
			var title;
			var href;
			for (i = 0; i < len; ++i) {
				r=maxr-k*(series[0].data-series[i].data);
				title=$(series[i].label).attr('title');
				href=$(series[i].label).attr('href');
				$("#cir"+n).append('<div class="block"><a href="'+href+'" title="'+title+'" class="legendtitle stylish" style="width:'+r+'px;height:'+r+'px;border-radius:'+(r*0.66)+'px;line-height:'+r+'px;margin-top:'+(maxr-r)/2+'px;background:'+colors[i]+';">'+((n==1)?('<img src="img/star.png"></img>x'+Math.round(series[i].data*0.5)/10):'<img src="img/hand.png"></img>x'+series[i].data)+'</a></div>');
			}
			$("#cir"+n).find(".legendtitle").tooltip();
			
		}
		
		$.ajax({
            url: dataurl,
            method: 'GET',
            dataType: 'json',
            success: onDataReceived
        });
		
	}
		
	function pieHover(event, pos, obj){
		if (!obj){$(this).css('cursor','default'); return;}
		$(this).css('cursor','pointer');
	}
	
	function pieClick(event, pos, obj){
		if (!obj) return;
		//TO DO: go to item page
		percent = parseFloat(obj.series.percent).toFixed(2);
		alert(''+obj.series.label+': '+percent+'%');
	}
	
	
	$('#togglebtn').click(function () {			
			var ele = document.getElementById('d1');			
			var ele2 = document.getElementById('d2');			
			if(ele2.style.display == "none") {
				ele.style.display = "none";				
				ele2.style.display = "block";				
			}
			else {
				ele.style.display = "block";
				ele2.style.display = "none";			
			}
			
		} 
	);
	
});