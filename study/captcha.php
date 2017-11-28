<? include '../lib/header.php'; ?>
<?
	$contentsCode = $_GET['contentsCode'];
	$lectureOpenSeq = $_GET['lectureOpenSeq'];
	$type = $_GET['type'];
	$chapter = $_GET['chapter'];
	$studySeq = $_GET['studySeq'];
	$viewNew = $_GET['viewNew'];
	$userID = $_SESSION['loginUserID'];

	if(!$_SESSION['loginUserID']){
		echo "<script>alert('세션이 종료 되었습니다. 다시 로그인 해주세요.');window.close();opener.location.href='../member/login.php';</script>";
		exit;
	}
	
	switch ($type){
		case "study" : //중간평가
			$evalCD = "01";
			$chapter = $_GET['chapter'];
			$evalType = "진도_".$chapter;
			break;
		case "final" : //최종평가
			$evalCD = "02";
			$evalType = "시험_1";
			$url = "#";
			break;
		case "report" : //과제
			$evalCD = "03";
			$evalType = "과제_1";
			$url = "#";
			break;
		case "mid" : //중간평가
			$evalCD = "04";
			$evalType = "진행평가_1";
			$url = "#";
			break;
	}
?>
<!doctype html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>캡챠인증</title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var captchaType = <?=$evalCD?>;
	//화면 설명 음성
	getVoice("explain");
	
	//캡챠 이미지 생성
	getCaptcha();
	
	//화면 설명 클릭시
	$("#btnExplain").click(function(e){
		getVoice("explain");
	});
	
	//음성 클릭시
	$("#btnVoice").click(function(e){
		getVoice("captcha");
	});
	
	//새로고침 클릭시
	$("#btnRefresh").click(function(e){
		//입력창 Clear
		$("#user_input").val('');
		//캡차 이미지 새로 생성
		getCaptcha();
	});
	
	//확인 버튼 클릭시
	$("#btnConfirm").click(function(e){
		result();
	})
	window.resizeTo(396,560)
})

/*
* Captcha Image 취득
* 설명 : IMG 태그의 ID가 "captcha_img" 객체를 찾아서 src(경로)값을 변경시킨다
*/
function getCaptcha(){
	$("#captcha_img").attr("src", "https://capt.hrdkorea.or.kr/getCapchaData?agent_id="+$('#agent_id').val()+"&user_agent_pk="+$('#user_agent_pk').val()+"&"+Math.random());
}
	
/*
* 음성 파일 조회 및 실행
* 파라메터 : voiceType -> "explain" or "captcha" or "correct" or "incorrect"
* 설명 : 각 브라우저에 따라 audio 또는 embed 객체를  생성하여 재생한다.
*/

function getVoice(voiceType){
	//동적으로 삽입 될 태그
	var audioObject;
	var parameter = "voiceType="+voiceType+"&agent_id="+$('#agent_id').val()+"&user_agent_pk="+$('#user_agent_pk').val()+"&"+Math.random();
	
	var browserType = getBrowserType();
	
	if(browserType == "IE11" || browserType == "IE10" || browserType == "IE9" || browserType == "Chrome") {
	//if(getBrowserType().indexOf("IE") != -1){
		// console.log("embed tag");
		audioObject = '<embed id="'+voiceType+'" src="https://capt.hrdkorea.or.kr/getCapchaVoice?'+parameter+'" autoplay="true" hidden="true" volume="100" />';
	}else if(browserType == "IE8" || browserType == "IE7"){			  
		audioObject = '<object id="'+voiceType+'" type="audio/x-wav" data="https://capt.hrdkorea.or.kr/getCapchaVoice?'+parameter+'" width="200" height="20" style="display:none">'+
		  '<param name="src" value="https://capt.hrdkorea.or.kr/getCapchaVoice?'+parameter+'">'+
		  '<param name="autoplay" value="true">'+
		  '<param name="autoStart" value="1">'+
		  '</object>';
	}else{
		// console.log("audio tag");
		audioObject = '<audio autoplay="autoplay"><source src="https://capt.hrdkorea.or.kr/getCapchaVoice?'+parameter+'" type="audio/wav" id="'+voiceType+'"></source></audio>';
	}
	//태그가 존재하면 삭제 
	if($("#audio").length)
		$("audio").remove();
	
	//이미지 태그 뒤에 오디오 삽입
	$("#captcha_img").after(audioObject);
};
    

