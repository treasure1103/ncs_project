<? include '../lib/header.php'; ?>
<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>평가결과보기</title>
<link rel="stylesheet" href="../css/userStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var contentsCode = "<?=$_GET['contentsCode'] ?>";
  var lectureOpenSeq = "<?=$_GET['lectureOpenSeq'] ?>"
  var types = "<?=$_GET['types'] ?>";
  var testType = "<?=$_GET['testType'] ?>";
  var machingApi = '';
  testType = testType ? testType : '';
  if(types == 'test'){
	  matchingApi = '../api/apiStudyTestResult.php';
  }else if(types == 'report'){
	  matchingApi = '../api/apiStudyReportResult.php';
  }
  $.get(matchingApi,{'contentsCode':contentsCode, 'lectureOpenSeq':lectureOpenSeq, 'testType':testType},function(data){
	  if( testType == 'mid' ){
		  $('#contentsNav > h2').html('중간평가&nbsp;<span>|&nbsp;</span>')
	  }else if( testType == 'final' ){
		  $('#contentsNav > h2').append('최종평가&nbsp;<span>|&nbsp;</span>')
	  }else{
		  $('#contentsNav > h2').html('과제&nbsp;<span>|&nbsp;</span>')
	  }
	  var MosaCheck = ''
	  if(types == 'test'){
		  if(data.testCopy == 'N'){
			  MosaCheck = '정상'
		  }else{
			  MosaCheck = '모사답안'
		  }
		  $('#contentsNav > h2').append('<strong>'+data.contentsName+'</strong><br /><span>응시일 : '+data.saveTime+ '&nbsp;&nbsp;|&nbsp;&nbsp;모사답안여부 : '+MosaCheck+'</sapn>')
	  }else{
		  if(data.reportCopy == 'N'){
			  MosaCheck = '정상'
		  }else{
			  MosaCheck = '모사답안'
		  }
		  $('#contentsNav > h2').append('<strong>'+data.contentsName+'</strong><br /><span>응시일 : '+data.reportSaveTime+ '&nbsp;&nbsp;|&nbsp;&nbsp;모사답안여부 : '+MosaCheck+'</sapn>')
	  }
	  var examView = '';
	  var score = '';
		var answerOX = '';
		var	userScore = '';
		var	aTypeTotalScore = '';
		var	bTypeTotalScore = '';
		var	cTypeTotalScore = '';
		var	dTypeTotalScore = '';
	  var i = 1;

	  if( types == 'test'){
		  var scoreTable = '';
		  scoreTable += '<table><thead><tr>';
		  scoreTable += '<th>총점</th>';
		  if(data.dTypeEA != 0){
			  scoreTable += '<th>진위형</th>';
		  }
		  if(data.aTypeEA != 0){
			  scoreTable += '<th>객관식</th>';
		  }
		  if(data.bTypeEA != 0){
			  scoreTable += '<th>단답형</th>';
		  }
		  if(data.cTypeEA != 0){
			  scoreTable += '<th>서술형</th>';
		  }
		  scoreTable += '</tr></thead><tbody><tr>';
			if(data.userScore == null || data.userScore == '') {
				userScore = '채점전';
				dTypeTotalScore = '-';
				aTypeTotalScore = '-';
				bTypeTotalScore = '-';
				cTypeTotalScore = '-';
			} else {
				userScore = data.userScore;
				dTypeTotalScore = data.dTypeTotalScore;
				aTypeTotalScore = data.aTypeTotalScore;
				bTypeTotalScore = data.bTypeTotalScore;
				cTypeTotalScore = data.cTypeTotalScore;
			}
		  scoreTable += '<td><strong class="red">'+userScore+'</strong></td>';
		  if(data.dTypeEA != 0){
			  scoreTable += '<td>'+dTypeTotalScore+'</td>';
		  }
		  if(data.aTypeEA != 0){
			  scoreTable += '<td>'+aTypeTotalScore+'</td>';
		  }
		  if(data.bTypeEA != 0){
			  scoreTable += '<td>'+bTypeTotalScore+'</td>';
		  }
		  if(data.cTypeEA != 0){
			  scoreTable += '<td>'+cTypeTotalScore+'</td>';
		  }
		  scoreTable += '</tr></tbody></table>';
		  $('#contentsNav').append(scoreTable);
		  
		  //안내사항
		  examView += '<div class="caution">';
		  examView += '<h1>안내사항</h1>';
		  examView += '<div>수고하셨습니다. 채점은 <strong>수강기간 종료 이후</strong>부터 이루어집니다. <strong>평가 재응시는 불가능합니다</storng>.</div>';
		  examView += '</div>';
		  
		  examView += '<div id="examArea" class="testArea">';

			if(data.dTypeEA != 0){ // 진위형
				$.each(data.dType, function(){
					var commentary = '';
					$('#contentsNav > form select').append('<option value="exam'+this.testSeq+'">'+i+'번</option>');
					examView += '<div id="exam'+this.testSeq+'"';
					if(this.score != 0 && this.score != null){
						examView += 'class="answerTrue"'
					}else if( this.score == null ){
						examView += ''
				    }else{
						examView += 'class="answerFalse"'
					}
					examView += '>'
					examView += '<h1>문제 '+i+'</h1>';
					examView += '<h2>'+this.exam+'</h2>';
					examView += '<h3>';
					if(this.score == null) {
						score = '채점전';
					} else {
						score = this.score;
					}
					if(this.answer == '1') {
						answerOX = 'O';
					} else {
						answerOX = 'X';
					}
					if(this.commentary == null) {
						commentary = '해설이 없습니다.';
					} else {
						commentary = this.commentary;
					}
					examView += '획득점수 : <strong>'+score+'</strong> 배점 : <strong>'+this.baseScore+'</strong> 정답 : <strong>'+answerOX+'</strong>';
					examView += '</h3>';

						if(this.userAnswer == 1) {
							var selectedOX01 = 'checked="checked"';
						} else if(this.userAnswer == 2) {
							var selectedOX02 = 'checked="checked"';
						}
						
						var answerCheck = new Array;
						var h=0;
						for (h = 0; h < 2 ; h ++){
							if(this.answer == h+1 && this.userAnswer == h+1){
								answerCheck[h] = 'class="blue"'
							}else if(this.answer == h+1 && this.userAnswer != h+1){
								answerCheck[h] = 'class="red"'
							}							
						}
						
						examView += '<ol>';
						examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example01'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'1\')" value="1" '+selectedOX01+'/>';
						examView += '<label for="example01'+this.seq+'" '+answerCheck[0]+'>'+this.example01+'</label></li>';
						examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example02'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'2\')" value="2" '+selectedOX02+'/>';
						examView += '<label for="example02'+this.seq+'" '+answerCheck[1]+'>'+this.example02+'</label></li>';
						examView += '</ol>';
						examView += '<div class="commentaryArea">';
						examView += '<h1>문제해설</h1>';
						examView += '<div>'+commentary.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
						examView += '</div>';
						examView += '</div>';
						i++;
				})
			}
			
			if(data.aTypeEA != 0){ // 객관식
				$.each(data.aType, function(){ 
					var commentary = '';
					$('#contentsNav > form select').append('<option value="exam'+this.testSeq+'">'+i+'번</option>');
					examView += '<div id="exam'+this.testSeq+'"';
					if(this.score != 0 && this.score != null){
						examView += 'class="answerTrue"'
					}else if( this.score == null ){
						examView += ''
				    }else{
						examView += 'class="answerFalse"'
					}
					examView += '>'
					examView += '<h1>문제 '+i+'</h1>';
					examView += '<h2>'+this.exam.replace(' ','&nbsp;').replace(/  /g,'&nbsp;&nbsp;')+'</h2>';
					examView += '<h3>';
					if(this.score == null) {
						score = '채점전';
					} else {
						score = this.score;
					}
					if(this.commentary == null) {
						commentary = '해설이 없습니다.';
					} else {
						commentary = this.commentary;
					}
					examView += '획득점수 : <strong>'+score+'</strong> 배점 : <strong>'+this.baseScore+'</strong> 정답 : <strong>'+this.answer+'</strong>';
					//examView += '출제차시 : <strong>'+this.sourceChapter+'차시</strong>';
					examView += '</h3>';

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
						
						var answerCheck = new Array;
						var h=0;
						for (h = 0; h < 5 ; h ++){
							if(this.answer == h+1 && this.userAnswer == h+1){
								answerCheck[h] = 'class="blue"'
							}else if(this.answer == h+1 && this.userAnswer != h+1){
								answerCheck[h] = 'class="red"'
							}else{
								answerCheck[h] = ''
							}
						}
						
						examView += '<ol>';
						examView += '<li><input type="radio" id="example01'+this.seq+'" '+selected01+'/>';
						examView += '<label for="example01'+this.seq+'" '+answerCheck[0]+'>1.&nbsp;'+this.example01+'</label></li>';
						examView += '<li><input type="radio" id="example02'+this.seq+'" '+selected02+'/>';
						examView += '<label for="example02'+this.seq+'" '+answerCheck[1]+'>2.&nbsp;'+this.example02+'</label></li>';
						examView += '<li><input type="radio" id="example03'+this.seq+'" '+selected03+'/>';
						examView += '<label for="example03'+this.seq+'" '+answerCheck[2]+'>3.&nbsp;'+this.example03+'</label></li>';
						examView += '<li><input type="radio" id="example04'+this.seq+'" '+selected04+'/>';
						examView += '<label for="example04'+this.seq+'" '+answerCheck[3]+'>4.&nbsp;'+this.example04+'</label></li>';

						if(this.example05 != undefined){
							examView += '<li><input type="radio" id="example05'+this.seq+'" '+selected05+'/>';
							examView += '<label for="example05'+this.seq+'" '+answerCheck[4]+'>5.&nbsp;'+this.example05+'</label></li>';
						}

						examView += '</ol>';
						examView += '<div class="commentaryArea">';
						examView += '<h1>문제해설</h1>';
						examView += '<div>'+commentary.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
						examView += '</div>';
						examView += '</div>';
						i++;
				})
			}

			if(data.bTypeEA != 0){ // 단답형
				$.each(data.bType, function(){
					var userAnswer = '';
					var commentary = '';
					if(this.userAnswer == null || this.userAnswer == '') {
						userAnswer = '미작성';
					} else {
						userAnswer = this.userAnswer;
					}
					if(this.commentary == null) {
						commentary = '해설이 없습니다.';
					} else {
						commentary = this.commentary;
					}
					$('#contentsNav > form select').append('<option value="exam'+this.testSeq+'">'+i+'번</option>');
					examView += '<div id="exam'+this.testSeq+'"';
					if(this.score != 0 && this.score != null){
						examView += 'class="answerTrue"'
					}else if( this.score == null ){
						examView += ''
				    }else{
						examView += 'class="answerFalse"'
					}
					examView += '>'
					examView += '<h1>문제 '+i+'</h1>';
					examView += '<h2>'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</h2>';
					examView += '<h3>';
					if(this.score == null) {
						score = '채점전';
					} else {
						score = this.score;
					}
					examView += '획득점수 : <strong>'+score+'</strong> 배점 : <strong>'+this.baseScore+'점</strong> 정답 : <strong>'+this.answer+'</strong>';
					//examView += '출제차시 : <strong>'+this.sourceChapter+'차시</strong>';
					examView += '</h3>';
					examView += '<input type="text" value="'+userAnswer+'" />';
					examView += '<div class="commentaryArea">';
					examView += '<h1>문제해설</h1>';
					examView += '<div>'+this.commentary.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
					examView += '</div>';
					examView += '</div>';
					i++;
				})
			}

			if(data.cTypeEA != 0){ // 서술형
				$.each(data.cType, function(){
					var userAnswer = '';
					var commentary = '';
					var correct = '';
					if(this.userAnswer == null || this.userAnswer == '') {
						userAnswer = '미작성';
					} else {
						userAnswer = this.userAnswer;
					}
					if(this.commentary == null) {
						commentary = '해설이 없습니다.';
					} else {
						commentary = this.commentary;
					}
					if(this.correct == null) {
						correct = '첨삭 내용이 없습니다.';
					} else {
						correct = this.correct;
					}
					if(this.score == null) {
						score = '채점전';
					} else {
						score = this.score;
					}
					$('#contentsNav > form select').append('<option value="exam'+this.testSeq+'">'+i+'번</option>');
					examView += '<div id="exam'+this.testSeq+'">'
					examView += '<h1>문제 '+i+' (서술형)</h1>';
					examView += '<h2>'+this.exam+'</h2>';
					examView += '<h3>';
					examView += '획득점수 : <strong>'+score+'</strong> 배점 : <strong>'+this.baseScore+'</strong>';
					//examView += '출제차시 : <strong>'+this.sourceChapter+'차시</strong>';
					examView += '</h3>';
					examView += '<textarea>'+userAnswer+'</textarea>';
					/*
					examView += '<div class="commentaryArea">';
					examView += '<h1>문제해설</h1>';
					examView += '<div>'+commentary.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
					examView += '</div>';			
					*/
					examView += '<div class="commentaryArea">';
					examView += '<h1>첨삭지도</h1>';
					examView += '<div>'+correct.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
					examView += '</div>';
					examView += '</div>';
					i++;
				})
			}
			examView += '</div>';
			examView += '</div>';

		  $('#contentsPopup').html(examView);
		  $('#contentsNav > h1').css('width','580px')
		  window.resizeTo(1030,720);

	  }else{
		  var scoreTable = '';
		  var comment = '';
			var reportScore ='';
		  var i = 1;

			if(data.reportScore == null) {
				reportScore = '채점전';
			} else {
				reportScore = data.reportScore;
			}
		  scoreTable += '<table><thead><tr>';
		  scoreTable += '<th>점수</th>';
		  scoreTable += '</tr></thead><tbody></tr>';
		  scoreTable += '<td>'+reportScore+'</td>';
		  scoreTable += '</tr></tbody></table>';
		  $('#contentsNav').append(scoreTable)

		  examView += '<div class="caution">';
		  examView += '<h1>안내사항</h1>';
		  examView += '<div>수고하셨습니다. 채점은 <strong>수강기간 종료 이후</strong>부터 이루어집니다. <strong>과제 재제출은 불가능합니다</strong>.</div>';
		  examView += '</div>';
		  $.each(data.reportResult, function(){ // 답안지
		      $('#contentsNav > form select').append('<option value="exam'+this.reportSeq+'">'+i+'번</option>')
			  //문제영역
			  examView += '<div id="examArea">';
			  examView += '<div id="exam'+this.reportSeq+'" class="reportArea">';//인덱스용 아이디선언
			  examView += '<h1>과제. '+i+'</h1>';
			  examView += '<h3>';
				if(this.score == null) {
					score = '채점전';
				} else {
					score = this.score;
				}
			  examView += '획득점수 : <strong>'+score+'</strong> 배점 : <strong>'+this.baseScore+'</strong>';
			  examView += '</h3>';
			  examView += '<h2>'+this.exam.replace(/\n/g,'<br />')+'</h2>';
			  //등록문제 다운로드
			  if(this.examAttachLink != null) {
				  examView += '<a href="../lib/fileDownLoad.php?fileName='+this.examAttach+'&link='+this.examAttachLink+'" target="_blank">'+this.examAttach+'</a>';
			  }
				examView += '<div class="exampleArea">';
				examView += '<h1>제출답안</h1>';
					if(this.answerType == 'attach') {
						examView += '<a href="../lib/fileDownLoad.php?fileName='+this.answerAttach+'&link='+this.attachLink+'" target="_blank">제출파일 다운로드 : '+this.answerAttach+'</a>';
					} else {
						examView += '<div>'+this.answerText.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
					}
				examView += '</div>';
				if(this.comment == null) {
					comment = '채점 후 보실 수 있습니다.';
				} else {
					comment = this.comment;
				}
				examView += '<div class="exampleArea">';
				examView += '<h1>첨삭지도</h1>';
				examView += '<div>'+comment.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</div>';
			  examView += '</div>';
			  examView += '</div>';
		  })
		  $('#contentsNav > h1').css('width','1028px');
		  $('#contentsPopup').html(examView);
		  window.resizeTo(1300,720)
	  }
  })
  function findExam(obj){
	  var links = '#'+obj.value;
	  window.location.href = links;
	  var scrollTops = $(window).scrollTop();
	  var plusHeight = $('#contentsNav').height();
	  $(window).scrollTop(scrollTops - plusHeight);
  }
</script>
</head>
<body>
<div id="contentsNav">
  <h2></h2>
  <form id="searchForm">
    <h1>문제 바로가기</h1>
    <select name="examNum" onchange="findExam(this)"></select>
  </form>
</div>
<div id="contentsPopup">
</div>
</body>