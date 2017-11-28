<? include '_header.php' ?>
<script type="text/javascript" src="../frontScript/login.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
	  loginScript();	  
  });
  studyCenterApi.always(function(data){
	  var studyRequestStart = data.studyCenter[0].studyRequestStart;
	  var studyRequestEnd = data.studyCenter[0].studyRequestEnd;
		var marketerID = data.marketerID;
	  if(studyRequestStart != null && studyRequestEnd != null){
		  var requestStartYear = studyRequestStart.substr(0,4)
		  var requestStartMonth = studyRequestStart.substr(5,2)
		  var requestStartDay = studyRequestStart.substr(8,2)
		  studyRequestStart = requestStartYear +'.'+ requestStartMonth +'.'+ requestStartDay;
		  
		  var requestEndYear = studyRequestEnd.substr(0,4)
		  var requestEndMonth = studyRequestEnd.substr(5,2)
		  var requestEndDay = studyRequestEnd.substr(8,2)
		  studyRequestEnd = requestEndYear +'.'+ requestEndMonth +'.'+ requestEndDay;
		  
			if(studyRequestStart=='1900.01.01' || studyRequestEnd == '1900.01.01'){
				var studyRequestDay = '상시 접수';
			} else {
				var studyRequestDay = studyRequestStart +' ~ '+ studyRequestEnd
			}  	
	  }else{
		  var studyRequestDay = '상시 접수'
	  }
	  
	  var studyStart = data.studyCenter[0].studyStart;
	  var studyEnd =  data.studyCenter[0].studyEnd;
	  if(studyStart != null && studyEnd != null){
		  startYear = studyStart.substr(0,4)
		  startMonth = studyStart.substr(5,2)
		  startDay = studyStart.substr(8,2)
		  studyStart = startYear +'.'+ startMonth +'.'+ startDay;
		  endYear = studyEnd.substr(0,4)
		  endMonth = studyEnd.substr(5,2)
		  endDay = studyEnd.substr(8,2)
		  studyEnd = endYear +'.'+ endMonth +'.'+ endDay;

			if(studyStart=='1900.01.01' || studyEnd=='1900.01.01'){
				var studyDay = '상시 접수';
			} else {
				var studyDay = studyStart +' ~ '+ studyEnd;
			}
	  }else{
  		  var studyDay = '진행중인 교육이 없습니다.';
	  }
	  
	  $('.dateInfo > ul li:eq(0) h3').html(studyRequestDay);
	  $('.dateInfo > ul li:eq(1) h3').html(studyDay);

	  var mainContents = '';
	  mainContents += '<img src="/attach/contents/'+data.studyCenter[0].mainContents[0].previewImage+'" alt="추천과정이미지" />';
	  mainContents += '<h3>추천 교육과정</h3>';
	  mainContents += '<h2>'+data.studyCenter[0].mainContents[0].contentsName+'</h2>';
	  mainContents += '<p>'+data.studyCenter[0].mainContents[0].intro+'</p>';
	  if(data.studyCenter[0].mainContents[0].seq != ''){
		  mainContents += '<button type="button" onclick="top.location.href=\'lecture.php?seq='+ data.studyCenter[0].mainContents[0].seq +'\'">바로가기</button>&nbsp;';
		  mainContents += '<button type="button" class="btnPreview" onclick="studyPop(\''+ data.studyCenter[0].mainContents[0].contentsCode +'\');">미리보기</button>';
	  }
	  $('.favoriteStudy').html(mainContents);

	  var bbsAjax = $.get('/api/apiBoard.php',{'boardCode':'1','list':'4'},function(data){
		  var lists = ''
		  $.each(data.board, function(){
			  lists += '<li onClick="top.location.href=\'bbs.php?boardCode=1&seq='+this.seq+'\'">';
			  lists += '<h1>'+this.subject+'</h1>';
			  lists += this.inputDate.substr(0,10);
			  lists += '</li>';
		  })
		  //alert(lists)
		  $('.BBSArea ul').html(lists)
	  })
  })
  function studyPop(contentsCode){
		$.get('/api/apiChapter.php',{'contentsCode':contentsCode},function(data){
			cont = data.chapter[0].player;
		  popupAddress = cont+'/player/popupPreview.php?contentsCode='+contentsCode+'&chapter=1';
		  window.open(popupAddress,"학습창","location=yes,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","study")
		})
  }

	function bannerScript(){
			var imgArr = ['../images/main/img_topbanner02.png'];//,'../images/main/img_topbanner01.png'
			var imgUrl = ['./20170308.php'];//,'../safety/'
			var fadeCount = 0;

			setInterval(function(){
					if(fadeCount == 0){
							$('#topBannerArea > a').fadeOut(300,function(){
									$(this).attr('href',imgUrl[0]).fadeIn(300);
									fadeCount = 1;
							});
							$('#topBannerArea > a > img').fadeOut(300,function(){
									$(this).attr('src',imgArr[0]).fadeIn(300);
									fadeCount = 1;
							});
					}else{
							$('#topBannerArea > a').fadeOut(300,function(){
									$(this).attr('href',imgUrl[1]).fadeIn(300);
									fadeCount = 0;
							});
							$('#topBannerArea > a > img').fadeOut(300,function(){
									$(this).attr('src',imgArr[1]).fadeIn(300);
									fadeCount = 0;
							});
					}
			},5000)
			
	}
	
	function remoteHelp(){
		window.open("http://367.co.kr/","원격지원센터","top=0,left=0,width=1080,height=700,menubar=no,status=no,titlebar=no,toolbar=no,scrollbars=yes,resizeable=no","study")
}
</script>
</head>