/*
* 인증 결과 확인
* 설명 : 캡챠 인증 처리
*/
function result(){ 	//유저 입력값 설정 (계산된 답)
	$('#captchaInput').val($('#user_input').val());
	var befor_encode_eval_type = $('#eval_type').val();   // eval_type은  훈련기관에서 인코딩하여 보내줘야 한다.
	$('#eval_type').val(encodeURIComponent(befor_encode_eval_type));  //  인코딩 하여 처리
	var evalCD = <?=$evalCD?>;
		
	//크로스 도메인 AJAX 문제를 해결하기 위해 jsonp 를 사용 START
	$.ajax({
		url : 'https://capt.hrdkorea.or.kr/result',
		dataType : "jsonp",
		jsonp: "jsonp_callback",
		type :"GET",
		data : $('#formAgent').serialize(),
		success : function(resultObj){ //인증 결과를 재생
			getVoice(resultObj.result);

			if(evalCD != "01"){
				if(resultObj.result == "correct"){
					$.ajax({
						url : '/api/apiCaptcha.php',
						data : $('#formAgent').serialize(),
						type : 'POST',
						success : function(){
							alert("인증 성공!") //do something(인증 성공 처리)
							//location.href='<?=$url?>';
							opener.parent.openStudyModal('<?=$type?>','<?=$contentsCode?>','<?=$lectureOpenSeq?>');
							window.close();
						}
					})
				}else{
					alert("인증 실패!") //do something(인증 실패 처리)
				}
			}else{
				if(resultObj.result == "correct"){
					$.ajax({
						url : '/api/apiCaptcha.php',
						data : $('#formAgent').serialize(),
						type : 'POST',
						success : function(){
							alert("인증 성공!") //do something(인증 성공 처리)
							opener.parent.studyPop('<?=$studySeq?>','<?=$chapter?>','<?=$_GET['viewNew']?>','Y');
							window.close();
						}
					})
				}else{
					alert("인증 실패!") //do something(인증 실패 처리)
				}
			}
		},
		error : function(request, status, error){
			alert("AJAX ERROR");
			alert("code: "+request.status+"\n"+"message: "+ request.responseText + "\n" + "error :" + error);
		} 
	});
	//인코딩 된 값 원복
	$('#eval_type').val(befor_encode_eval_type);
}; //크로스 도메인 AJAX 문제를 해결하기 위해 jsonp 를 사용 END

/*
* 브라우저 타입을 리턴
* 설명 : navigator.userAgent 값을 비교하여 브라우저를 판별한다
* 리턴값 : (String) 브라우저 명
*/
function getBrowserType(){
	var _ua = navigator.userAgent;
	
	//IE 버젼을 구분하기 위해 tradent를 판별
	var trident = _ua.match(/Trident\/(\d.\d)/i);
	//IE 11 ,10,  9,  8
	if(trident != null){
		if( trident[1] == "7.0" ) return "IE11";
		if( trident[1] == "6.0" ) return "IE10";
		if( trident[1] == "5.0" ) return "IE9";
		if( trident[1] == "4.0" ) return "IE8";
	}
	//IE 7
	if(navigator.appName == 'Microsoft Internet Explorer') return "IE7";
 
	//OTHER
	var agt = _ua.toLowerCase();
	if (agt.indexOf("opera") != -1 || agt.indexOf("opr") != -1) return 'Opera';
	if (agt.indexOf("chrome") != -1) return 'Chrome';
	if (agt.indexOf("staroffice") != -1) return 'Star Office'; 
	if (agt.indexOf("webtv") != -1) return 'WebTV'; 
	if (agt.indexOf("beonex") != -1) return 'Beonex'; 
	if (agt.indexOf("chimera") != -1) return 'Chimera'; 
	if (agt.indexOf("netpositive") != -1) return 'NetPositive'; 
	if (agt.indexOf("phoenix") != -1) return 'Phoenix'; 
	if (agt.indexOf("firefox") != -1) return 'Firefox'; 
	if (agt.indexOf("safari") != -1) return 'Safari'; 
	if (agt.indexOf("skipstone") != -1) return 'SkipStone'; 
	if (agt.indexOf("netscape") != -1) return 'Netscape'; 
	if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla';
	return "Unknown";
} //  * 브라우저 타입을 리턴   End
</script>
<link rel="stylesheet" href="../css/userStyle.css" />
</head>

