//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트 소팅
var useApi = '../api/apiSessionList.php';
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
userLevel = userLevel ? userLevel :9;

function listAct(page){
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:100px;">번호</th>';
	contents += '<th>아이디</th>';
	contents += '<th>이름</th>';
	contents += '<th>접속IP</th>';
	contents += '<th>접속한 시간</th>';
	contents += '<th>강제 로그아웃</th>';
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
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		var lists = '';
		var i = totalCount;
		if(page != 1){
			i = totalCount - ((page-1)*listCount)
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			$.each(data.session,  function(){
				lists += '<tr>';
				lists += '<td>'+i+'</td>';
				lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.userID+'\')" style="cursor:pointer;">'+this.userID+'</td>';
				lists += '<td>'+this.userName+'</td>';
				lists += '<td>'+this.ip+'</td>';
				lists += '<td>'+this.inputDate+'</td>';
				lists += '<td><button type="button" onClick="forcedLogout(\''+this.userID+'\')">로그아웃</button></td>';
				lists += '</tr>';
				i--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="20">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="20">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists)
		//pagerAct();
	})
}

  function forcedLogout(userID){
		  if(confirm("강제로 로그아웃합니까?")){
			  $.ajax({
				  url: useApi,
				  type:'POST',
				  data:'userID='+userID,
				  dataType:'text',
				  success:function(data){
					  alert('강제 로그아웃 처리되었습니다.');
					  location.reload();
				  },
				  fail:function(){
					  alert('실패하였습니다.');
				  }
			  })
			}
  }