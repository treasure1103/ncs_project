//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//보드 정보 선언
var useApi = '../api/apiSurvey.php';
var seq = seq ? seq : '' ;
var totalCount = '';

function listAct() {
	writePrint();
}

	function writePrint(){
		var writes ='';
		
			//등록차시 불러오기
			writes += '<h2>등록 정보 보기</h2>'
			writes += '<div class="BBSList">'
			writes += '<table class="listArea"><thead>';
			writes += '<th style="width:60px">순번</th>';
			writes += '<th style="width:80px;">설문유형</th>';
			writes += '<th>설문내용</th>';
			//writes += '<th style="width:180px;">복사/삭제</th>';
			writes += '<th style="width:180px;">삭제</th>';
			writes += '</thead><tbody>';
			writes += '</tbody></table>';
			writes += '</div>';

			//개별등록,수정
			writes += '<h2>개별등록/수정</h2>'
			writes += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
			writes += '<input type="hidden" name="seq" />'
			writes += '<ul>';
			
				//문제번호,문제분류
				writes += '<li>';
				writes += '<div class="halfDiv">'
				writes += '<h1>순번</h1>';
				writes += '<input type="tel" name="orderBy" class="year">';
				writes += '</div>'
				writes += '<div class="halfDiv">'
				writes += '<h1>설문유형</h1>';
				writes += '<select name="surveyType" onchange="changeInput(this)"><option value="A">선택형</option><option value="B">작성형</option></select>';
				writes += '</div>'
				writes += '</li>';
				//설문내용
				writes += '<li><h1>설문내용<br /><br />';
				writes += '</h1>'
				//writes += '<div class="textInputs">';
				//writes += '<div id="exam" class="examView"></div>';
				//writes += '</div>'
				writes += '<textarea name="exam"></textarea>';
				writes += '</li>';
				//보기
				for(i=1; i<6 ; i++){
					writes += '<li class="answerA"><h1>보기 '+i+'.<br />';
					writes += '</h1>'
					//writes += '<div class="textInputs">';
					//writes += '<div id="example0'+i+'" class="examView" "></div>';
					//writes += '</div>';
					writes += '<textarea name="example0'+i+'"></textarea>';
					writes += '</li>';
				}

			writes += '</ul>';
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="listAct()">목록보기</button>';
			writes += '<button type="button" onClick="resetInput()">초기화</button>';
			writes += '<button type="button" onClick="sendData(\''+useApi+'\',\'writeform\');matchingList();">등록하기</button>';
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			writes += '</form>';
			
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		
		matchingList();
		var	mustInput = '&nbsp;&nbsp;<strong class="price">(*)</strong>';
		$('.mustCheck > h1').append(mustInput)//필수요소 사용
	}

function changeInput(vals){
	vals = vals.value;
	if(vals == 'A'){
		$('.answerB').css('display','none')
		$('.answerA').css('display','list-item')
	}else if(vals != 'A'){
		$('.answerA').css('display','none')
		$('.answerB').css('display','list-item')
	}
}

function resetInput(){
	if($('.writeform input').prop('name') != 'contentsCode'){
		$('.writeform input[type="text"], .writeform input[type="tel"], .writeform input[name="seq"]').val('');
		$('.writeform textarea, .writeform div.examView').html('');
		$('input[type="checkbox"]').prop('checked',false);
	}
	$('.writeform button[type="submit"]').html('등록하기')
}

function matchingList(){
	var matchingApi = '';
	matchingApi = useApi;

	var writeAjax = $.get(matchingApi,function(data){
		var sortCount = data.totalCount;
		var chapterLists = '';

		if(sortCount != 0){
				$.each(data.survey,function(){		
					chapterLists += '<tr class="line'+this.seq+'">';
					chapterLists += '<td>'+this.orderBy+'</td>';
					chapterLists += '<td>';
					if(this.surveyType == 'A'){
						chapterLists += '선택형';
					}else if(this.surveyType == 'B'){
						chapterLists += '작성형';
					}
					chapterLists += '</td>';
					chapterLists += '<td class="left" onClick="contentsAct('+this.seq+',\'modify\')" style="cursor:pointer;">'+this.exam+'</td>';
					chapterLists += '<td>';
					//chapterLists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'modify\')">수정</button>&nbsp;/&nbsp;';
					//chapterLists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'copys\')">복사</button>&nbsp;/&nbsp;';
					chapterLists += '<button type="button" onClick="deleteData(\''+useApi+'\','+this.seq+');matchingList();">삭제</button>';
					chapterLists += '</td>';
					chapterLists += '</tr>'	;
				})
		
		}else{
			chapterLists += '<tr><td colspan="20">등록된 정보가 없습니다.</td></tr>';
		}
		$('.BBSList table.listArea tbody').html(chapterLists);
	})
}
//챕터개별 등록,수정,복사
function contentsAct(seqNum,action){
	var matchingApi = '';
		matchingApi = useApi;

	$.get(matchingApi,{'seq':seqNum},function(data){
			$.each(data.survey, function(){
				if(action == 'copy'){
					var copyDate = ''
					copyDate += 'seq='+this.seq+'&';
					copyDate += 'orderBy='+this.ordeyBy+'&';
					copyDate += 'surveyType='+this.surveyType+'&';
					copyDate += 'exam='+this.exam.replace(/&/g,'%26')+'&';
					copyDate += 'example01='+this.example01.replace(/&/g,'%26')+'&';
					copyDate += 'example02='+this.example02.replace(/&/g,'%26')+'&';
					copyDate += 'example03='+this.example03.replace(/&/g,'%26')+'&';
					copyDate += 'example04='+this.example04.replace(/&/g,'%26')+'&';
					copyDate += 'example05='+this.example05.replace(/&/g,'%26')+'&';
					copyAct();
				}else if(action == 'modify'){
					$('.writeform input[name="seq"]').val(this.seq);
					$('.writeform input[name="orderBy"]').val(this.orderBy);
					$('.writeform select[name="surveyType"]').val(this.surveyType);
					$('.writeform textarea[name="exam"]').val(this.exam);
					//$('#exam').html(this.exam);
					if(this.surveyType == "A"){
						$('input[type="checkbox"]').prop('checked',false);
						$('textarea[name="answer"]').html('');
						$('.answerA').css('display','list-item');
						$('.answerB').css('display','none');
						$('textarea[name="example01"]').html(this.example01);
						$('textarea[name="example02"]').html(this.example02);
						$('textarea[name="example03"]').html(this.example03);
						$('textarea[name="example04"]').html(this.example04);
						$('textarea[name="example05"]').html(this.example05);
						$('#answer'+this.answer).prop('checked',true);
					}else{
						$('.answerA').css('display','none');
						$('.answerB').css('display','list-item');
						$('input[type="checkbox"]').prop('checked',false);
						$('textarea[name="answer"]').html(this.answer);
					}
				}
			})

	})
	function copyAct(){
		$.ajax({
			url:matchingApi,
			data:copyDate,
			type:'POST',
			success:function(){
				alert('복사가 완료되었습니다.')
				matchingList(codes, contypes);
			}
		})
	}
	if(action == 'delete'){
		$.ajax({
			url:matchingApi,
			data:'seq='+seqNum,
			type:'DELETE',
			success:function(){
				alert('삭제되었습니다.')
			},
			done:function(){
				chapterList(contentsCode)
			}
		})
	}
}