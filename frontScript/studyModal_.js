var testApi = '../api/apiStudyTest_.php';
var reportApi = '../api/apiStudyReport.php';
var surveyApi = '../api/apiSurvey.php';
var surveyAnswerApi = '../api/apiSurveyAnswer.php';
var examList = 2;
var examPage = 1;
var i = 1;
var examEndTime = '';
var timer = '';
var setClockfunction = '';

function openStudyModal(types,contentsCode,lectureOpenSeq){
	if(typeof(contentsCode) == 'object'){
		contentsCode = $(contentsCode).parents('form').children('input[name="contentsCode"]').val();
	}
	if(typeof(lectureOpenSeq) == 'object'){
		lectureOpenSeq = $(lectureOpenSeq).parents('form').children('input[name="lectureOpenSeq"]').val();
	}
	if(types != 'final'){
		agreeModal(types,contentsCode,lectureOpenSeq);
	}else{
		$.get(chapterApi,{'contentsCode':contentsCode,'lectureOpenSeq':lectureOpenSeq},function(data){
			if(data.survey != 'Y'){
				var studyModals = '';
				$('body').css('overflow','hidden');
				studyModals += '<div id="screenModal" style="display:none;">';
				//타이틀 노출
				studyModals += '<div class="titleArea">';
				studyModals += '<div>';
				studyModals += '<img src="../images/study/img_test02.png" />';
				studyModals += '<h1>최종평가</h1>';
				studyModals += '<h2 class="contentsName">설문조사</h2>';
				studyModals += '<button type="button" onClick="studyModalClose();"><img src="../images/study/btn_modalclose.png" /></button>';
				studyModals += '</div>';
				studyModals += '</div>';
				
				$.get(surveyApi,{},function(data){
					//주의사항
					studyModals += '<div class="cautionTest">';
					studyModals += '<div class="textArea">';
					studyModals += '<h1>설문조사입니다.</h1>';
					studyModals += '<p>수강한 과정에 대한 <strong>설문조사</strong> 입니다.<br />시험은 <strong>설문조사 후 응시가능</strong>하며, 설문시간은 <strong>시험시간에 미포함 되며</strong> 시험 결과와는 무관합니다.</p>';
					studyModals += '</div>';
					studyModals += '</div>';
					studyModals += '<div class="surveyArea">';
					studyModals += '<form class="surveyForm">';
					$.each(data.survey, function(){
						studyModals += '<input type="hidden" name="seq[]" value="'+this.seq+'">';
						studyModals += '<input type="hidden" name="surveyType[]" value="'+this.surveyType+'">';
						studyModals += '<input type="hidden" name="lectureOpenSeq" value="'+lectureOpenSeq+'">';
						studyModals += '<input type="hidden" name="contentsCode" value="'+contentsCode+'">';
						studyModals += '<h1>설문 '+this.orderBy+'</h1>';
						studyModals += '<h2>'+this.exam+'</h2>';
						if(this.surveyType=='A'){ // 객관식
							studyModals += '<ol>';
							for(i=1; i<6 ; i++){
								if(typeof(eval('this.example0'+i)) != 'undefined') {
									studyModals += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example0'+i+this.seq+'"  value="'+i+'" />';
									studyModals += '<label for="example0'+i+this.seq+'">'+i+'.&nbsp;'+eval('this.example0'+i)+'</label></li>';
								}
							}					
							studyModals += '</ol>';
						} else if(this.surveyType=='B'){ // 단답형
							studyModals += '<textarea name="userAnswer'+this.seq+'"></textarea>';
						}
					})
					studyModals += '</form>';
					studyModals += '</div>'; //문제종료
					studyModals += '<div class="btnArea">';
					studyModals += '<button type="button" onclick="submitSurvey(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\')"><img src="../images/study/btn_lastsubmit.png" alt="최종제출" /></button>';
					studyModals += '</div>';
					studyModals += '</div>';
					$('#footer').after(studyModals);
					$('#screenModal').fadeIn('fast');
				})
			}else{
				agreeModal(types,contentsCode,lectureOpenSeq);
			}
		})
	}
}

function submitSurvey(types,contentsCode,lectureOpenSeq){
	var surveySerial = $('.surveyForm').serialize();
	$.post(surveyAnswerApi,surveySerial,function(data){
		if(data.result == 'success'){
			$('#screenModal').remove();
			agreeModal(types,contentsCode,lectureOpenSeq);
		}else{
			alert(data.result)
		}
	})	
}

