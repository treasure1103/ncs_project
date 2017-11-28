//리스트액션
function listAct(page){
	if( modes != 'writeMode'){
		var actionArea = '';
		actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
		if(pageMode != 'userPage'){
			actionArea += '<select name="searchType">';
			actionArea += '<option value="userName">이름</option>';
			actionArea += '<option value="subject">제목</option>';
			actionArea += '</select>&nbsp;';
		}
		actionArea += '<input type="text" name="searchValue" />&nbsp;<button type="button" onClic="searchAct()">검색하기</button>';
		actionArea += '</form>';
		if(boardType == 'recruit'){
			actionArea += '<div class="recruitUseCHK"><strong class="price">채용공고 사용여부</strong>&nbsp;&nbsp;&nbsp;&nbsp;';
			actionArea += '<input type="radio" name="useRecruit" id="useRecruit" value="Y" checked="checked" /><label for="useRecruit"> 사용</label>';
			actionArea += '<input type="radio" name="useRecruit" id="uselessRecruit" value="N" /><label for="uselessRecruit">채용공고 사용</label></div>';
			actionArea += '<input type="hidden" name="useRecruitseq">'
		}
		actionArea += '</div>';
		
		if(pageMode == 'userPage'){
			$('#titleArea').after(actionArea);
		}else{
			$('#contents > h1').after(actionArea);
			$('#contents > h1').html('1:1상담');
		}
				
		var contents = '';
		contents += '<table><thead><tr>';
		contents += '<th style="width:60px;">번호</th>';
		if(pageMode !='userPage' && pageMode !='studyCenterPage'){
			contents += '<th style="width:100px;">이름</th>';
			contents += '<th style="width:140px;">연락처</th>';
		}
		contents += '<th class="left">상담제목</th>';
		contents += '<th style="width:120px;">등록일</th>';
		contents += '<th style="width:80px;">답변</th>';
		contents += '</tr></thead><tbody>';		
		contents += '</tbody></table>';
		if(pageMode == 'userPage' || pageMode == 'studyCenterPage'){
		  contents += '<div class="btnArea"><button type="button" onClick="writeAct()" class="fRight">문의하기</button></div>';
		}
		$('#wrap').removeAttr('class');
		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSList');
		$('#contentsArea').html(contents);
		ajaxAct();
	}else{
		writeAct();
	}
}

function ajaxAct(searchSort){
	searchSort = searchSort ? searchSort : ''
	var type = '';
	if(pageMode == 'adminPage'){
		type='admin';
	}
	var listAjax = $.get(useApi,'viewType='+type+'&page='+page+'&list='+listCount+'&boardType='+boardType+searchSort,function(data){
		totalCount = data.totalCount;
		var lists = '';
		var i = '';
		if (page != 1){
			i = (page-1)*listCount
		}else{
			i = 1;
		}
		if (totalCount != 0){
			$.each(data.consult, function(){
				lists += '<tr>';
				lists += '<td>'+i+'</td>';
				if(pageMode != 'userPage' && pageMode !='studyCenterPage'){
					lists += '<td>'+this.userName+'</td>';
					lists += '<td>'+this.phone01+'-'+this.phone02+'-'+this.phone03+'</td>';
				}
				lists += '<td class="Left" onClick="viewAct('+this.seq+')" style="cursor:pointer;">'+this.subject+'</td>';
				lists += '<td>'+this.inputDate.substr(0,10)+'</td>';
				var status = '';
				if(this.status == 'W'){
					status = '대기'
				}else if(this.status == 'D'){
					status = '보류'
				}else if(this.status == 'C'){
					status = '완료'
				}
				lists += '<td>'+status+'</td>';
				lists += '</tr>';
				i++
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">아직 등록된 목록이 없습니다.</td></tr>';
		}
		$('.BBSList > table > tbody').html(lists)
		pagerAct();
	})
}