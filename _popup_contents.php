<? include '../lib/header.php'; ?>
<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>문제 전체보기</title>
<link rel="stylesheet" href="../css/userStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var codes = "<?=$_GET['codes'] ?>";
  var types = "<?=$_GET['types'] ?>";
  var testType = "<?=$_GET['testType'] ?>";
  var machingApi = '';
	var i = 1;
  testType = testType ? testType : '';
  if(types == 'testWrite'){
	  matchingApi = '../api/apiTest.php';
  }else if(types == 'reportWrite'){
	  matchingApi = '../api/apiReport.php';
  }
  $.get(matchingApi,{'contentsCode':codes, 'testType':testType},function(data){
	  $('#contentsNav > h1').html('[&nbsp;'+data.contentsCode+'&nbsp;]&nbsp;<strong>'+data.contentsName+'</strong>')
	  if( testType == 'mid' ){
		  $('#contentsNav > h1').append('&nbsp<span>|&nbsp;</span>중간평가')
	  }else if( testType == 'final' ){
		  $('#contentsNav > h1').append('&nbsp<span>|&nbsp;</span>최종평가')
	  }else{
		  $('#contentsNav > h1').append('&nbsp<span>|&nbsp;</span>과제')
	  }
	  var examView = '';
	  if( types == 'testWrite'){
		  var scoreTable = '';
		  scoreTable += '<table><thead><tr>';
		  scoreTable += '<th>객관식</th>';
		  scoreTable += '<th>단답식</th>';
		  scoreTable += '<th>서술형</th>';
		  scoreTable += '<th>진위형</th>';
		  scoreTable += '</tr></thead><tbody></tr>';
		  scoreTable += '<td>'+data.aTypeEA+'</td>';
		  scoreTable += '<td>'+data.bTypeEA+'</td>';
		  scoreTable += '<td>'+data.cTypeEA+'</td>';
		  scoreTable += '<td>'+data.dTypeEA+'</td>';
		  scoreTable += '</tr></tbody></table>';
		  $('#contentsNav').append(scoreTable);
		  
		  examView += '<div id="examArea" class="testArea">';
		  $.each(data.test, function(){ // 답안지
		      $('#contentsNav > form select').append('<option value="exam'+this.examNum+'">'+this.examNum+'번</option>');
			  
		      examView += '<div id="exam'+this.examNum+'">'
			  examView += '<h1>문제 '+this.examNum+'</h1>';
			  examView += '<h2>'+this.exam+'</h2>';
			  examView += '<h3>';
			  examView += '배점 : <strong>'+this.score+'점</strong>';
			  examView += '출제차시 : <strong>'+this.sourceChapter+'차시</strong>';
			  examView += '</h3>';
			  if(this.examType=='A'){ // 객관식
				  if(this.answer == 1) {
					  var selected01 = 'checked="checked"';
				  } else if(this.answer == 2) {
					  var selected02 = 'checked="checked"';
				  } else if(this.answer == 3) {
					  var selected03 = 'checked="checked"';
				  } else if(this.answer == 4) {
					  var selected04 = 'checked="checked"';
				  } else if(this.answer == 5) {
					  var selected05 = 'checked="checked"';
				  }
				  examView += '<ol>';
				  examView += '<li><input type="radio" id="example01'+this.seq+'" '+selected01+'/>';
				  examView += '<label for="example01'+this.seq+'">1) '+this.example01+'</label></li>';
				  examView += '<li><input type="radio" id="example02'+this.seq+'" '+selected02+'/>';
				  examView += '<label for="example02'+this.seq+'">2) '+this.example02+'</label></li>';
				  examView += '<li><input type="radio" id="example03'+this.seq+'" '+selected03+'/>';
				  examView += '<label for="example03'+this.seq+'">3) '+this.example03+'</label></li>';
				  examView += '<li><input type="radio" id="example04'+this.seq+'" '+selected04+'/>';
				  examView += '<label for="example04'+this.seq+'">4) '+this.example04+'</label></li>';
				  if(this.example05 != undefined){
				  examView += '<li><input type="radio" id="example05'+this.seq+'" '+selected05+'/>';
					  examView += '<label for="example05'+this.seq+'">5) '+this.example05+'</label></li>';
				  }
				  examView += '</ol>';
			  } else if(this.examType=='B'){ // 단답형
				  examView += '<input type="text" value="'+this.answerText+'" />';
			  } else if(this.examType=='C'){ // 서술형
				  examView += '<textarea>'+this.answerText+'</textarea>';
			  } else if(this.examType=='D'){ // 진위형
				  if(this.answer == 1) {
					  var selectedOX01 = 'checked="checked"';
				  } else if(this.answer == 2) {
					  var selectedOX02 = 'checked="checked"';
				  }
				  examView += '<ol>';
				  examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example01'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'1\')" value="1" '+selectedOX01+'/>';
				  examView += '<label for="example01'+this.seq+'">'+this.example01+'</label></li>';
				  examView += '<li><input type="radio" name="userAnswer'+this.seq+'" id="example02'+this.seq+'" onClick="answerUpdate('+this.orderBy+',\'2\')" value="2" '+selectedOX02+'/>';
				  examView += '<label for="example02'+this.seq+'">'+this.example02+'</label></li>';
				  examView += '</ol>';
			  }
			  examView += '<div class="commentaryArea">';
			  examView += '<h1>문제해설</h1>';
			  examView += '<div>'+this.commentary.replace(/\n/g,'<br />')+'</div>';
			  examView += '</div>';
		      examView += '</div>';
		  })
		  examView += '</div>';
		  $('#contentsPopup').html(examView);
		  $('#contentsNav > h1').css('width','580px')
		  window.resizeTo(1030,720)
	  }else{
		  var scoreTable = '';
		  scoreTable += '<table><thead><tr>';
		  scoreTable += '<th>문항수</th>';
		  scoreTable += '</tr></thead><tbody></tr>';
		  scoreTable += '<td>'+data.totalCount+'</td>';
		  scoreTable += '</tr></tbody></table>';
		  $('#contentsNav').append(scoreTable)
		  $.each(data.report, function(){ // 답안지
		      $('#contentsNav > form select').append('<option value="exam'+this.examNum+'">'+this.examNum+'번</option>')
			  //문제영역
			  examView += '<div id="examArea">';
			  examView += '<div id="exam'+this.examNum+'" class="reportArea">';//인덱스용 아이디선언
			  examView += '<h1>과제. '+ this.examNum +'</h1>';
			  examView += '<h3>';
			  examView += '배점 : <strong>'+this.score+'점</strong>';
			  examView += '출제차시 : <strong>'+this.sourceChapter+'차시</strong>';
			  examView += '</h3>';
			  examView += '<h2>'+this.exam.replace(/\n/g,'<br />')+'</h2>';
			  //등록문제 다운로드
			  if(this.examAttachLink != null) {
				  examView += '<a href="fileDownLoad.php?fileName='+this.examAttach+'&link='+this.examAttachLink+'" target="_blank">'+this.examAttach+'</a>';
			  }
			  examView += '<div class="exampleArea">';
			  examView += '<h1>모범답안</h1>';
			  if(this.exampleAttachLink != null) {
				  examView += '<a href="fileDownLoad.php?fileName='+this.exampleAttach+'&link='+this.exampleAttachLink+'" target="_blank">모범답안 다운로드 : '+this.exampleAttach+'</a>';
			  }
			  examView += '<div>'+this.example.replace(/\n/g,'<br />')+'</div>';
			  examView += '</div>';
			  examView += '<div class="commentaryArea">';
			  examView += '<h1>채점기준</h1>';
			  if(this.rubricAttachLink != null) {
				  examView += '<a href="fileDownLoad.php?fileName='+this.rubricAttach+'&link='+this.rubricAttachLink+'" target="_blank">채점기준 다운로드 : '+this.rubricAttach+'</a>';
			  }
			  examView += '<div>'+this.rubric.replace(/\n/g,'<br />')+'</div>';
			  examView += '</div>';
			  examView += '</div>';
			  examView += '</div>';
		  })
		  $('#contentsNav > h1').css('width','1028px')
		  $('#contentsPopup').html(examView);
		  window.resizeTo(1300,720)
	  }
  })
  function findExam(obj){
	  var links = '#'+obj.value;
	  window.location.href = links
	  var scrollTops = $(window).scrollTop();
	  var plusHeight = $('#contentsNav').height();
	  $(window).scrollTop(scrollTops - plusHeight)
  }
</script>
</head>
<body>
<div id="contentsNav">
  <h1></h1>
  <form id="searchForm">
    <h1>문제 바로가기</h1>
    <select name="examNum" onchange="findExam(this)"></select>
  </form>
</div>
<div id="contentsPopup">
</div>
</body>