function CalenderAct(useType,Prints,date){
	//참조 http://blog.kurien.co.kr/516
	//var date = date ? date : new Date();
	if(date == ''){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yy = today.getFullYear();
		if(dd<10) {
			dd='0'+dd
		} 
		if(mm<10) {
			mm='0'+mm
		} 
		today = yy+'-' + mm+'-'+dd;
		date = today
	}
	
	if( typeof( date ) !== 'undefined' ) {
		date = date.split('-'); // 년월일 배열 나누기
		date[1] = date[1] - 1; // 연산시작 0이기 때문
		date = new Date(date[0], date[1], date[2]); // 년월일 배열 담기
	}else{
		var date = new Date();
	}
	
	var currentYear = date.getFullYear();//년도를 구함			
	var currentMonth = date.getMonth() + 1; //연을 구함. 월은 0부터 시작하므로 +1, 12월은 11을 출력		
	var currentDate = date.getDate();//오늘 일자.
	
	date.setDate(1);
	var currentDay = date.getDay();	//이번달 1일의 요일은 출력. 0은 일요일 6은 토요일
	
	//var dateString = new Array('일', '월', '화', '수', '목', '금', '토');
	var lastDate = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if( (currentYear % 4 === 0 && currentYear % 100 !== 0) || currentYear % 400 === 0 )
	lastDate[1] = 29; //각 달의 마지막 일을 계산, 윤년의 경우 년도가 4의 배수이고 100의 배수가 아닐 때 혹은 400의 배수일 때 2월달이 29일 임.
	
	var currentLastDate = lastDate[currentMonth-1];
	var week = Math.ceil( ( currentDay + currentLastDate ) / 7 );  
	//총 몇 주인지 구함.
	
	if(currentMonth != 1){
		var prevDate = currentYear + '-' + ( currentMonth - 1 ) + '-' + currentDate;
	}else{
		var prevDate = ( currentYear - 1 ) + '-' + 12 + '-' + currentDate;
	}
	//만약 이번달이 1월이라면 1년 전 12월로 출력.
	
	if(currentMonth != 12){
		var nextDate = currentYear + '-' + ( currentMonth + 1 ) + '-' + currentDate;
	}else{
		var nextDate = ( currentYear + 1 ) + '-' + 1 + '-' + currentDate;
	}
	//만약 이번달이 12월이라면 1년 후 1월로 출력.
	
	
	if( currentMonth < 10 ){
		var currentMonth = '0' + currentMonth;
	}
	//10월 이하라면 앞에 0을 붙여준다.	

	
	var calendarWrite = '';
	
	calendarWrite += '<div>';
	calendarWrite += '<button type="button" onclick="CalenderAct(\''+useType+'\',\'' +  Prints + '\', \'' + prevDate + '\')"><img src="../images/admin/btn_calprev.png" alt="이전달" /></button>&nbsp;';
	calendarWrite += '<h1>'+currentYear + '</h1>년 <h2>' + currentMonth + '</h2>월';
	calendarWrite += '<button type="button" onclick="CalenderAct(\''+useType+'\',\'' +  Prints + '\', \'' + nextDate + '\')"><img src="../images/admin/btn_calnext.png" alt="다음달" /></button>';
	calendarWrite += '</div>';
	calendarWrite += '<table id="calendarTable">';
	calendarWrite += '<thead><tr><th width="14.3%">일</th><th width="14.3%">월</th><th width="14.3%">화</th><th width="14.3%">수</th><th width="14.3%">목</th><th width="14.3%">금</th><th width="14.3%">토</th></tr></thead>';
	calendarWrite += '<tbody>';
	
	var dateNum = 1 - currentDay;
	
	for(var i = 0; i < week; i++){
		calendarWrite += '<tr>';
		for(var j = 0; j < 7; j++, dateNum++){
			if( dateNum < 1 || dateNum > currentLastDate ){
				calendarWrite += '<td></td>';
				continue;
			}
			
			//데이트 피커용 10일 이하 0앞에 붙여주기
			var dateNums = '';
			if(dateNum <= 9){
				dateNums = '0'+ dateNum
			}else{
				dateNums = dateNum
			}
			
			//10일 이하라면 앞에 0을 붙여준다.
			calendarWrite += '<td id="'+currentYear+'-'+currentMonth+'-'+dateNums+'"><h1>' + dateNum + '</h1></td>';
		}
		calendarWrite += '</tr>';
	}		
	calendarWrite += '</tbody></table>';		
	$('#'+Prints).html(calendarWrite);
	$('#calendarTable thead tr th:first-child, #calendarTable tbody tr td:first-child').addClass('sunday')
	$('#calendarTable thead tr th:last-child, #calendarTable tbody tr td:last-child').addClass('saturday')
	var holidayCheck = $.get('../api/apiHoliday.php',{'year':currentYear,'month':currentMonth},function(data){
		if(data.totalCount != 0){
			$.each(data.holiday,function(){
				var matchTd = $('#'+this.holiday);
				if(this.enabled == "Y"){
					matchTd.addClass('sunday')
				}else{
					matchTd.addClass('saturday')
				}
			})
		}
	})
	if(useType == 'act'){
		dateAct();
	}
}

function pickerAct(){
	$('.cal').bind({
		click:function(){
			$('.picked').removeClass('picked');
			$('#datePicker').remove();
			$(this).addClass('picked');
			var pickDate = $(this).val();
			var calPrint = '<div id="datePicker"></div>'
			$(this).after(calPrint);
			CalenderAct('act','datePicker',pickDate);				
		}
	})
}