function typeChat(){
		 
		  // Khai báo đối tượng Date
var date = new Date();
var caseChat;
 
// Lấy số thứ tự của ngày hiện tại
var current_day = date.getDay();
 
// Biến lưu tên của thứ
var day_name = '';

var hour = date.getHours();
var minutes = date.getMinutes();
 
if(current_day == 0){
	//chat fb
	return 2;
	
}else if((current_day == 6)){	
	return 2;

}else{	
	var currentHours = parseFloat(hour + '.' + minutes);
	if(((currentHours >= parseFloat(10.06)) && (currentHours <= parseFloat(23.59))) || ((currentHours >= '00.00') && (currentHours <= parseFloat(8.30)))){
		return 2;
	}else{
		return 3;
	}

	// check jo
	// chat subiz
}
	
}