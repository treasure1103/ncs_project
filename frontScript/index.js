//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


// 정보 선언
var useApi = '../api/apiStudyStats.php';
var useVisitApi = '../api/apiVisit.php';
var useNoticeApi = '../api/apiBoard.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

//리스트 소팅
function listAct(page){
	var contents = '';
	$('#contents > h1').html('제목');

	if(loginUserLevel <= 4) { // 관리자 모드 접속 시
		//진행중인 수강 통계
		/*
		contents += '<div id="inProgress">';
		contents += '<h1>진행중인 수강 현황</h1>';
		contents += '<table><thead><tr>';
		contents += '<th style="70%;">수강기간</th>';
		contents += '<th style="10%">총과정수</th>';
		contents += '<th style="20%">중간평가채점 수 / 응시자<br />최종평가채점 수 / 응시자<br />과제채점 수/ 응시자</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		contents += '</div>';
		*/

		//방문자 통계
		contents += '<div id="visitCount">';
		contents += '<h1>방문자 통계</h1>';
		contents += '<table><thead><tr>';
		contents += '<th style="width:50%;">총 방문자수</th>';
		contents += '<th style="width:25%;">금일</th>';
		contents += '<th style="width:25%;">어제</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		contents += '</div>';

		// 현재 연도 수강 통계
		contents += '<div id="yearCount">';
		contents += '<h1>현재 연도 수강 통계 (전체 통계가 아닌 현재 연도에 수강내역이 있는 데이터로 산출) </h1>';
		contents += '<table><thead><tr>';
		contents += '<th style="width:30%">사업주 / 회원수</th>';
		contents += '<th style="width:15%;">과정</th>';
		contents += '<th style="width:30%;">총 수강 / 수료</th>';
		contents += '<th style="width:25%;">수료율</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		contents += '</div>';
/*
	} else if(loginUserLevel == 7) { // 교강사 접속 시 공지, 첨삭현황만 불러옴
		contents += '<div id="inProgress">';
		contents += '<h1>진행중인 첨삭 현황</h1>';
		contents += '<table><thead><tr>';
		contents += '<th style="70%;">수강기간</th>';
		contents += '<th style="10%">총과정수</th>';
		contents += '<th style="20%">중간평가채점 수 / 응시자<br />최종평가채점 수 / 응시자<br />과제채점 수/ 응시자</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		contents += '</div>';

	} else if(loginUserLevel == 8) { // 교육담당자
		contents += '<div id="inProgress">';
		contents += '<h1>진행중인 현황</h1>';
		contents += '<table><thead><tr>';
		contents += '<th style="70%;">수강기간</th>';
		contents += '<th style="10%">총과정수</th>';
		contents += '<th style="20%">중간평가채점 수 / 응시자<br />최종평가채점 수 / 응시자<br />과제채점 수/ 응시자</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		contents += '</div>';
*/
	} else { // 없음
		contents += '<div id="yearCount">';
		contents += '<h1>상단 수강관리를 클릭하세요.</h1>';
		contents += '</div>';
	}

	// 공지사항
	contents += '<div id="noticeBoard">';
	contents += '<h1>공지사항</h1>';
	contents += '<table><thead><tr>';
	contents += '<th class="left">제목 / 작성자</th>';
	contents += '<th style="width:100px;">작성일</th>';
	contents += '<th style="width:70px;">조회수</th>';
	contents += '</tr></thead><tbody>';
	contents += '</tbody></table>';
	contents += '</div>';
	
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	visitCountAJAX();
	yearCountAJAX();
	if (loginUserLevel == '7') {
		noticeBoardAJAX('6');
	} else {
		noticeBoardAJAX('1');
	}

	//inProgressAJAX();
}

function visitCountAJAX(){
	var listAjax = $.get(useVisitApi,function(data){
		totalCount = data.totalCount;
		var lists = '';

		if (totalCount != 0){
			lists += '<tr>';
			lists += '<td>'+data.totalEA+'</td>';
			lists += '<td>'+data.todayEA+'</td>';
			lists += '<td>'+data.yesterdayEA+'</td>';
			lists += '</tr>';

		}else{
			lists += '<tr><td class="notResult" colspan="4">내역이 없습니다.</td></tr>'
		}

		$('#visitCount tbody').html(lists);
	}) 
}

function yearCountAJAX(){
	var listAjax = $.get(useApi,{'year':'now'},function(data){
		totalCount = data.totalCount;
		var lists = '';

		if (totalCount != 0){
			lists += '<tr>';
			lists += '<td>'+data.companyCount+'&nbsp;/&nbsp;'+data.userCount+'</td>';
			lists += '<td>'+data.contentsCount+'</td>';
			lists += '<td>'+data.studyCount+'&nbsp;/&nbsp;'+data.passCount+'</td>';
			lists += '<td>'+eval(data.passRate).toFixed(1)+'%</td>';
			lists += '</tr>';

		}else{
			lists += '<tr><td class="notResult" colspan="6">내역이 없습니다.</td></tr>'
		}

		$('#yearCount tbody').html(lists);
	}) 
}

function noticeBoardAJAX(boardCode){
	var listAjax = $.get(useNoticeApi,{'boardCode':boardCode,'list':'5'},function(data){
		totalCount = data.totalCount;
		var lists = '';
		if (totalCount != 0){
			$.each(data.board, function(){
				lists += '<tr>';
				lists += '<td class="left"><strong><a href="06_board.php?locaSel=0903&boardCode='+boardCode+'&seq='+this.seq+'">'+this.subject+'</a></strong></td>';
				lists += '<td>'+this.inputDate.substr(0,10)+'</td>';
				lists += '<td>'+this.hits+'</td>';
				lists += '</tr>';
			})

		}else{
			lists += '<tr><td class="notResult" colspan="4">내역이 없습니다.</td></tr>'
		}

		$('#noticeBoard tbody').html(lists);
	}) 
}

function inProgressAJAX(){
	var listAjax = $.get(useApi, function(data){
		totalCount = data.totalCount;
	var lists = '';
/*
		if (totalCount != 0){
			$.each(data.study, function(){
				lists += '<tr>';
				lists += '<td style="background:#f8f8f8;">'+this.lectureStart+' ~ '+this.lectureEnd+'<br />~ '+this.tutorDeadline+' 까지 첨삭 완료</td>';
				lists += '<td style="background:#f8f8f8;">'+this.studyCount+'</td>';
				lists += '<td style="background:#f8f8f8;">'+this.midComplete+'&nbsp;/&nbsp;'+this.midSubmit+'<br />'+this.testComplete+'&nbsp;/&nbsp;'+this.testSubmit+'<br />'+this.reportComplete+'&nbsp;/&nbsp;'+this.reportSubmit+'</td>';
				lists += '</tr>';

				$.each(this.company, function(){
					lists += '<tr>';
					lists += '<td>'+this.companyName+'</td>';
					lists += '<td>'+this.studyCount+'</td>';
					lists += '<td>'+this.midComplete+'&nbsp;/&nbsp;'+this.midSubmit+'<br />'+this.testComplete+'&nbsp;/&nbsp;'+this.testSubmit+'<br />'+this.reportComplete+'&nbsp;/&nbsp;'+this.reportSubmit+'</td>';
					lists += '</tr>';
				})
			})


		}else{
			lists += '<tr><td class="notResult" colspan="6">내역이 없습니다.</td></tr>'
		}
*/
		lists += '<tr><td class="notResult" colspan="6">수강관리에서 확인하실 수 있습니다.</td></tr>'
		$('#inProgress tbody').html(lists);
	}) 
}