<body>
<? include '_gnb.php'; ?>
<div id="main_contents">
  <img alt="사이버교육센터에 오신것을 환영합니다." />
  <ul id="mooyoungFloat">
    <? if($_SESSION['loginUserName'] == "" ){ ?>
      <li class="loginArea">
        <h1><strong>Member</strong> Login</h1>
        <form id="login" action="javascript:actLogin()">
          <button type="submit" tabindex="3">로그인</button>
          <input type="text" name="userID" value="아이디" tabindex="1" /><br />
          <input type="text" name="pwd" value="비밀번호" tabindex="2" />
          <div>
            <button type="button" onClick="top.location.href='mypage.php'">회원가입</button>
            <button type="button" onClick="top.location.href='login.php?mode=findID'">아이디/비밀번호 찾기</button>
          </div>
        </form>
      </li>
    <? } else { ?>
      <li class="myInformation">
        <h1><strong><?=$_SESSION['loginUserName'] ?></strong>님 환영합니다.<button type="button" onClick="logOut();">로그아웃</button></h1>
        <ul>
          <li onClick="top.location.href='study.php'"><div><img src="../images/studycenter/btn_main01.png" alt="내강의실" /></div>내 강의실</li>
          <!--<li onClick="top.location.href='#'"><div><img src="../images/studycenter/btn_main02.png" alt="학습도움말" /></div>학습도움말</li>-->
          <li onClick="top.location.href='mypage.php'"><div><img src="../images/studycenter/btn_main03.png" alt="개인정보변경" /></div>개인정보변경</li>
        </ul>
      </li>
			
    <? } ?>
    <li class="favoriteStudy">
    </li>
		<li class="btnBlock" onClick="top.location.href='lecture.php'">
      <div></div>
      <h2>어떤 교육과정이 있는지 궁금하신가요?</h2>
      <h1>교육 과정 보기</h1>
    </li>
  </ul>
  <ul>
    <li class="dateInfo">
      <h2>교육 신청/진행 기간 안내</h2>
      <ul>
				<li>
          수강신청기간
          <h3></h3>
        </li>
        <li>
          교육기간
          <h3></h3>
        </li>
      </ul>
		   <button type="button" onClick="remoteHelp()">원격지원</button>
			 <!-- <button type="button" onClick="top.location.href='/attach/docs/request.zip'">수강신청서 다운로드</button> -->
    </li>
    <li class="BBSArea">
      <h1>
        <strong>공지사항</strong><span>Notice</span>
        <button type="button" class="btnMore" onClick="top.location.href='bbs.php?boardCode=1'">+</button>
      </h1>
      <ul></ul>
    </li>
    <li class="CSCenter">

    </li>		
  </ul>
</div>
<? include '_footer.php'; ?>