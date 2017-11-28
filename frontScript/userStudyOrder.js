//보드 정보 선언
seq = seq ? seq : '' ;
var useApi = '../api/apiUserOrder.php';
var orderApi = '../api/apiOrder.php';
var detailApi = '../api/apiContents.php';
var chapterApi = '../api/apiChapter.php';
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
var totalCount = ''; //전체 페이지 카운트

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
	actionArea += '<input type="hidden" name="searchType" value="contentsName" />';
    actionArea += '<input type="text" name="searchValue" />&nbsp;';
	actionArea += '<button type="button" onClick="searchAct()">검색하기</button></form>';
	actionArea += '</form>'
	actionArea += '&nbsp</div>';
	$('#contentsArea').before(actionArea);	
	//게시물 소팅부분
	var contents = '';
	contents += '<ul class="lectureList">';
	contents += '</ul>';
	$('#wrap').removeAttr('class');
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
}

function ajaxAct(listPage,sortData){
	listPage = listPage ? listPage : page ;
	page = listPage;
	sortData = sortData ? sortData : '';
	var listAjax = $.get(useApi,'enabled=Y&page='+page+'&list='+listCount+sortData,function(data){
		imageURL = data.previewImageURL;
		totalCount = data.totalCount;
		
		//var nowTime = '2016-02-25'
		var nowTime = data.nowTime;		
		var nowYear = nowTime.substr(0,4)
		var nowMonth = nowTime.substr(5,2)
		var nowDay = nowTime.substr(8,2)
		nowTime = new Date(nowMonth+'/'+nowDay+'/'+nowYear);
		nowTime = nowTime.setDate(nowTime.getDate())
		
		$('#titleArea > h3 > strong').html(totalCount)
		var lists = '';
		var i = 1;
		if(page != 1){
			i = totalCount - ((page-1) * listCount);
		}else{
			i = totalCount;
		}
		if (totalCount != 0){
			$.each(data.order, function(){
			
				var startTime = this.lectureStart;
				var startYear = startTime.substr(0,4)
				var startMonth = startTime.substr(5,2)
				var startDay = startTime.substr(8,2)
				startTime = new Date(startMonth+'/'+startDay+'/'+startYear);
				
				var refoundTime = startTime.setDate(startTime.getDate() - 3);//수강시작 ~일까지 취소가능
				var refoundYear = new Date(refoundTime).getFullYear();
				var refoundMonth = new Date(refoundTime).getMonth() + 1;
				if(refoundMonth < 10){
					refoundMonth = '0'+refoundMonth;
				}
				var refoundDay = new Date(refoundTime).getDate();
				if(refoundDay < 10){
					refoundDay = '0'+refoundDay;
				}
				var refoundDate = refoundYear +'-'+ refoundMonth +'-'+ refoundDay;
				//var refoundTimes = refoundTime.getDate()
				
				lists += '<li>';
				
				//버튼영역
				lists += '<button type="button" onclick="viewAct(\''+this.contentsCode+'\')"><img src="../images/lecture/img_button_detail.png" alt="상세보기이미지"><br />상세보기</button>'
				if(refoundTime >= nowTime){
					lists += '<button type="button" onClick="deleteData(\''+this.seq+'\')"><img src="../images/lecture/img_button_submit.png" alt="신청취소이미지"><br />변경 및 취소</button>'
				}
				
				//썸네일
				if(this.previewImage != null){
					lists += '<img src="'+imageURL+this.previewImage+'" onclick="viewAct(\''+this.contentsCode+'\')" alt="'+this.contentsName+'" />';
				}else{
					lists += '<img src="../images/lecture/img_noimage.png" onclick="viewAct(\''+this.contentsCode+'\')" alt="이미지준비중" />';
				}
				//과정명
				lists += '<h3>교육과정 > 경영.리더십과정</h3>'
				lists += '<h1 onclick="viewAct(\''+this.contentsCode+'\')">'+this.orderName+'</h1>'
				if(this.mobile == 'Y'){
					lists += '<h5>모바일 학습 가능</h5>'
				}
				lists += '<h4>수강 신청일 : '+this.orderDate+'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;취소 가능일 : <strong class="red">'+refoundDate+'</strong>까지</h4>'
				lists += '<h4>수강기간 : <strong class="blue">'+this.lectureStart+'&nbsp;~&nbsp;'+this.lectureEnd+'</strong></h4>';
			})
		} else {
			lists += '<li class="noList">과정이 없습니다.</li>';
		}
		$('.lectureList').html(lists);
		pagerAct();
	})
}