function agreeModal(types,contentsCode,lectureOpenSeq){
	$('body').css('overflow','hidden');
	//types : 중간평가=mid, 기말평가=final, 레포트 = report, 시험주의사항=cautionMid,cautionFinal , 레포트주의사항=cautionReport,
	var studyModals = '';
	studyModals += '<div id="screenModal" style="display:none;">';
	//타이틀 노출
	studyModals += '<div class="titleArea">';
	studyModals += '<div>';
	if(types=='mid'){
		studyModals += '<img src="../images/study/img_test01.png" />';
		studyModals += '<h1>중간평가</h1>';
	}else if(types=='final'){
		studyModals += '<img src="../images/study/img_test02.png" />';
		studyModals += '<h1>최종평가</h1>';
	}else{
		studyModals += '<img src="../images/study/img_report.png" />';
		studyModals += '<h1>과제제출</h1>';
	}
	//강의명 호출
	studyModals += '<h2 class="contentsName">유의사항</h2>';
	
	//타입에 따라 버튼 액션 호출 필요
	studyModals += '<button type="button" onClick="studyModalClose();"><img src="../images/study/btn_modalclose.png" /></button>';
	studyModals += '</div>';
	studyModals += '</div>';
	
	//주의사항
	studyModals += '<div class="caution">';
	studyModals += '<img src="../images/study/img_notice_big.png" />';
	//주의사항
	studyModals += '<h1>주의사항</h1>';
	var cautionApi = $.get('../api/apiSiteInfo.php',{},function(data){
		if(types == 'mid'){
			studyModals += '<p>'+data.midCopy.replace(/\n/g,'<br />')+'</p>';
		}else if(types == 'final'){
			studyModals += '<p>'+data.testCopy.replace(/\n/g,'<br />')+'</p>';
		}else if(types == 'report'){
			studyModals += '<p>'+data.reportCopy.replace(/\n/g,'<br />')+'</p>';
		}
		studyModals += '</div>';
		studyModals += '<div class="agreeArea">';
		if(types == 'mid' || types == 'final'){
			studyModals += '<input type="checkbox" name="agree" id="agree" value="agreeOK" />';
			studyModals += '<label for="agree">위 사항을 모두 숙지하였으며, 공정하게 평가에 응시하겠습니다.</label>';
			studyModals += '</div>';
			studyModals += '<div class="btnArea">';
			studyModals += '<button onclick="agreeTest(\''+types+'\',\''+contentsCode+'\','+lectureOpenSeq+')"><img src="../images/study/btn_dotest_big.png" /></button>';
			studyModals += '</div>';
		}else if(types == 'report'){
			studyModals += '<input type="checkbox" name="agree" id="agree" value="agreeOK" />';
			studyModals += '<label for="agree">위 사항을 모두 숙지하였으며, 공정하게 과제를 제출하겠습니다.</label>';
			studyModals += '</div>';
			studyModals += '<div class="btnArea">';
			studyModals += '<button onclick="agreeTest(\''+types+'\',\''+contentsCode+'\','+lectureOpenSeq+')"><img src="../images/study/btn_doreport_big.png" /></button>';
			studyModals += '</div>';
		}
		studyModals += '</div>';
		$('#footer').after(studyModals);
		$('#screenModal').fadeIn('fast');
	})
}

