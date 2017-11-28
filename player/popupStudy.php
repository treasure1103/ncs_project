<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=1280, user-scalable=yes">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<?
	$sourceType = $_POST['sourceType']; 
	if($sourceType == "html5") {
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<? } else { ?>
<meta http-equiv="X-UA-Compatible" content="IE=8">
<? } ?>
<title>학습 플레이어</title>
<link rel="stylesheet" href="../css/userStyle1.css" />
<script type="text/javascript">
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>"; 	//로그인 유저 이름
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";  //로그인 유저 아이디
  var pageMode = 'userPage';
</script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/userUI.js"></script>
<script type="text/javascript">
  var useApi = '../api/apiStudyChapter.php';
  var progressApi = '/api/apiProgressCheck.php';
  var consultApi = '../api/apiConsult.php';
  var contentsCode = '<?=$_POST[contentsCode]; ?>'; //검색 페이지
  var chapter = '<?=$_POST[chapter]; ?>'; //검색 페이지
  var lectureOpenSeq = '<?=$_POST[lectureOpenSeq]; ?>'; //검색 페이지
  var types = '<?=$_POST[types]; ?>'; //검색 페이지
  var seq = '<?=$_POST[seq]; ?>'; //검색 페이지
  var subDomain = '<?=$_POST[subDomain]; ?>'; //검색 페이지
  var totalTime = 0;
  var studyChapter = '';
  var sendTime = 10000; //진도체크시간
  var nowPageURL = '';
  var nowPage = '';
  var progressCheck = '';
  var eduinfoType = 'Y';
  var timeCheck = null;
  var progressTime = null;
	var sourceType = '<?=$sourceType?>';

  //맴버정보 기록
  //alert(contentsCode)
  
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
		  var progressCheck = data.progressCheck;
			var attachFile = '';
			if(data.attachFile == '' || data.attachFile == null) {
				attachFile = '등록된 학습자료가 없습니다.';
			} else {
				attachFile = '<a href="/attach/contents/'+data.attachFile+'" target="_blank">'+data.attachFile+'</a>';
			}
			$('#popupStudyArea > div li').eq(0).children('h1').after(attachFile);

		  $.each(data.progress, function(){
			  if(types == 'new'){
				  nowPageURL = this.player + this.chapterPath;
			  }else{
					if(this.lastPage == null || this.lastPage == '') {
					  nowPageURL = this.player + this.chapterPath;
					} else {
					  nowPageURL = this.player + this.lastPage;
					}
			  }
			  nowPageURL
			  totalTime = this.totalTime;
			  $('iframe').attr('src',nowPageURL);
			  $('#popupStudyArea > h1').html(contentsName);
			  var chapterNum = this.chapter;
			  if(chapterNum >= 100){
				  chapterNum = '-';
			  }
			  $('#popupStudyArea > h2').html(chapterNum+'차시 | '+this.chapterName)
			  $('#popupStudyArea > div li').eq(1).children('h1').after(this.professor.replace(/\n/g,'<br />'));
			  $('#popupStudyArea > div li').eq(2).children('h1').after(this.goal.replace(/\n/g,'<br />'));
			  $('#popupStudyArea > div li').eq(3).children('h1').after(this.content.replace(/\n/g,'<br />'));
			  $('input[name="subject"]').val('[&nbsp;'+contentsName+'&nbsp;]&nbsp;'+this.chapter+'차시 질문입니다.');
		  })
		  $('iframe').bind({
			  load:function(){
				  checkSize();
				  checkChapter();
				  progressSend();
			  }
		  })
	  })
	  .done(function(){	  
		  //타이머
		  clearInterval(timeCheck);
		  clearInterval(progressTime);
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
		  progressTime = setInterval(function(){progressSend()},sendTime)	  
	  })
  })
  
  function progressSend(){
	  var sendData = 'lectureOpenSeq='+lectureOpenSeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&nowPage='+nowPage+'&nowPageURL='+nowPageURL+'&totalTime='+totalTime+'&progressCheck='+progressCheck;
	  $('input[name="addItem01"]').val(nowPageURL);
	  $.post(progressApi,sendData);
  }
  
  function checkChapter(){
	  nowPageURL = frames[0].document.location.href;
	  nowPages = nowPageURL.slice(7);//http자르기
	  var filearr = nowPages.split("/");
	  //페이지진도 계산
	  var selectFile = filearr.length -1;
	  var nowPageFull = filearr[selectFile].split(".");
	  var nowPageURLFull = filearr[selectFile];
	  nowPage = Number(nowPageFull[0].substr(nowPageFull[0].length-2,2));//페이지 진도
	  
	  //현재 주소값 계산
	  var nowAddress = filearr[0];
	  nowAddress = nowAddress.length;
	  nowPageURL = nowPageURL.slice(7+Number(nowAddress))
  }
  function checkSize(){
	  var flashWidth = $('iframe').contents().width();
	  var flashHeight = $('iframe').contents().height();
	  if(flashWidth <= 500){
		  flashWidth = 1024;
	  }
	  if(flashHeight <= 300){
		  flashHeight = 768;
	  }
	  var windowWidth = Number(flashWidth) ;
	  if(eduinfoType == 'Y'){
		  windowWidth = windowWidth + 370;
	  }else{
		  windowWidth = windowWidth + 30;
	  }
	  var windowHeight = Number(flashHeight) + 66;
	  $('iframe').css({'width':flashWidth+'px','height':flashHeight+'px'})
	  $('#studyPopup > div').css({'height':flashHeight+'px','margin-left':(flashWidth+10)+'px'})//20161118 Small 스크린용 스크립트
	  $('body').css({'width':windowWidth+'px'})//20161118 Small 스크린용 스크립트
	  $('#studyPopup > button').css({'height':flashHeight+'px'})//20161123 상세정보 보기 닫기 버튼
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
	  window.close();
  }
  function sendData(apiName,formClass,types){	  
	  if($('textarea[name="content"]').val() != ''){
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
					  alert('등록에 실패하였습니다.');
				  }
			  })
			  
		  }
	  }else{
		 alert('질문내용을 입력해주세요');
	  }
  }

	function overlap_loginchk(){
		$.ajax({
			url:'../api/crossLogin.php',
			dataType:'JSON',
			success:function(data){
				if(data.result != 'success'){
					alert(data.result);
					self.close();
				}
			}
		})
		window.setTimeout("overlap_loginchk()",10000);
	}
	
  //버튼동작
  function eduinfoOpen(){
	  var infoAct = $('#popupStudyArea').css('display');
	  var flashWidth = $('iframe').contents().width();
	  var flashHeight = $('iframe').contents().height() + 66;
	  if(infoAct == 'block'){
		  $('#popupStudyArea').css('display','none');
		  flashWidth = Number(flashWidth) + 30;
		  $('#studyPopup > button img').attr('src','btn_eduinfo_close.jpg')
		  eduinfoType = 'N'
	  }else{
		  $('#popupStudyArea').css('display','block');
		  flashWidth = Number(flashWidth) + 370;
		  $('#studyPopup > button img').attr('src','btn_eduinfo_open.jpg')
		  eduinfoType = 'Y'
	  }
	  $('body').css('width',flashWidth+'px');
	  window.resizeTo(flashWidth,flashHeight)
  }

	if(sourceType == 'book' || sourceType == 'html5') {
		// Internet Explorer 버전 체크
		var IEVersionCheck = function() {
		var word;
		var version = "N/A";
		var agent = navigator.userAgent.toLowerCase();
		var name = navigator.appName;

			// IE old version ( IE 10 or Lower )
			if ( name == "Microsoft Internet Explorer" ) word = "msie ";
			else {
				 // IE 11
				 if ( agent.search("trident") > -1 ) word = "trident/.*rv:";
				 // IE 12  ( Microsoft Edge )
				 else if ( agent.search("edge/") > -1 ) word = "edge/";
			}

			var reg = new RegExp( word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})" );
			if (  reg.exec( agent ) != null  )
				 version = RegExp.$1 + RegExp.$2;
			return version;
		};
		//document.write(IEVersionCheck());
		if(IEVersionCheck() == '9.0' || IEVersionCheck() == '8.0' || IEVersionCheck() == '7.0') {
			alert('현재 사용중인 인터넷 브라우저에서는 원활한 강의 재생이 되지 않을 수 있습니다. 크롬 브라우저를 사용하시기 바라며 설치가 되어 있지 않다면 네이버나 다음에서 크롬을 검색하여 설치하시기 바랍니다');
		}
	}
</script>
</head>

<body id="studyPopup" onload="overlap_loginchk();">
  <iframe frameborder="0" scrolling="no"></iframe><!-- 버튼요소추가 --><button type="button" onclick="eduinfoOpen()"><img src="../images/player/btn_eduinfo_open.jpg" /></button><!-- 오픈,하이드 추가  -->  
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
				<li><h1>학습자료 받기</h1></li>
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