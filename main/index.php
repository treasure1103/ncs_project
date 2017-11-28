<? include '../include/header.php' ?>
<script type="text/javascript" src="../frontScript/login.js"></script>
<script type="text/javascript" src="../frontScript/userMain.js"></script>
<script type="text/javascript" src="../js/rollingBanner.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
	  loginScript();
  });

<?
	$today = substr($inputDate,0,10);
	$query = "SELECT * FROM nynPopup WHERE enabled='Y' AND (popupType='All' OR popupType='main') AND (startDate <= '".$today."' AND endDate >= '".$today."')";
	$result = mysql_query($query);
	$_top = 0;
	$_left = 0;
	while($rs = mysql_fetch_array($result)) {
?>
		$(window).load(function(){
			function getCookie(name){
					var nameOfCookie = name + "=";
					var x = 0;
					while (x <= document.cookie.length){
						var y = (x + nameOfCookie.length);
						if (document.cookie.substring(x, y) == nameOfCookie){
						if ((endOfCookie = document.cookie.indexOf(";", y)) == -1){
						endOfCookie = document.cookie.length;
						}
						return unescape (document.cookie.substring(y, endOfCookie));
						}
						x = document.cookie.indexOf (" ", x) + 1;
						if (x == 0) break;
					}
					return "";
				}
				if (getCookie("popname<?=$rs[seq];?>") != "done"){
					window.open("./popup.php?seq=<?=$rs[seq];?>","팝업<?=$rs[seq];?>","width=<?=$rs[width];?>,height=<?=$rs[height];?>,top=<?=$_top;?>,left=<?=$_left;?>,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","popup<?=$rs[seq];?>");
				}
		});
<?
		if($_left > 1280) {
			$_top = $_top+$rs[height];
			$_left = 0;
		} else {
			$_left = $_left+$rs[width];
		}
	}
?>
</script>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="main_contents">
  <ul>
    <? if($_SESSION['loginUserName'] == "" ){ ?>
      <li class="loginArea">
        <h1><strong>Member</strong> Login</h1>
        <form id="login" action="javascript:actLogin()">
          <button type="submit" tabindex="3">로그인</button>
          <input type="text" name="userID" value="아이디" tabindex="1" /><br />
          <input type="text" name="pwd" value="비밀번호" tabindex="2" />
          <div>
            <button type="button" class="useInfo" onClick="top.location.href='/eduinfo/rule.php'">사이트 이용안내</button>
            <button type="button" onClick="top.location.href='/member/mypage.php'">회원가입</button>
            <button type="button" onClick="top.location.href='/member/login.php?mode=findID'">아이디/비밀번호 찾기</button>
          </div>
        </form>
      </li>
    <? }else{ ?>
      <li class="myInformation">
        <h1><strong><?=$_SESSION['loginUserName'] ?></strong>님 환영합니다.<button type="button" onClick="logOut();">로그아웃</button></h1>
        <ul>
          <li onClick="top.location.href='/study/'"><div><img src="../images/main/btn_study.png" alt="내강의실" /></div>내 강의실</li>
          <!--<li onClick="helpDesk()"><div><img src="../images/main/btn_info.png" alt="학습도움말" /></div>학습도움말</li>-->
          <li onClick="alert('준비중입니다.')"><div><img src="../images/main/btn_info.png" alt="학습도움말" /></div>학습도움말</li>
          <li onClick="top.location.href='/member/mypage.php'"><div><img src="../images/main/btn_mypage.png" alt="개인정보변경" /></div>개인정보변경</li>
        </ul>
      </li>
    <? } ?>    
    <li class="slideArea">
      <button class="btn_prev"><img src="../images/main/btn_rollingleft.png" alt="앞으로" /></button>
      <button class="btn_next"><img src="../images/main/btn_rollingright.png" alt="앞으로" /></button>
      <div class="rolling_banner">
        <ul>
          <li onClick="top.location.href='/lecture/?seq=6';"><img src="../images/default/mainrolling03.jpg" alt="사업주 지원카드" /></li>
       </ul>
      </div>
    </li>
    <li class="Customer_Service">
      <a href="http://367.co.kr/">
		<img src="../images/main/customer_service.png" alt="원격지원서비스" />
	  </a>
    </li>
  </ul>
  <div>
    <div>
      <h1>신규 교육 콘텐츠</h1>
      <h2>New Education Contents</h2>
    </div>
    <ul>
		<?
			$queryA = " SELECT A.seq, A.contentsName, A.previewImage, B.value02 AS sort01Name, C.value02 AS sort02Name
									FROM nynContents AS A
									LEFT OUTER
									JOIN nynCategory AS B ON A.sort01=B.value01
									LEFT OUTER
									JOIN nynCategory AS C ON A.sort02=C.value01
									WHERE A.mainContents='Y'
									ORDER BY A.mainOrderBy";
			$resultA = mysql_query($queryA);

			while($rsA = mysql_fetch_array($resultA)) {
		?>
      <li onClick="top.location.href='/lecture/?seq=<?=$rsA[seq]?>'">
        <img src="../images/main/icon_bestbadge.png" />
        <div><img src="/attach/contents/<?=$rsA[previewImage]?>" alt="과정이미지" /></div>
        <h1><?=$rsA[contentsName]?></h1>
        <h2><?=$rsA[sort01Name]?><img src="../images/global/icon_triangle.png" alt="화살표" /><?=$rsA[sort02Name]?></h2>
      </li>
		<?
			}
		?>
    </ul>
  </div>
  <ul>
    <li class="CSCenter">
      <h1>고객센터</h1>
      <h2><?=$_csPhone?></h2>
      <table>
        <tr>
          <th>팩스</th>
          <td><?=$_csFax?></td>
        </tr>
        <tr>
          <th>운영시간</th>
          <td>평일 오전 09:00 ~ 오후 06:00<br />(점심시간 오전 12:00 ~ 오후 01:00)</td>
        </tr>
      </table>
    </li>
	<li class="BBSArea">
      <h1>
        공지사항<span>Notice</span>
        <button type="button" class="btnMore" onClick="top.location.href='/bbs/?boardCode=1'"><img src="../images/main/btn_more.png" /></button>
      </h1>
      <table>
        <colgroup>
          <col width="290px" />
          <col width="78px" />
        </colgroup>
        <tbody>
        </tbody>
      </table>
    </li>
    <li class="downloadArea">
      <a href="./contentsListExcel.php">
			<img src="../images/main/img_download01.png" alt="다운로드 버튼" /></a>
      <h1>교육과정 리스트 다운로드</h1>
      <h2>Download Lecture Lists</h2>
    </li>
    <br />
    <li class="downloadArea">
      <a href="/attach/docs/request_form.zip">
			<img src="../images/main/img_download02.png" alt="다운로드 버튼" /></a>
      <h1>기업회원 교육신청 양식</h1>
      <h2>Request Document Form</h2>
    </li>