function agreeTest(types,contentsCode,lectureOpenSeq){
	if($('input[name="agree"]:checked').prop('checked') == true){
		if(types == 'final') {
			if(confirm('평가 재응시는 불가능하며, 응시 제한시간 내에 완료하여야 합니다.\n\n정말로 평가를 응시하시겠습니까?')){
				$('#screenModal').fadeOut('fast',function(){
					$('#screenModal').remove()
					doTest(types,contentsCode,lectureOpenSeq)
				});
			}
		} else {
			$('#screenModal').fadeOut('fast',function(){
				$('#screenModal').remove()
				doTest(types,contentsCode,lectureOpenSeq)
			});
		}
	}else{
		if(types == 'report'){
			alert('과제제출 유의사항에 동의해주세요');
		}else{
			alert('평가응시 유의사항에 동의해주세요');
		}
	}
}
function doTest(types,contentsCode,lectureOpenSeq){
	////////////////////////////
	var studyModals = '';
	studyModals += '<div id="screenModal" style="display:none;">';
	//타이틀 노출
	studyModals += '<div class="titleArea">';
	studyModals += '<div>';
	if(types=='mid'){
		studyModals += '<img src="../images/study/img_test01.png" />';
		studyModals += '<h1>중간평가</h1>';
	}else if(types=='final'){
		studyModals += '<img src="../images/study/img_test02.png" />';
		studyModals += '<h1>최종평가</h1>';
	}else{
		studyModals += '<img src="../images/study/img_report.png" />';
		studyModals += '<h1>과제제출</h1>';
	}
	//강의명 호출
	studyModals += '<h2 class="contentsName"></h2>';
	
	//타입에 따라 버튼 액션 호출 필요
	if(types == 'final'){
		clearTimeout(setClockfunction);
		studyModals += '<button type="button" onClick="studyModalClose(\'final\');"><img src="../images/study/btn_modalclose.png" /></button>';
	}else{
		studyModals += '<button type="button" onClick="studyModalClose();"><img src="../images/study/btn_modalclose.png" /></button>';
	}
	studyModals += '</div>';
	studyModals += '</div>';
	if(types=='mid' || types=='final'){ // 중간, 최종평가		
		var answerAjax = $.get(testApi,'testType='+types+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&list=99',function(data){			
			
			studyModals += '<div class="cautionTest">';
			if(types == 'final'){
				var old = data.testEndTime;
				var oldTime = old.substr(11,8)
				var oldYear = old.substr(0,4)
				var oldMonth = old.substr(5,2)
				var oldDay = old.substr(8,2)
				old = new Date(oldMonth+'/'+oldDay+'/'+oldYear+' '+oldTime).getTime()
				
				var now = data.nowTime;
				var nowTime = now.substr(11,8)
				var nowYear = now.substr(0,4)
				var nowMonth = now.substr(5,2)
				var nowDay = now.substr(8,2)
				now = new Date(nowMonth+'/'+nowDay+'/'+nowYear+' '+nowTime).getTime();
				var gap = old - now;
				examEndTime = gap/1000;
				setClock();
				studyModals += '<div class="timer">';
				studyModals += '</div>';
			}

			studyModals += '<div class="textArea">';
			studyModals += '<h1>평가 주의 사항</h1>';
			if(types == 'final'){
				studyModals += '<p>평가는 <strong>1회만 응시가능</strong>하며, 제한시간은 접속종료 등의 상태에서도 중단없이 계속 흘러가게 됩니다.<br /><strong>재응시가 불가능</strong>하므로 신중히 응시하시길 바랍니다.</p>';
			} else if(types == 'mid') {
				studyModals += '<p>평가는 <strong>1회만 응시가능</strong>하며, <strong>재응시가 불가능</strong>하므로 신중히 응시하시길 바랍니다.</p>';
			}
			studyModals += '</div>';
			studyModals += '</div>';
			studyModals += '<div class="testArea">';
			studyModals += '<ul>';
			var ansExamPage=0;
			$.each(data.studyTest, function(){ // 답안지
				if(examList != 1) {
					if(parseInt(this.orderBy % examList) == 1) {
						ansExamPage = parseInt(ansExamPage)+1;
					}
				} else {
					ansExamPage = parseInt(ansExamPage)+1;
				}
				if(this.userAnswer == null || this.userAnswer == '') {
					var userAnswer = '&nbsp;';
				} else {
					var userAnswer = this.userAnswer;
				}
				studyModals += '<li onClick="answerSave(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+ansExamPage+'\')" style="cursor:pointer;">문제 '+this.orderBy;
				studyModals += '<div class="userAnswer'+this.orderBy+'">'+userAnswer+'</div></li>';
				i++;
			})
			studyModals += '</ul>';
			studyModals += '<div id="examArea">';
			studyModals += '</div>';
			studyModals += '</div>'; //문제종료
			studyModals += '<div class="btnArea">';
			studyModals += '</div>';
			studyModals += '</div>';
			$('#footer').after(studyModals);
			$('#screenModal').fadeIn('fast');
			$('#screenModal div.titleArea h2').html(data.contentsName);
			examRequest(types,contentsCode,lectureOpenSeq,examList,examPage);
		})

	}else if(types=='report'){  // 과제
		var reportAjax = $.get(reportApi,'contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq,function(data){
			$('#sceenModal div h2').html(data.contentsName)
			studyModals += '<div class="cautionTest">';
			studyModals += '<div class="textArea">';
			studyModals += '<h1>평가 주의 사항</h1>';
			studyModals += '<p>과제 제출은 <strong>1회만</strong> 가능하며, 최종제출 후에는 재제출이 불가능합니다.<br />최종제출 전까지는 임시저장 기능을 활용하시고, 학습기간 내에 꼭 <strong>최종제출을 완료</strong>하여야 합니다.</p>';
			studyModals += '</div>';
			studyModals += '</div>';
			var i = 1;
			studyModals += '<form class="answerForm" name="answerForm" method="post" action="'+reportApi+'" enctype="multipart/form-data">';
			studyModals += '<input type="hidden" name="reportEnd" value="" />';
			studyModals += '<input type="hidden" name="lectureOpenSeq" value="'+data.lectureOpenSeq+'">';
			studyModals += '<input type="hidden" name="contentsCode" value="'+data.contentsCode+'">';
			$.each(data.studyReport, function(){
				//문제영역
				var answerType = '';
				if(this.answerType == null){
					answerType = 'attach';
				}else{
					answerType = this.answerType;
				}
				studyModals += '<div class="reportArea">';
				studyModals += '<input type="hidden" name="seq[]" value="'+this.seq+'" />';
				studyModals += '<input type="hidden" name="reportSeq[]" value="'+this.reportSeq+'" />';
				studyModals += '<input type="hidden" name="reserveDate[]" value="'+this.reserveDate+'" />';
				studyModals += '<input type="hidden" name="answerType[]" value="'+this.answerType+'" />';
				
				studyModals += '<h1>문제.'+i+'</h1>';
				studyModals += '<h2>'+this.exam.replace(/  /g,'&nbsp;&nbsp;').replace(/\n/g,'<br />')+'</h2>';
				//등록문제 다운로드
				if(this.examAttach != null) {
					studyModals += '<a href="../lib/fileDownLoad.php?fileName='+this.examAttach+'&link='+this.examAttachLink+'" target="_blank">레포트문제 다운로드 : '+this.examAttach+'</a>';
				}
				//
				studyModals += '<br /><div class="reportSubmit">';
				studyModals += '<ul id="reportTab'+i+'" class="reportTab'+this.seq+'">';
				if(answerType == 'attach'){
					studyModals += '<li class="select"><img src="../images/study/img_submitfile.png" alt="파일제출" /> 파일로 제출하기</li>';
					studyModals += '<li><img src="../images/study/img_sumitwrite.png" alt="직접작성" /> 직접 작성하기</li>';
				}else{
					studyModals += '<li><img src="../images/study/img_submitfile.png" alt="파일제출" /> 파일로 제출하기</li>';
					studyModals += '<li class="select"><img src="../images/study/img_sumitwrite.png" alt="직접작성" /> 직접 작성하기</li>';
				}
				studyModals += '</ul>';
				studyModals += '<div>';
				studyModals += '<input type="hidden" name="answerType'+this.seq+'" value="'+answerType+'" />';

				studyModals += '<ul>';
				if(answerType == 'attach'){
					if(this.answerAttach == null ){
						studyModals += '<li><h1>파일 제출하기</h1>';
						studyModals += '<input type="hidden" name="fileCheck'+this.seq+'" value="" />'
					}else{
						studyModals += '<li class="select"><h1>현재 제출파일</h1><a href="../lib/fileDownLoad.php?fileName='+this.answerAttach+'&link='+this.attachLink+'" target="_blank">'+this.answerAttach+'</a>';
						studyModals += '<input type="hidden" name="fileCheck'+this.seq+'" value="filechecked" /></li>';
						studyModals += '<li><h1>다시 제출하기</h1>';
					}
					studyModals += '<input type="file" name="answerAttach'+this.seq+'" /></li>';
					studyModals += '</ul>';
					studyModals += '<div>파일로 제출하는 경우 2개 이상의 문서는 압축하여 1개의 파일로 제출하여야 하며, 제출파일은 <strong>마지막에 제출한 파일로 최종 저장</strong>됩니다.';
					studyModals += '<br />파일로 제출하기와 직접작성하기 중 마지막에 제출한 방식 하나만 최종 제출됩니다.</div>';
				}else{
					studyModals += '<h1>레포트입력하기</h1>';
					if(this.answerText != null){
					  studyModals += '<textarea name="answerText'+this.seq+'" oncontextmenu="return false" onkeydown="keyCheck(event)">'+this.answerText+'</textarea>';
					}else{
					  studyModals += '<textarea name="answerText'+this.seq+'" oncontextmenu="return false" onkeydown="keyCheck(event)"></textarea>';
					}
						studyModals += '<div>파일로 제출하는 경우 2개 이상의 문서는 압축하여 1개의 파일로 제출하여야 하며, 제출파일은 <strong>마지막에 제출한 파일로 최종 저장</strong>됩니다.';
						studyModals += '<br />파일로 제출하기와 직접작성하기 중 마지막에 제출한 방식 하나만 최종 제출됩니다.</div>';
				}
				studyModals += '</div>';
				//
				studyModals += '</div>';
				//
				studyModals += '</div>';
				i++;
			})
			studyModals += '</form>';
			studyModals += '<div class="btnArea">';
			studyModals += '<button type="button" onClick="reportSave(\'\')"><img src="../images/study/btn_save.png" /></button>';
			studyModals += '<button type="button" onClick="answerEnd(\'\',\'\',\'\',\'\',\'\',\'Y\')"><img src="../images/study/btn_lastsubmit.png" /></button>';
			studyModals += '</div>';
			studyModals += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			studyModals += '</div>';
			$('#footer').after(studyModals);
			$('#screenModal div.titleArea h2').html(data.contentsName)
			fileformAct();//파일 첨부 사용시
			$('#screenModal').fadeIn('fast');
		}).done(function(data){
			$('.reportSubmit > ul > li').bind({
				click:function(){
					var types = $(this).index()
					var studyModals = '';
					var actionDiv = $(this).parent('ul').parent('div');
					var actionOrder = Number($(this).parent('ul').attr('id').replace('reportTab','')-1);
					var actionSeq = $(this).parent('ul').attr('class').replace('reportTab','');
					studyModals += '<input type="hidden" name="answerType'+actionSeq+'" value="" />';
					if(types == 0){						
						$(this).parent('ul').children('li').removeClass('select');
						$(this).parent('ul').children('li:nth-child(1)').addClass('select');
						studyModals += '<ul>';
						if(data.studyReport[actionOrder].answerAttach != null){
							studyModals += '<li class="select"><h1>현재 제출파일</h1><a href="../lib/fileDownLoad.php?fileName='+this.answerAttach+'&link='+this.attachLink+'" target="_blank">'+data.studyReport[actionOrder].answerAttach+'</a></li>';
							studyModals += '<li><h1>파일 제출하기</h1>';
						}
						else{
							studyModals += '<li><h1>다시 제출하기</h1>';
						}
						studyModals += '<input type="file" name="answerAttach'+actionSeq+'" /></li>';
						studyModals += '</ul>';
						studyModals += '<div>파일로 제출하는 경우 2개 이상의 문서는 압축하여 1개의 파일로 제출하여야 하며, 제출파일은 <strong>마지막에 제출한 파일로 최종 저장<strong>됩니다.';
						studyModals += '<br />파일로 제출하기와 직접작성하기 중 마지막에 제출한 방식 하나만 최종 제출됩니다.</div>';
					}else{
						$(this).parent('ul').children('li').removeClass('select');
						$(this).parent('ul').children('li:nth-child(2)').addClass('select');
						studyModals += '<h1>레포트입력하기</h1>';
						//alert(data.studyReport[actionOrder].exam)
						if(data.studyReport[actionOrder].answerText != null){
							studyModals += '<textarea name="answerText'+actionSeq+'" oncontextmenu="return false" onkeydown="keyCheck(event)">'+data.studyReport[actionOrder].answerText+'</textarea>';
						}else{
							studyModals += '<textarea name="answerText'+actionSeq+'" oncontextmenu="return false" onkeydown="keyCheck(event)"></textarea>';
						}
						studyModals += '<div>파일로 제출하는 경우 2개 이상의 문서는 압축하여 1개의 파일로 제출하여야 하며, 제출파일은 <strong>마지막에 제출한 파일로 최종 저장<strong>됩니다.';
						studyModals += '<br />파일로 제출하기와 직접작성하기 중 마지막에 제출한 방식 하나만 최종 제출됩니다.</div>';
					}
					//alert(studyModals)
					actionDiv.children('div').html(studyModals);
					if(types == 0){
						$('input[name="answerType'+actionSeq+'"]').val('attach');
						actionDiv.children('div').children('ul').children('li').children('input[type="file"]').each(function(){
							var thisName = $(this).attr('name');
							var preLabel = '';
							preLabel += '<label class="AttachFiles"><span>파일찾기</span>';
							preLabel += '<input type="file" name="'+thisName+'" style="display:none" onchange="fileAddAct(this,\''+thisName+'\')" />';
							preLabel += '</label>';
							$(this).after(preLabel);
							$(this).remove();
						})
					}else{
						$('input[name="answerType'+actionSeq+'"]').val('text');
					}
				}
			})
		})
	}
	////////////////////////////
}

