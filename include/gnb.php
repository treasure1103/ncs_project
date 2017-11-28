<div id="header">
  <div>
    &nbsp;
  	<? if($_SESSION['loginUserID'] == "" ){ ?>
      <button type="button" onclick="top.location.href='/member/mypage.php'">회원가입</button>
      <button type="button" onclick="top.location.href='/member/login.php?page=<? echo $fileName[1] ?>/<? echo $fileName[2] ?>?<? echo getenv("QUERY_STRING") ?>'">로그인</button>
    <? }else{ ?>
      <strong><?=$_SESSION['loginUserName'] ?></strong>&nbsp;<span>(<?=$_SESSION['loginUserID'] ?>)</span>&nbsp;&nbsp;&nbsp;
      <button type="button" onclick="top.location.href='/member/mypage.php'">개인정보변경</button>
      <button type="button" onClick="logOut();">로그아웃</button>
    <? 
				if($_SESSION['loginUserLevel'] < 9 ){ 				
		?>
			<button type="button" onclick="top.location.href='/admin'">관리자모드</button>
		<? 
				}
		?>
    <? } ?>
  </div>
</div>
<div id="GNB">
  <div>
    <a href="/main/"><img src="../images/global/logo_gnb.png" alt="<?=$_siteName?>" /></a>
    <ul>
      <li>
        <h1 onclick="top.location.href='/about/'">회사소개</h1>
        <ol>
          <li onclick="top.location.href='/about/'">회사소개</li>
          <li onclick="top.location.href='/about/location.php'">찾아오시는 길</li>
          <li onclick="top.location.href='/about/organization.php'">튜터모집</li>
        </ol>
      </li>
      <li>
        <h1 onclick="top.location.href='/eduinfo/'">교육안내</h1>
        <ol>
          <li onclick="top.location.href='/eduinfo/'">위탁교육안내</li>
          <li onclick="top.location.href='/eduinfo/logic.php'">교육진행절차</li>
          <li onclick="top.location.href='/eduinfo/goyong.php'">환급절차</li>
          <li onclick="top.location.href='/eduinfo/rule.php'">교육이용 안내</li>
					<li onclick="top.location.href='/eduinfo/process.php'">과정개발절차</li>
        </ol>
      </li>
      <li class="lectureMenu">
        <h1>교육과정소개</h1>
        <ol>
        </ol>
      </li>
      <li>
        <h1 onclick="top.location.href='/bbs/?boardCode=1'">고객지원</h1>
        <ol>
          <li onclick="top.location.href='/bbs/?boardCode=1'">공지사항</li>
          <li onclick="top.location.href='/bbs/?boardCode=2'">자주묻는질문</li>
          <li onclick="top.location.href='/bbs/mantoman.php'">1:1문의</li>
          <li onclick="top.location.href='/bbs/?boardCode=3'">수강후기</li>
		  <li onclick="window.open('http://367.co.kr/')">PC 원격지원</li>
        </ol>
      </li>
      <li class="studyMenu">
        <h1 onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='/member/login.php?page=study'" <? }else{ ?> "top.location.href='/study/'" <? } ?>>내 강의실</h1>
        <ol>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='/member/login.php?page=study'" <? }else{ ?> "top.location.href='/study/'" <? } ?>>진행중인과정</li>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='/member/login.php?page=study/history.php'" <? }else{ ?> "top.location.href='/study/history.php'" <? } ?>>학습종료과정</li>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='/member/login.php?page=study/studyOrder.php'" <? }else{ ?> "top.location.href='/study/studyOrder.php'" <? } ?>>수강신청내역</li>
          <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='/member/login.php?page=study/mantoman.php'" <? }else{ ?> "top.location.href='/study/mantoman.php'" <? } ?>>상담신청내역</li>
        </ol>
      </li>
    </ul>
  </div>
</div>