<body id="captcha">
	<!-- 훈련기관에서 넘겨 줄 파라메터 -->
	<form id='formAgent' name='formAgent' method='POST' accept-charset="utf-8">
      <!-- 인증방법 (ex. A:API,   I: IFrame) -->
      <input type='hidden' id='auth_method' 	name='auth_method' value='A' >
      <!-- 훈련기관 ID-->
      <input type='hidden' id='agent_id' 	name='agent_id' value='ncscenter2' >
      <!-- 회원ID -->
      <input type='hidden' id='user_agent_pk'   name='user_agent_pk' value='<?=$userID?>' >
      <!-- 과정코드 -->
      <input type='hidden' id='course_agent_pk'	name='course_agent_pk' value='<?=$contentsCode?>' >
      <!-- 수업코드 -->
      <input type='hidden' id='class_agent_pk'	name='class_agent_pk' value='<?=$contentsCode?>,<?=$lectureOpenSeq?>' >	
      <!-- 평가구분 코드 (ex. 01:진도,  02:시험,  03:과제,  99:기타) -->
      <input type='hidden' id='eval_cd'	name='eval_cd' value='<?=$evalCD?>' >
      <!-- 평가방법 (ex. '시험_1')-->
      <input type='hidden' id='eval_type'	name='eval_type' value='<?=$evalType?>' >
      <!-- 캡챠 사용자 입력 값 (input)-->
      <input type='hidden' id='captchaInput' name='captchaInput'>
      <!-- 인증 후 돌아갈 페이지 (API 사용시 ''로 설정) -->
      <input type='hidden' id='succ_url' 	name='succ_url' value='' >
      <!-- 인증 실패 시 돌아갈 페이지 (API 사용시 ''로 설정)-->
      <input type='hidden' id='fail_url' 	name='fail_url' value='' >
	</form>

  <div class="header">
    <input type="button" id="btnExplain" value="화면설명" style="cursor:pointer;">
    <h1>자동등록방지(CAPTCHA) 인증</h1>
  </div>
  <div class="info_area">
    <h1>안녕하세요. <?=$_siteName?>입니다.</h1>
    <h2>자동등록방지(CAPTCHA)를 위해 보안절차를 거치고 있습니다</h2>
    <ul>
      <? if($evalCD == 1){ ?>
        <li>진도 : 8차시마다 참여 시 자동등록방지(CAPTCHA) 인증</li>
      <? }else if($evalCD == 2 || $evalCD == 4){ ?>
        <li>시험 : 입장 시 자동등록방지(CAPTCHA) 인증</li>
      <? }else if($evalCD == 3){ ?>
        <li>과제 : 입장 시 자동등록방지(CAPTCHA) 인증</li>
      <? } ?>
      <li>진도는 매번 인증, 시험, 과제 인증은 1일 1회</li>
    </ul>
  </div>
  <div class="capcha_div">
    <div>
      <img id="captcha_img" />
    </div>
    <div>
      <input type="button" id="btnRefresh" value="새로고침" style="cursor:pointer;">
      <input type="button" id="btnVoice" value="음성듣기" style="cursor:pointer;">
    </div>
    <h2>그림에서 나타내는 문자를 입력하세요.</h2>
    <input type="text" id="user_input"/>
    <input type="button" id="btnConfirm" value="전송하기" style="cursor:pointer;">
  </div>
</div>
</body>
</html>