</div>
	<div id = "jisa_banner">
	<div id = "jisa_contents_01">
      <h1>지사 리스트</h1>
      <h2>Ncscenter Branch List</h2>
    </div>
	<ul>
	<li class="jisaArea">
	  <img src="../images/main/jisa_image2.png" alt="지사배너" />
      <h1>영등포 지사</h1>
      <h2>02.768.0501</h2>
	  <h3>(Fax)02.6008.8796</h2>
    </li>
    <li class="jisaArea Area_02">
	  <img src="../images/main/jisa_image2.png" alt="지사배너" />
      <h1>구로지사</h1>
      <h2>02.6433.2600</h2>
    </li>
	<li class="jisaArea Area_02">
	  <img src="../images/main/jisa_image2.png" alt="지사배너" />
      <h1>영남지사</h1>
      <h2>053.525.9524</h2>
    </li>
	<li class="jisaArea Area_02">
	  <img src="../images/main/jisa_image2.png" alt="지사배너" />
      <h1>여의도지사</h1>
      <h2>02.2069.0686</h2>
	  <h3>(Fax)070.7405.6364</h2>
    </li>
  </ul>
  </div>
<div id="tail_link_main_00">
	<table id="tail_link_main_01">
	<tr>
		<td align="center" class="tail_link"><a href="http://www.moel.go.kr" target="_blank"><img src="../images/main/taillink1.png" alt="고용노동부" /></a></td>
		<td align="center" class="tail_link"><a href="http://www.hrd.go.kr" target="_blank"><img src="../images/main/taillink2.png" alt="HRD-NET" /></a></td>
		<td align="center" class="tail_link"><a href="https://emon.hrdkorea.or.kr/main" target="_blank"><img src="../images/main/taillink3.png" alt="원격평생교육시설" /></a></td>
		<td align="center" class="tail_link"><a href="http://www.e-simsa.or.kr/index.do" target="_blank"><img src="../images/main/taillink4.jpg" alt="한국산업인력공단" /></a></td>
	</tr>
	</table>
</div>
<? include '../include/footer.php' ?>