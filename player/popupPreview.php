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
	$contentsCode = $_GET['contentsCode'];
	$qChapter = "select previewChapter from nynContents where contentsCode='".$contentsCode."'";
	$result = mysql_query($qChapter);
	$rs = mysql_fetch_array($result);
	if($rs[previewChapter] == null){
		$previewChapter = "1";
	} else {
		$previewChapter= $rs[previewChapter];
	}	

	$sourceType = $_GET['sourceType'];
	if($sourceType == "html5") {
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<? } else { ?>
<meta http-equiv="X-UA-Compatible" content="IE=8">
<? } ?>
<title>학습 플레이어</title>
<link rel="stylesheet" href="../css/userStyle1.css" />
<script type="text/javascript">
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>";
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";
  var pageMode = 'userPage';
</script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/userUI.js"></script>
<script type="text/javascript">
  var useApi = '../api/apiStudyChapterSlim.php';
  var contentsApi = '../api/apiContents.php';
  var consultApi = '../api/apiConsult.php';
  var contentsCode = '<?=$contentsCode; ?>'; //검색 페이지
  var previewChapter = '<?=$previewChapter; ?>'; //미리보기 차시설정값
  var seq = '<?=$_GET[seq]; ?>'; 
  var eduinfoType = 'Y';

  //맴버정보 기록
 
  var studyPopup = $.get(useApi,{'contentsCode':contentsCode,'chapter':previewChapter},function(data){
	  var contentsName = data.contentsName;
	  $.each(data.progress, function(){
		  nowPageURL = this.chapterPath;
		  $('iframe').attr('src',nowPageURL);
		  $('#popupStudyArea > h1').html(contentsName)
		  $('#popupStudyArea > h2').html(this.chapter+'차시 | '+this.chapterName)
		  $('#popupStudyArea > div li').eq(0).children('h1').after(this.professor.replace(/\n/g,'<br />'))
		  $('#popupStudyArea > div li').eq(1).children('h1').after(this.goal.replace(/\n/g,'<br />'))
		  $('#popupStudyArea > div li').eq(2).children('h1').after(this.content.replace(/\n/g,'<br />'))
		  $('input[name="subject"]').val('[&nbsp;'+contentsName+'&nbsp;]&nbsp;'+this.chapter+'차시 질문입니다.')
	  })
	  $('iframe').bind({
		  load:function(){
			  checkSize();
			  opener.parent.viewStudyDetail(seq,contentsCode,lectureOpenSeq,'Y');
		  }
	  })
  })

  function checkSize(){
	  var flashWidth = $('iframe').contents().width();
	  var flashHeight = $('iframe').contents().height();
	  if(flashWidth <= 720){
		  flashWidth = 1024;
	  }
	  if(flashHeight <= 540){
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
	  window.close();
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
</script>
</head>

<body id="studyPopup">
  <iframe frameborder="0" scrolling="no"></iframe><!-- 버튼요소추가 --><button type="button" onclick="eduinfoOpen()"><img src="../images/player/btn_eduinfo_open.jpg" /></button><!-- 오픈,하이드 추가  -->  
  <div id="popupStudyArea">
    <h5><strong>학습하기</strong></h5>
    <h1>과정명</h1>
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
	<form class="writeForm" action="javascript:alert('실제 수강에서만 지원합니다.');" style="display:none;">
      <input type="hidden" name="phone01" value="" />
      <input type="hidden" name="phone02" value="" />
      <input type="hidden" name="phone03" value="" />
      <input type="hidden" name="email01" value="">
      <input type="hidden" name="email02" value="">
      <input type="hidden" name="boardType" value="study">
      <input type="hidden" name="userName" value="<?=$_SESSION['loginUserID'] ?>">
      <input type="hidden" name="userID" value="<?=$_SESSION['loginUserName'] ?>">
      <input type="hidden" name="addItem01" value="">
      <input type="hidden" name="subject" value="">
      <h1>학습내용 질문하기</h1>
      <h2>질문내용은 확인 후 <strong class="blue">내 강의실 > 내 질문내역</strong>에서<br />확인가능합니다.</h2>
      <textarea name="content"></textarea>
      <button type="submit">질문하기</button>
    </form>
  </div>
</body>