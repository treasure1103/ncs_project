<? include '../lib/header.php'; ?>
<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=1280, user-scalable=yes">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>학습창</title>
<link rel="stylesheet" href="../css/userStyle.css" />
<script type="text/javascript">
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>"; 	//로그인 유저 이름
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";  //로그인 유저 아이디
  var pageMode = 'userPage';
</script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/userUI.js"></script>
<script type="text/javascript">
  var useApi = '/api/apiStudyChapter.php';
  var progressApi = '/api/apiProgressCheck.php';
  var consultApi = '../api/apiConsult.php';
  var contentsCode = '<?=$_GET[contentsCode]; ?>'; //검색 페이지
  var chapter = '<?=$_GET[chapter]; ?>'; //검색 페이지
  var lectureOpenSeq = '<?=$_GET[lectureOpenSeq]; ?>'; //검색 페이지
  var types = '<?=$_GET[types]; ?>'; //검색 페이지
  var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
  var totalTime = 0;
  var studyChapter = '';
  var sendTime = 10000; //진도체크시간
  var nowPageURL = '';
  var nowPage = '';
  //맴버정보 기록
  
  var loginUserInfo = $.get('../api/apiLoginUser.php',{},function(data){
	  $('input[name="phone01"]').val(data.mobile01);
	  $('input[name="phone02"]').val(data.mobile02);
	  $('input[name="phone03"]').val(data.mobile03);
	  $('input[name="email01"]').val(data.email01);
	  $('input[name="email02"]').val(data.email02);
  })
  .done(function(){
	  var studyPopup = $.get(useApi,{'contentsCode':contentsCode,'chapter':chapter,'lectureOpenSeq':lectureOpenSeq},function(data){
		  var contentsName = data.contentsName;
		  $.each(data.progress, function(){
			  if(types == 'new'){
				  nowPageURL = this.chapterPath;
			  }else{
					if(this.lastPage == null || this.lastPage == '') {
					  nowPageURL = this.chapterPath;
					} else {
					  nowPageURL = this.lastPage;
					}
			  }
			  totalTime = this.totalTime;
			  $('iframe').attr('src',nowPageURL);
			  $('#popupStudyArea > h1').html(contentsName)
			  $('#popupStudyArea > h2').html(this.chapter+'차시 | '+this.chapterName)
			  //$('#popupStudyArea > h5 strong').html(totalTime)
			  $('#popupStudyArea > div li').eq(0).children('h1').after(this.professor.replace(/\n/g,'<br />'))
			  $('#popupStudyArea > div li').eq(1).children('h1').after(this.goal.replace(/\n/g,'<br />'))
			  $('#popupStudyArea > div li').eq(2).children('h1').after(this.content.replace(/\n/g,'<br />'))
			  //$('#popupStudyArea > div li').eq(3).children('h1').after(this.activity.replace(/\n/g,'<br />'))
			  $('input[name="subject"]').val('[&nbsp;'+contentsName+'&nbsp;]&nbsp;'+this.chapter+'차시 질문입니다.')
		  })
		  $('iframe').bind({
			  load:function(){
				  checkSize();
				  checkChapter();
				  progressSend();
				  opener.parent.viewStudyDetail(seq,contentsCode,lectureOpenSeq,'Y');
			  }
		  })
	  })
	  .done(function(){	  
		  //타이머
		  timeCheck = setInterval(function(){timer()},1000);
		  
		  totalTime = Number(totalTime);
		  function timer(){
			  totalTime += 1;		  
			  //각각의 형태만들기
			  hour = Math.floor(totalTime / 3600);
			  minute = Math.floor( (totalTime-(hour*3600)) / 60 );
			  sec = totalTime - (hour*3600) - (minute*60);
	
			  // hh:mm:ss 형태를 유지하기 위해 한자리 수일 때 0 추가
			  if(hour < 10) hour = "0" + hour;
			  if(minute < 10) minute = "0" + minute;
			  if(sec < 10) sec = "0" + sec;
			  $('#popupStudyArea > h5 strong').html(hour+':'+minute+':'+sec);		  
		  }
		  
		  //진도보내기 시간별
		  progressCheck = setInterval(function(){progressSend()},sendTime)	  
	  })
  })
  
  function progressSend(){
	  //alert(lectureOpenSeq+'/'+contentsCode+'/'+chapter+'/'+nowPage+'/'+nowPageURL+'/'+totalTime)
	  var sendData = 'lectureOpenSeq='+lectureOpenSeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&nowPage='+nowPage+'&nowPageURL='+nowPageURL+'&totalTime='+totalTime;
	  $('input[name="addItem01"]').val(nowPageURL)
	  $.post(progressApi,sendData)
  }
  
  function checkChapter(){
	  nowPageURL = frames[0].document.location.href;
	  nowPages = nowPageURL.slice(7);//http자르기
	  var filearr = nowPages.split("/");
	  //페이지진도 계산
	  var selectFile = filearr.length -1;
	  var nowPageFull = filearr[selectFile].replace('.html','').replace('.htm','');
	  var nowPageURLFull = filearr[selectFile];
	  nowPage = Number(nowPageFull.substr(nowPageFull.length-2,2));//페이지 진도
	  
	  //현재 주소값 계산
	  var nowAddress = filearr[0];
	  nowAddress = nowAddress.length;
	  nowPageURL = nowPageURL.slice(7+Number(nowAddress))
  }
  function checkSize(){
	  var flashWidth = $('iframe').contents().find('object').attr('width');
	  var flashHeight = $('iframe').contents().find('object').attr('height');
	  var windowWidth = Number(flashWidth) + 360 ;
	  var windowHeight = Number(flashHeight) + 74;
	  $('iframe').css({'width':flashWidth+'px','height':flashHeight+'px'})
	  $('#studyPopup > div').css({'height':flashHeight+'px'})
	  window.resizeTo(windowWidth,windowHeight)
  }
  function tabAct(num){
	  $('#popupStudyArea > ul li').removeClass('select');
	  $('#popupStudyArea > div, #popupStudyArea > form').css('display','none');
	  if(num == 1){
		  $('#popupStudyArea > ul li').eq(0).addClass('select');
		  $('#popupStudyArea > div').css('display','block');
	  }else{
		  $('#popupStudyArea > ul li').eq(1).addClass('select');
		  $('#popupStudyArea > form').css('display','block');
	  }
  }
  
  function endStudy(){
	  progressSend();
      opener.parent.viewStudyDetail(seq,contentsCode,lectureOpenSeq,'Y');
	  window.close();
  }
  function sendData(apiName,formClass,types){
	  var sendSerial = $('form.'+formClass).serialize();
	  if(confirm("등록하시겠습니까?")){
		  $.ajax({
			  url: apiName,
			  type:'POST',
			  data:sendSerial,
			  dataType:'text',
			  success:function(data){
				  alert('등록되었습니다.');
				  $('textarea[name="content"]').val('')
			  },
			  fail:function(){
				  alert('등록에 실패하였습니다.')
			  }
		  })
	  }
  }
  
