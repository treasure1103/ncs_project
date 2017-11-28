function listAct(page){
	//상단 액션 부분	
	var actionArea = '';
	
    var today = new Date();
    var year= today.getFullYear();
    var mon = (today.getMonth()+1)>9 ? ''+(today.getMonth()+1) : '0'+(today.getMonth()+1);
    var day = today.getDate()>9 ? ''+today.getDate() : '0'+today.getDate();
    var todayDate = year + '-' + mon + '-' + day;
    var searchDate = todayDate;

	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
    actionArea += '<div>'
    actionArea += '발신 일자 : <div class="datePicker"><input type="text" name="searchDate" class="cal" value="'+searchDate+'" readonly="readonly" /></div>';
    actionArea += '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="messageType" value="smt" id="smt" checked="checked"><label for="smt">단문</label>';
    actionArea += '<input type="radio" name="messageType" value="mmt" id="mmt"><label for="mmt" style="margin-right:10px;">장문</label>';
    actionArea += ' / 이름 : <input type="text" name="userName">';
    actionArea += '</div>'
    actionArea += '<button type="submit" class="allWidth">검색</button>';
	actionArea += '</form>';
	actionArea += '</div>';
	$('#contents > h1').after(actionArea);
    pickerAct();//데이트피커 사용

	//게시물 소팅부분
	var contents = '';
	contents += '<table>';
    contents += '<colgroup>';
    contents += '<col style="width:60px">';
    contents += '<col style="width:140px">';
	contents += '<col style="width:200px">';
    contents += '<col style="width:100px">';
    contents += '<col style="width:120px">';
    contents += '<col>';
    contents += '<col style="width:150px">';
    contents += '<col style="width:100px">';
    contents += '</colgroup>'
    contents += '<thead><tr>';
	contents += '<th>번호</th>';
    contents += '<th>실패사유</th>';
	contents += '<th>소속</th>';
    contents += '<th>이름</th>';
	contents += '<th>연락처</th>';
    contents += '<th>내용</th>';
    contents += '<th>발신시간</th>';
	contents += '</tr></thead><tbody>'	;
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
}

function ajaxAct(sortDatas){
    sortDatas = sortDatas ? sortDatas : '';
    if(sortDatas != ''){
		sortData = sortDatas
	}
    $.get('../api/apiSMSLog.php','&page='+page+'&list='+listCount+sortData,function(data){
        var lists = '';
        totalCount = data.totalCount;
		var i = totalCount;
		if(page != 1){
			i = totalCount - ((page-1)*listCount)
		}
        if(totalCount != 0){ 
            $.each(data.sendLog, function(){
                lists += '<tr>';
                lists += '<td>'+i+'</td>';
                lists += '<td>'+this.errorMessage+'</td>';
				lists += '<td>'+this.companyName+'</td>';
                lists += '<td>'+this.userName+'</td>';
				lists += '<td>'+this.recipient_num+'</td>';
                lists += '<td class="left">'+this.content+'</td>';
                lists += '<td>'+this.date_client_req+'</td>';
                lists += '</tr>';
                i--;
            })
        }else{
            lists = '<tr><td colspan="7">검색 결과가 없습니다.</td></tr>';
        }
        $('.BBSList tbody').html(lists);
        pagerAct();

    })
}