//문제 호출
function examRequest(types,contentsCode,lectureOpenSeq,examList,examPage) {
	var examView = '';
	var examAjax = $.get(testApi,'testType='+types+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&list='+examList+'&page='+examPage,function(data){
		examView += '<form class="answerForm" method="post">';
		examView += '<input type="hidden" name="contentsCode" value="'+data.contentsCode+'">';
		examView += '<input type="hidden" name="lectureOpenSeq" value="'+data.lectureOpenSeq+'">';
		examView += '<input type="hidden" name="testType" value="'+data.testType+'">';
		$.each(data.studyTest, function(){ // 답안지
			examView += '<input type="hidden" name="seq[]" value="'+this.seq+'">';
			examView += '<input type="hidden" name="examType[]" value="'+this.examType+'">';

			if(this.examType=='C'){ // 서술형
				var examTypeName = '(서술형)';
			} else {
				var examTypeName = '';
			}

			examView += '<h1>문제 '+this.orderBy+' '+examTypeName+'</h1>';
			examView += '<h2>'+this.exam.replace(/  /g,'&nbsp;&nbsp;').replace(/\n/g,'<br />')+' (배점 : '+this.score+')</h2>';
			if(this.examType=='A'){ // 객관식
				if(this.userAnswer == 1) {
					var selected01 = 'checked="checked"';
				} else if(this.userAnswer == 2) {
					var selected02 = 'checked="checked"';
				} else if(this.userAnswer == 3) {
					var selected03 = 'checked="checked"';
				} else if(this.userAnswer == 4) {
					var selected04 = 'checked="checked"';
				} else if(this.userAnswer == 5) {
					var selected05 = 'checked="checked"';
				}
				examView += '<ol>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example01'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'1\')" value="1" '+selected01+'/>';
				examView += '<label for="example01'+this.seq+'">1.&nbsp;'+this.example01+'</label></li>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example02'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'2\')" value="2" '+selected02+'/>';
				examView += '<label for="example02'+this.seq+'">2.&nbsp;'+this.example02+'</label></li>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example03'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'3\')" value="3" '+selected03+'/>';
				examView += '<label for="example03'+this.seq+'">3.&nbsp;'+this.example03+'</label></li>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example04'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'4\')" value="4" '+selected04+'/>';
				examView += '<label for="example04'+this.seq+'">4.&nbsp;'+this.example04+'</label></li>';
				if(this.example05 != undefined){
					examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example05'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'4\')" value="5" '+selected05+'/>';
					examView += '<label for="example05'+this.seq+'">5.&nbsp;'+this.example05+'</label></li>';
				}
				examView += '</ol>';
			} else if(this.examType=='B'){ // 단답형
				if(this.userAnswer != null){
					examView += '<input type="text" name="userAnswer'+this.seq+'" onkeyup="answerUpdate('+this.orderBy+',this.value,event)" onkeydown="keyCheck(event);" value="'+this.userAnswer+'"  oncontextmenu="return false" />';
				}else{
					examView += '<input type="text" name="userAnswer'+this.seq+'" onkeyup="answerUpdate('+this.orderBy+',this.value,event)" onkeydown="keyCheck(event);" value=""  oncontextmenu="return false" />';
				}
			} else if(this.examType=='C'){ // 서술형
				if(this.userAnswer != null){
					examView += '<textarea name="userAnswer'+this.seq+'" onkeyup="answerUpdate('+this.orderBy+',this.value,event)" onkeydown="keyCheck(event);" oncontextmenu="return false">'+this.userAnswer+'</textarea>';
				}else{
					examView += '<textarea name="userAnswer'+this.seq+'" onkeyup="answerUpdate('+this.orderBy+',this.value,event)" onkeydown="keyCheck(event);" oncontextmenu="return false"></textarea>';
				}
			} else if(this.examType=='D'){ // 진위형
				if(this.userAnswer == 1) {
					var selectedOX01 = 'checked="checked"';
				} else if(this.userAnswer == 2) {
					var selectedOX02 = 'checked="checked"';
				}
				examView += '<ol>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example01'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'1\')" value="1" '+selectedOX01+'/>';
				examView += '<label for="example01'+this.seq+'">1.&nbsp;'+this.example01+'</label></li>';
				examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example02'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'2\')" value="2" '+selectedOX02+'/>';
				examView += '<label for="example02'+this.seq+'">2.&nbsp;'+this.example02+'</label></li>';
				examView += '</ol>';
			}
		})
		examView += '</form>';
		var btnView = '';
		btnView += '<button type="button" class="fLeft" onClick="answerEnd(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+prevPage+'\',\'Y\')"><img src="../images/study/btn_lastsubmit.png" alt="최종제출" /></button>';
		var loadEA = parseInt(data.examList)*parseInt(data.examPage);
		var nextPage = parseInt(examPage)+1;
		var prevPage = parseInt(examPage)-1;
		if(examPage == 1){ // 1페이지
			btnView += '<button type="button" class="fRight" onClick="answerSave(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+nextPage+'\')"><img src="../images/study/btn_nextexam.png" alt="다음문제" /></button>';
			btnView += '<button type="button" class="fRight" onClick="alert(\'처음입니다.\');"><img src="../images/study/btn_prevexam.png" alt="이전문제" /></button>';
		} else if(data.totalCount <= loadEA) { // 마지막 페이지
			btnView += '<button type="button" class="fRight" onClick="alert(\'마지막입니다.\');"><img src="../images/study/btn_nextexam.png" alt="다음문제" /></button>';
			btnView += '<button type="button" class="fRight" onClick="answerSave(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+prevPage+'\')"><img src="../images/study/btn_prevexam.png" alt="이전문제" /></button>';
		} else {
			btnView += '<button type="button" class="fRight" onClick="answerSave(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+nextPage+'\')"><img src="../images/study/btn_nextexam.png" alt="다음문제" /></button>';
			btnView += '<button type="button" class="fRight" onClick="answerSave(\''+types+'\',\''+contentsCode+'\',\''+lectureOpenSeq+'\',\''+examList+'\',\''+prevPage+'\')"><img src="../images/study/btn_prevexam.png" alt="이전문제" /></button>';
		}
		$('#examArea').html(examView);
		$('.btnArea').html(btnView);
	})
}

