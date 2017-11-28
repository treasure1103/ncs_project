<div id="LNB">
  <div class="menuArea">
    <h1>내 강의실<br /><span>Study Room</span></h1>
    <ul>
      <li onclick="top.location.href='/study/'">진행중인과정</li>
      <li onclick="top.location.href='/study/history.php'">학습종료과정</li>
      <li onclick="top.location.href='/study/studyOrder.php'">수강신청내역</li>
      <li onclick="top.location.href='/study/mantoman.php'">상담신청내역</li>
    </ul>
  </div>
  <div class="quickHelp">
    <h1>Quick Menu</h1>
    <button type="button" onclick="alert('준비중입니다.');"><img src="../images/global/btn_lnbhelp.png" alt="학습도움말" /><br />학습도움말</button>
    <button type="button" onclick="remoteHelp()"><img src="../images/global/btn_lnbremote.png" alt="원격지원" /><br />원격지원</button>
		</div>
<? include '../include/csCenter.php' ?>
</div>