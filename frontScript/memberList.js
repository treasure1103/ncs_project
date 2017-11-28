//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트 소팅
function listAct(page){
	
	//상단 액션 부분	
	var actionArea = '';
	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
	if(loginUserLevel < 5) {
		//actionArea += '<button type="button" class="fRight">엑셀출력하기</button>';
		actionArea += '<button type="button" class="fRight" onClick="writeAct()">회원등록</button>';
		//actionArea += '<button type="button" class="fRight">선택메일</button>';
		//actionArea += '<button type="button" class="fRight">전체메일</button>';
	}
    actionArea += '<select name="searchType">';	
    actionArea += '<option value="userName">이름</option>';
    actionArea += '<option value="userIDLike">아이디</option>';
    actionArea += '<option value="mobile">연락처</option>';
    actionArea += '<option value="companyName">사업주</option>';
    actionArea += '</select>&nbsp;';
    actionArea += '<input type="text" name="searchValue" />&nbsp;';
	actionArea += '<button type="button" onClick="searchAct()">검색하기</button></form>';
	actionArea += '</form>'
	actionArea += '</div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	//contents += '<th style="width:50px;"><input type="checkbox" id="checkAll" onChange="checkboxAllCheck(\'checkAll\')" /><label for="checkAll"></label></th>';
	contents += '<th style="width:60px;">번호</th>';
	contents += '<th>아이디/이름</th>';
	contents += '<th style="width:150px;">사업주</th>';
	contents += '<th style="width:120px;">생년월일/성별</th>';
	contents += '<th style="width:200px;">연락처/E-mail</th>';
	contents += '<th style="width:120px;">가입일</th>';
	contents += '<th style="width:120px;">최근 로그인</th>';
	contents += '<th style="width:120px;">비번 초기화</th>';
	contents += '<th style="width:80px;">수정</th>';
	if(loginUserLevel < 5){ 
	  contents += '<th style="width:80px;">삭제</th>';
	}
	contents += '</tr></thead><tbody>'	;
	contents += '<tr><td class="notResult" colspan="10">검색어를 입력해주세요.</td></tr>'	;
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	if(userLevel != '' && userLevel != '9') {
		ajaxAct();
	}
	
}

function ajaxAct(sortDatas){
	loadingAct();
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var i = totalCount;
		if(page != 1){
			i = totalCount - ((page-1)*listCount)
		}
		if (totalCount != 0){
			$.each(data.member,  function(){
				if(this.userDelete.userDelete != 'Y'){
					lists += '<tr>';
					//lists += '<td><input type="checkbox" name="check['+this.seq+']" id="check'+this.seq+'" class="checkAll" /><label for="check'+this.seq+'"></label></td>';
					lists += '<td>'+i+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\','+this.seq+',\''+this.userID+'\')" style="cursor:pointer;">'+this.userID+'<br />'+this.userName+'</td>';
					lists += '<td>'+this.company.companyName+'</td>';
					lists += '<td>'+this.birth+'<br />'+this.sexName+'</td>';
					//lists += '<td>'+this.userLevel.userGrade+'</td>';
					lists += '<td>'+this.mobile01+'-'+this.mobile02+'-'+this.mobile03+'<br />';
					if(this.email01 != '' || this.email01 != null){
						lists += this.email01+'@'+this.email02;
					}else{
						lists += '미등록';
					}
					lists += '<td>';
					if(this.inputDate != null){
						lists += this.inputDate.substr(0,10)
					}else{
						lists += '-';
					}
					lists += '</td>';
					lists += '<td>';
					if(this.loginTime != null){
						lists += this.loginTime.substr(0,10)
					}else{
						lists += '-';
					}
					lists += '</td>';

					if(loginUserLevel < '5'){
						lists += '<td><button type="button" onClick="pwdReset(\''+this.seq+'\',\''+this.birth.substr(2,4)+'\')">초기화</button></td>';
					}
					if(loginUserLevel <= this.userLevel){
						lists += '<td><button type="button" onClick="writeAct('+this.seq+')">수정</button></td>';
					}
					if(loginUserLevel < '5'){
						lists += '<td><button type="button" onClick="memDelete(\''+this.userID+'\')">삭제</button></td>';
					}

					lists += '</tr>';
					i--;
				}
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists)
		pagerAct();
		loadingAct();
	})
}

function memDelete(memID){
	if(confirm('회원정보를 삭제하시겠습니까? 삭제 후 복구하실 수 없습니다.')) {
		$.ajax({
			url: useApi,
			type:'DELETE',
			data:'memDel=Y&memID='+memID,
			dataType:'text',
			success:function(data){
				if(data == 'success'){
					alert('삭제되었습니다.');
				} else if(data == 'no') {
					alert('수강내역이 존재합니다. 수강내역이 없는 회원만 삭제할 수 있습니다.');
				}
				ajaxAct();
			},
			fail:function(){
				alert('오류가 발생하였습니다.')
			}
		})
	}
}