//제출답 반영
function answerUpdate(num,answer,event) {
	event = event ? event : '';
	if (event.keyCode == 86 && event.ctrlKey){
		return false
		//obj.value = ''
		//$('.userAnswer'+num).html('&nbsp');
	}else{
		if(answer != ''){
			$('.userAnswer'+num).html(answer);
		}else{
			$('.userAnswer'+num).html('&nbsp');
		}
	}
}

function answerEnd(types,contentsCode,lectureOpenSeq,examList,examPage,testEnd) {
	if(confirm('최종제출 후 수정하실 수 없습니다.\r\r최종제출 하시겠습니까?')) {
		if(types == 'mid' || types == 'final') {
			answerSave(types,contentsCode,lectureOpenSeq,examList,examPage,testEnd);
		} else {
			reportSave(testEnd);
		}
	}
}

//제출답 서버 저장
function answerSave(types,contentsCode,lectureOpenSeq,examList,examPage,testEnd,endTime,closed) {
	var sendSerial = $('form.answerForm').serialize();
	if(testEnd == 'Y') {
		var testEndAdd = '&testEnd=Y';
	} else {
		var testEndAdd = '';
	}
	if(endTime == 'Y') {
		var endTimeAdd = '&endTime=Y';
	} else {
		var endTimeAdd = '';
	}
	$.ajax({
		url: testApi,
		type:'POST',
		data:sendSerial+testEndAdd+endTimeAdd,
		dataType:'JSON',
		success:function(data){
			if(testEnd == 'Y') {
				if(data.result == 'success'){
					alert('최종제출 되었습니다.');
					//studyModalClose();	
					$('#screenModal').remove();
					$('body').css('overflow','auto')
					listAct();
				}else if(data.result == 'timeEnd') {
					alert('응시가 종료되었습니다. 마지막 저장된 답까지 자동제출 됩니다.');
					studyModalClose();	
				}else{
					alert(data.result);
				}
			} else {
				if(closed != 'Y') {
					examRequest(types,contentsCode,lectureOpenSeq,examList,examPage);
				}
			}
		},
		fail:function(){
			alert('저장에 실패하였습니다. 창을 다시 여시기 바랍니다.');
		}
	})	
}


