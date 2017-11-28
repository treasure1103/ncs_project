<div id="LNB">
  <div class="menuArea">
    <h1>마이페이지<br /><span>My Page</span></h1>
    <ul>
	  <? if($_SESSION['loginUserID'] == "" ){ ?>
        <li onclick="top.location.href='/member/mypage.php'">회원가입</li>
      <? }else{ ?>
        <li onclick="top.location.href='/member/mypage.php'">개인정보변경</li>
        <li onclick="top.location.href='/member/withdrawal.php'">회원탈퇴</li>
      <? } ?>
    </ul>
  </div>
<? include '../include/csCenter.php' ?>
</div>