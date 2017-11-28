<?php include_once("/lib/analyticstracking.php");
	$subDomain = $_SERVER['HTTP_HOST'];
	$subDomain = explode(".",$subDomain);
	$query = "select marketerID from nynCompany where companyID='".$subDomain[0]."'";
	$result = mysql_query($query);
	$rs = mysql_fetch_assoc($result);
?>
<div id="Header">
  <div>
	<? if($_SESSION['loginUserID'] == "" ){ ?>
      <button type="button" onclick="top.location.href='login.php?page=<? echo $fileName[2] ?>?<? echo getenv("QUERY_STRING") ?>'">로그인</button>
      <button type="button" onclick="top.location.href='mypage.php'">회원가입</button>
  <? }else{ ?>
      <strong><?=$_SESSION['loginUserName'] ?></strong>&nbsp;<span>(<?=$_SESSION['loginUserID'] ?>)</span>&nbsp;님 환영합니다.&nbsp;&nbsp;&nbsp;
      <button type="button" onclick="top.location.href='mypage.php'">개인정보변경</button>
      <button type="button" onClick="logOut();">로그아웃</button>
  <? } ?>
	   <button type="button" class="favoriteBtn" onclick="addFavorite()">즐겨찾기</button>
  </div>
</div>
<div id="GNB">
  <div>
    <a href="index.php"></a>
    <ul>
				<li>
					<h1 onclick="top.location.href='eduinfo.php'">교육안내</h1>
					<ol>
						<li onclick="top.location.href='eduinfo.php'">위탁교육안내</li>
						<li onclick="top.location.href='logic.php'">교육진행절차</li>
						<li onclick="top.location.href='goyong.php'">환급절차</li>
						<li onclick="top.location.href='rule.php'">교육이용안내</li>
					</ol>
				</li>
      <li class="lectureMenu">
        <h1>교육과정소개</h1>
        <ol></ol>
      </li>
      <li>
        <h1 onclick="top.location.href='bbs.php?boardCode=1'">고객지원</h1>
        <ol>
          <li onclick="top.location.href='bbs.php?boardCode=1'">공지사항</li>
          <li onclick="top.location.href='bbs.php?boardCode=2'">자주묻는질문</li>
          <li onclick="top.location.href='mantomanWrite.php'">1:1문의</li>
        </ol>
      </li>
      <li class="studyMenu">
        <h1 onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=study.php'" <? }else{ ?> "top.location.href='study.php'" <? } ?>>내 강의실</h1>
        <ol>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=study.php'" <? }else{ ?> "top.location.href='study.php'" <? } ?>>진행중인과정</li>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=history.php'" <? }else{ ?> "top.location.href='history.php'" <? } ?>>학습종료과정</li>
					<li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='studyCenterOrder.php'" <? }else{ ?> "top.location.href='studyOrder.php'" <? } ?>>수강신청이력</li>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=mantoman.php'" <? }else{ ?> "top.location.href='mantoman.php'" <? } ?>>상담신청이력</li>
        </ol>
      </li>
    </ul>
  </div>
</div>