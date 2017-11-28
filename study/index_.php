<?
		$sitecode = "BC622";				// NICE로부터 부여받은 사이트 코드
    $sitepasswd = "ogrlhp6YFkrf";			// NICE로부터 부여받은 사이트 패스워드
    
    $authtype = "M";      	// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
    	
		$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지
		
		 
    $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
    
    // 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
		//if (extension_loaded($module)) {// 동적으로 모듈 로드 했을경우
			$reqseq = get_cprequest_no($sitecode);
		//} else {
		//	$reqseq = "Module get_request_no is not compiled into PHP";
		//}
    
    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
    $returnurl = "http://ncscenter.kr/member/checkplus_success.php";	// 성공시 이동될 URL
    $errorurl = "http://ncscenter.kr/member/checkplus_fail.php";		// 실패시 이동될 URL
	
    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
    
    $_SESSION["REQ_SEQ"] = $reqseq;
    // 입력될 plain 데이타를 만든다.
    $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
									"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
									"9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
									"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
									"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
									"11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
									"9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;
    
    
		//if (extension_loaded($module)) {// 동적으로 모듈 로드 했을경우
			$enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);
		//} else {
		//	$enc_data = "Module get_request_data is not compiled into PHP";
		//}

    if( $enc_data == -1 )
    {
        $returnMsg = "암/복호화 시스템 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -2 )
    {
        $returnMsg = "암호화 처리 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -3 )
    {
        $returnMsg = "암호화 데이터 오류 입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -9 )
    {
        $returnMsg = "입력값 오류 입니다.";
        $enc_data = "";
    } else {
			$returnMsg = "";
		}
?>
<? include '../include/header.php' ?>
<!-- 모바일 접근처리 -->
<script language="JavaScript" type="text/JavaScript">
	var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'MOT', 'SAMSUNG', 'SonyEricsson');
			for (var word in mobileKeyWords){
				if (navigator.userAgent.match(mobileKeyWords[word]) != null){
						parent.window.location.href='http://m.ncscenter.kr';break;
				}
			}
</script>
<!--// 모바일 접근처리 -->

<script language='javascript'>
	window.name ="Parent_window";
	
	function fnPopup(){
		window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		document.form_chk.target = "popupChk";
		document.form_chk.submit();
	}
	var returnMsg = '<?= $returnMsg ?>';
	var enc_data = '<?= $enc_data ?>';
	
	function fnPopupipin(){
		window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_ipin.target = "popupIPIN2";
		document.form_ipin.action = "https://cert.vno.co.kr/ipin.cb";
		document.form_ipin.submit();
	}
	
	var sEncData = '<?= $sEncData ?>';
</script>
<script type="text/javascript">

if(loginUserID == ''){
	top.location.href = '/main/'
}
var page = '<?=$_GET[page]; ?>'; 
var seq = '<?=$_GET[seq]; ?>'; 
var lectureOpenSeq = '<?=$_GET[lectureOpenSeq]; ?>'; 
var contentsCode = '<?=$_GET[contentsCode]; ?>'; 
var captchaCnt = 8; //캡챠간격
var maxStudyCnt = 8; //최대진도
//var captchaBanStart = '2017-01-04 12:00:00'; //캡챠미적용 시작시간
//var captchaBanEnd = '2017-01-04 24:00:00'; //캡챠미적용 끝 시간
var captchaBanStart = ''; //캡챠미적용 시작시간
var captchaBanEnd = ''; //캡챠미적용 끝 시간
$(document).ready(function(){
	GNBAct('userGNB');  
});
</script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
<script type="text/javascript" src="../frontScript/studyModal_.js"></script>
<script type="text/javascript" src="../frontScript/userStudy_.js"></script>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/study.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$_siteName?><img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>진행중인 강의</strong></h2>
      <h1>진행중인과정</h1>
      <h3 class="study">현재 <strong><?=$_SESSION['loginUserName'] ?></strong>님은 총 <strong class="blue">3</strong>개의 강의가 진행중입니다.</h3>
    </div>
    <div class="noticeArea">
      <img src="../images/study/img_notice.png" alt="주의" />
      <h1>강의 주의 사항</h1>
      <p>모든 수강과정의 평가응시와 과제제출은 진도율이 <strong>80% 이상</strong> 되어야 가능합니다.<br />순차학습으로 진행되며 <strong>일일 진도제한은 8차시</strong> 입니다.</p>
    </div>
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>
<? include '../include/footer.php' ?>