//과제 제출
function reportSave(reportEnd) {
	
	var contentsCode = $('.answerForm input[name="contentsCode"]').val();
	var lectureOpenSeq = $('.answerForm input[name="lectureOpenSeq"]').val();
	
	var reportPart = new Array
	var errorCnt = 0;
	$('.reportArea').each(function(){
        var reportSeq = $(this).children('input[name="seq[]"]').val()
		var reportTitle = $(this).children('h1').html();
		if($(this).children('input[name="answerType[]"]').val() == 'attach' || $(this).children('input[name="answerType[]"]').val() == "null"){
			if($('input[name="answerAttach'+reportSeq+'"]').val() == '' || $('input[name="answerAttach'+reportSeq+'"]').val() == 'undefined'){
				if($('input[name="fileCheck'+reportSeq+'"]').val() != 'filechecked'){
					errorCnt ++
					alert('제출 파일이 없습니다. 파일을 등록 하시기 바랍니다.');

				}
			}
		}
		if($('textarea[name="answerText'+reportSeq+'"]').val() == ''){
				errorCnt ++
			alert('작성한 내용이 없습니다. 내용을 작성 하시기 바랍니다.');

		}			
    });
		
	if(reportEnd == 'Y') {
		$('input[name="reportEnd"]').val('Y');
	}
	if(errorCnt == 0){
		$('.answerForm').ajaxForm({
			dataType:'text',
			beforeSubmit: function (data,form,option) {
				return true;
			},
			success: function(data,status){
				if(reportEnd == 'Y') {
					studyModalClose();
					$.get(reportApi,{'contentsCode':contentsCode,'lectureOpenSeq':lectureOpenSeq},function(data){
						$.each(data.studyReport, function(){
							var uri = this.seq;
							var group_id = this.reportSeq;
							var reserve_date = this.reserveDate;
							var title = loginUserID + this.seq;
							var content = '';
							var file1 = '';
							if(this.answerType == "text"){
								content = this.answerText
							}else{
								file1 = 'http://esangedu.kr'+this.attachLink;
							}
							alert("제출이 완료되었습니다.");
							$.get('../api/copyKiller/insert_copykiller_content.php',{'uri':uri,'group_id':group_id,'title':group_id,'content':content,'file1':file1,'reserve_date':reserve_date})
						})
					})
				}else{
					alert("임시 저장되었습니다. 학습기간 내에 꼭 최종제출을 하여야 합니다.");
					$('#screenModal').remove();
					doTest('report',contentsCode,lectureOpenSeq)
				}
			},
			error: function(){
				//에러발생을 위한 code페이지
				alert('저장에 실패하였습니다. 창을 다시 여시기 바랍니다.');
			}
		});
		$('.answerForm').submit();
	}
}