//#################################################################################################################################
//
//	중복로그인 체크 해보자
//
//#################################################################################################################################

function overlap_loginchk(){
	jsloginchk.src="/api/crossLogin.php";
	window.setTimeout("overlap_loginchk()",10000);
}

</script>
<script id='jsloginchk'></script>
</head>

<body id="studyPopup" onload="overlap_loginchk();">
  <iframe frameborder="0" scrolling="no"></iframe>
  <div id="popupStudyArea">
    <h5>수강시간 : <strong>00:00:00</strong></h5>
    <h1>과정명과정명</h1>
    <h2>차시수</h2>
    <button type="button" onclick="endStudy()">학습종료</button>
    <ul>
      <li class="select" onclick="tabAct(1)">학습요점</li>
      <li onclick="tabAct(2)">질문하기</li>
    </ul>
    <div>
      <ul>
        <li><h1>내용전문가</h1></li>
        <li><h1>학습목표</h1></li>
        <li><h1>학습내용</h1></li>
        <!--<li><h1>학습활동</h1></li>-->
      </ul>
    </div>
	<form class="writeForm" action="javascript:sendData(consultApi,'writeForm')" style="display:none;">
      <input type="hidden" name="phone01" value="" />
      <input type="hidden" name="phone02" value="" />
      <input type="hidden" name="phone03" value="" />
      <input type="hidden" name="email01" value="">
      <input type="hidden" name="email02" value="">
      <input type="hidden" name="boardType" value="study">
      <input type="hidden" name="userName" value="<?=$_SESSION['loginUserName'] ?>">
      <input type="hidden" name="userID" value="<?=$_SESSION['loginUserID'] ?>">
      <input type="hidden" name="addItem01" value="">
      <input type="hidden" name="subject" value="">
      <h1>학습내용 질문하기</h1>
      <h2>질문내용은 강사님확인 후 <strong class="blue">내 강의실 > 내 질문내역</strong>에서<br />확인가능합니다.</h2>
      <textarea name="content"></textarea>
      <button type="submit">질문하기</button>
    </form>
  </div>
</body>