function viewAct(contentsCode){
	//상단 액션 부분	
	$('.searchArea').remove();	
	//게시물 소팅부분
	var views = '';
	$.get(detailApi,{'contentsCode':contentsCode},function(data){
		imageURL = data.previewImageURL;
		$.each(data.contents, function(){
			views += '<div class="summuryArea">';
			if(this.previewImage != '' && this.previewImage != null){
				views += '<img src="'+imageURL+this.previewImage+'" alt="'+this.contentsName+'" />';
			}else{
				views += '<img src="/images/lecture/img_noimage.png" alt="이미지가 준비중입니다." />'
			}
			views += '<h5>리더십<img src="../images/global/icon_triangle.png" alt="화살표" />인문학 리더십</h5>'
			views += '<h1>'+this.contentsName+'</h1>';
			views += '<h2>총 <strong>'+this.chapter+'</strong>차시 / <strong>'+this.contentsTime+'시간</strong> 교육과정</h2>';
			views += '<h3><strong>'+this.professor+'</strong> 강사</h3>';
			views += '<button type="button" onclick="studyPop(\''+this.contentsCode+'\');">미리보기</button>'
			views += '</div>';
			
			//수료기준
			views += '<h1>수료기준 및 수강정원</h1>'
			views += '<table><tr>';
			views += '<th>수강정원</th>';
			views += '<th>총 진도율</th>';
			views += '<th>중간평가</th>';
			views += '<th>최종평가</th>';
			views += '<th>과제</th>';
			views += '</tr><tr>';
			views += '<td rowspan="2"><strong>'+this.limited+'</strong>명</td>';
			views += '<td rowspan="2"><strong>'+this.passProgress+'</strong>% 이상</td>';
			views += '<td>총&nbsp;<strong>';
			if(this.totalPassMid != 0){
				views += this.totalPassMid+'</strong>점 / <strong>'+this.midRate+'</strong>% 반영';
			}
			views += '</td>';
			views += '<td>총&nbsp;<strong>';
			if(this.totalPassTest != 0){
				views += this.totalPassTest+'</strong>점 / <strong>'+this.testRate+'</strong>% 반영';
			}
			views += '</td>';
			views += '<td>';
			if(this.totalPassReport != 0){
				views += '총&nbsp;<strong>'+this.totalPassReport+'</strong>점 / <strong>'+this.reportRate+'</strong>% 반영';
			} else {
				views += '없음';
			}
			views += '</td>';
			views += '</tr><tr>';
			views += '<td colspan="3">반영된 평가, 과제 점수 합산 <strong>'+this.passScore+'</strong>점 이상</td>';
			views += '</tr></table>';
			
			//교육비
			views += '<h1>교육비 안내</h1>'
			views += '<table><tr>';
			views += '<th>일반교육비</th>';
			views += '<th>우선지원 기업</th>';
			views += '<th>대규모<br />(1000인 미만)</th>';
			views += '<th>대규모<br />(1000인 이상)</th>';
			views += '</tr><tr>';
			views += '<td><strong>'+toPriceNum(this.price)+'</strong>원</td>';
			views += '<td><strong>'+toPriceNum(this.rPrice01)+'</strong>원</td>';
			views += '<td><strong>'+toPriceNum(this.rPrice02)+'</strong>원</td>';
			views += '<td><strong>'+toPriceNum(this.rPrice03)+'</strong>원</td>';
			views += '</tr></table>';
			
			//교육교재안내
			if(this.bookIntro != '' && this.bookIntro != null){
				views += '<h1>교재정보</h1>'
				views += '<div class="bookInfo">'
				if(this.bookImage != '' && this.bookImage != null){
					views += '<img src="'+bookURL+this.bookImage+'" alt="교재이미지">';
				}else{
				views += '<img src="/images/lecture/img_nobooks.png" alt="이미지가 준비중입니다." />'
				}
				views += '<h1>'+this.bookIntro+'</h1>'
				views += '</div>';
			}
			//교육소개관련
			if(this.intro != '' && this.intro != null){
				views += '<h1>과정소개</h1>';
				views += '<div class="infoArea">';
				views += this.intro.replace(/\n/g,'<br />');
				views += '</div>';
			};
			if(this.target != '' && this.target != null){
				views += '<h1>교육대상</h1>';
				views += '<div class="infoArea">';
				views += this.target.replace(/\n/g,'<br />');;
				views += '</div>';
			};
			if(this.goal != '' && this.goal != null){
				views += '<h1>교육목표</h1>';
				views += '<div class="infoArea">';
				views += this.goal.replace(/\n/g,'<br />');;
				views += '</div>';
			};
			//목차관련
			views += '<h1>교육목차</h1>';
			views += '<ol></ol>';
			views += '<div class="btnArea">';
			views += '<button type="button" onclick="listAct(page)">목록으로</button>'
			views += '</div>';
		})
		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('lectureDetail');
		$('#contentsArea').html(views);		
	}).done(function(data){
		var contentsCode = ''
		$.each(data.contents, function(){
			$.get(chapterApi,{'contentsCode':this.contentsCode},function(data){
				var chapterWrite = '';
				$.each(data.chapter,function(){
					chapterWrite += '<li>'+this.chapterName+'</li>'
				})
				$('.lectureDetail ol').html(chapterWrite)
			})
		})		
	})
}


function studyPop(contentsCode){
	popupAddress = 'http://oneedu.co.kr/player/popupConfirm.php?contentsCode='+contentsCode+'&chapter=1';
	window.open(popupAddress,"학습창","menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","study")
}

function deleteData(delSeq){
	if(confirm('정말 취소하시겠습니까? 교육신청 기간에는 얼마든지 다시 신청이 가능합니다.') == true){
		$.ajax({
			url:useApi,
			type:'DELETE',
			data:{'seq':delSeq},
			dataType:'json',
			success: function(data){
				if(data.result == 'success'){
					alert('수강신청한 과정이 취소 처리되었습니다.');
					ajaxAct();
				}else{
					alert(data.result);
				}		
			},
			fail: function(){
				alert('시스템에 문제가 있습니다. 관리자에 문의하세요')
			}
		})
	}
		
}