//서영기 - 맨끝 변수 'Y' --> stat
function setClock(types,contentsCode,lectureOpenSeq,examList,examPage,stat) { // 남은시간 체크
//시간이 다되면
	if(parseInt(examEndTime)==0) {
		timer = '<span>남은시간</span><strong>00:00</strong>';
		$('.timer').html(timer);
		examTimeOut(types,contentsCode,lectureOpenSeq,examList,examPage);
	 } else if(parseInt(examEndTime) >= 1) {
		var Minutes = parseInt(examEndTime% 36000 / 60); 
		var Seconds = parseInt(examEndTime% 3600 % 60 ); 
		var	Value = ((Minutes < 10) ? "0" : "") + Minutes;
			Value += ((Seconds < 10) ? ":0" : ":") + Seconds;
		examEndTime = examEndTime - 1;
		timer = '<span>남은시간</span> <strong>'+Value+'</strong>';
		$('.timer').html(timer);
		setClockfunction = setTimeout ("setClock()", 1000); 
	}
}

function examTimeOut(types,contentsCode,lectureOpenSeq,examList,examPage) {
	answerSave(types,contentsCode,lectureOpenSeq,examList,examPage,'Y','Y');
}

function studyModalClose(types) {
	answerSave(types,contentsCode,lectureOpenSeq,examList,examPage,'','','Y');
	if(types=='final'){
		var endTime = $('.timer strong').html();
		if(confirm('평가 시간이 '+endTime+' 남았습니다.\n평가 시간은 응시 도중 접속 종료 등의 상태에서도 중단 없이 계속 흘러가며, 평가 시간이 지나면 자동 종료됩니다.\n창을 닫으시겠습니까?')) {
			
		} else {
			return;
		}
	}
	$('#screenModal').remove();
	$('body').css('overflow','auto')
	listAct();
}

//인증창
function certPassModal(startDate,studySeq,contentsCode,lectureOpenSeq){
	$('body').css('overflow','hidden');
	var studyModals = '';
	studyModals += '<div id="screenModal" style="display:none;">';
	//타이틀 노출
	studyModals += '<div class="titleArea">';
	studyModals += '<div>';
	studyModals += '<img src="../images/study/img_test01.png" />';
	studyModals += '<h1>학습자 유의사항</h1>';	
	studyModals += '<h2 class="contentsName">학습자 유의사항 및 본인인증</h2>';
	
	//타입에 따라 버튼 액션 호출 필요
	studyModals += '<button type="button" onClick="studyModalClose();"><img src="../images/study/btn_modalclose.png" /></button>';
	studyModals += '</div>';
	studyModals += '</div>';
	
	//주의사항
	studyModals += '<div class="caution">';
	studyModals += '<img src="../images/study/img_notice_big.png" />';
	//주의사항
	studyModals += '<h1>주의사항</h1>';
	var cautionApi = $.get('../api/apiSiteInfo.php',function(data){
		studyModals += data.caution;
		studyModals += '<p><h1>강의 주의 사항</h1>';
		studyModals += '<p>순차학습으로 진행되며, 1일 최대 <strong>8차시</strong>까지 학습이 가능합니다.<br />';
		studyModals += '평가(중간/최종)와 과제는 <strong>1회만</strong> 응시 가능하며, 최종평가는 응시 제한시간이 있습니다.<br />';
		studyModals += '진도율(중간평가 50% 이상, 최종평가/과제 80% 이상)에 따라 응시 가능 여부가 달라집니다.<br /><br />';
		studyModals += '모든 과정의 수료기준은 진도율 80% 이상, 총점 60점 이상이 되어야 합니다.<br /><br />';
		studyModals += '<strong>※ 수료기준에 도달한 경우에만 고용 노동부로부터 훈련비용의 지원을 받을 수 있습니다.</strong></p></p>';
		studyModals += '</div>';
		studyModals += '<div class="mobileCert">';
		studyModals += '<form class="certForm" action="javascript:agreeCert()">'
		studyModals += '<input type="hidden" name="lectureStart" value="'+startDate+'" />'
		studyModals += '<input type="hidden" name="certPass" value="" />'
		studyModals += '</form>';
		studyModals += '<ul><li>';
		studyModals += '<h1>본인인증</h1>';
		studyModals += '<form name="form_chk" method="post">';
		studyModals += '<input type="hidden" name="m" value="checkplusSerivce">';
		studyModals += '<input type="hidden" name="EncodeData" value="'+enc_data+'">';
		studyModals += '<input type="hidden" name="param_r1" value=""><input type="hidden" name="param_r2" value=""><input type="hidden" name="param_r3" value="">';
		studyModals += '<button type="button" onclick="fnPopup();" class="btnPhone">휴대폰인증하기</button></form>&nbsp;&nbsp;';
		studyModals += '<form name="form_ipin" method="post">';
		studyModals += '<input type="hidden" name="m" value="pubmain">';
		studyModals += '<input type="hidden" name="enc_data" value="'+sEncData+'">';
		studyModals += '<input type="hidden" name="param_r1" value="">';
		studyModals += '<input type="hidden" name="param_r2" value="">';
		studyModals += '<input type="hidden" name="param_r3" value=""></form>';    
		//studyModals += '<button type="button" onClick="fnPopupipin();" class="btnIpin">아이핀인증하기</button>&nbsp;&nbsp;';
		//studyModals += '<button type="button" onClick="window.open(\'http://www.gpin.go.kr/center/main/index.gpin\')" class="btnIpin">공공IPIN센터 바로가기</button></form>';
		//studyModals += '<br /><span class="red">※ 타인 명의 휴대폰인 경우 공공아이핀을 발급 후 아이핀 인증으로 진행해 주시기 바랍니다. (아이핀/마이핀가입)</span>';
		studyModals += '<form name="vnoform" method="post">';
		studyModals += '<input type="hidden" name="enc_data">';
		studyModals += '<input type="hidden" name="param_r1" value="">';
		studyModals += '<input type="hidden" name="param_r2" value="">';
		studyModals += '<input type="hidden" name="param_r3" value="">';
		studyModals += '</form>';
		studyModals += '</li></ul>';
		studyModals += '</form>';
		studyModals += '</div>'
		studyModals += '<div class="btnArea">';
		studyModals += '<button onclick="agreeCert('+studySeq+',\''+contentsCode+'\','+lectureOpenSeq+')"><img src="../images/member/btn_ok.png" /></button>';
		studyModals += '</div>';
		studyModals += '</div>';
		$('#footer').after(studyModals);
		$('#screenModal').fadeIn('fast');
	})
}

function mobileCheck(userName,userBirth,sex){
	if(userName != loginUserName) {
		alert('인증정보와 회원정보가 일치하지 않습니다. 문제가 있는 경우 고객센터로 연락주시기 바랍니다.');
	} else {
		$('div.mobileCert > ul form').remove();
		$('div.mobileCert > ul h1').after('<strong class="blue">본인인증이 완료되었습니다.</strong>');
		$('input[name="certPass"]').val('Y');
	}
}

function agreeCert(studySeq,contentsCode,lectureOpenSeq){
	if($('input[name="certPass"]').val() != 'Y'){
		alert('휴대폰 본인인증을 해주세요')
	}else{
		var sendData = $('form.certForm').serialize();
		$.post(chapterApi,sendData,function(data){
			if(data.result == 'success'){
				alert('확인이 완료되었습니다.');
				$('#screenModal').remove();
				$('body').css('overflow','auto')
				viewStudyDetail(studySeq,contentsCode,lectureOpenSeq)
			}else{
				alert('에러입니다. 시스템관리자에게 문의하세요.')
			}
		})
	}
}

function keyCheck(event){
	if (event.keyCode == 86 && event.ctrlKey){
		alert('붙여넣기는 불가능합니다.');
		event.returnValue = false;
	}
}