<div id="header">
  <div>
    <img onclick="top.location.href='00_index.php'" src="../images/global/logo_gnb.png" />
    <h1>관리자 시스템</h1>
  </div>
  <ul class="apiGNB">
  </ul>
</div>
<div id="subMenu">
  <div>
    <h1><strong><?=$_SESSION['loginUserName'] ?></strong>님<br />환영합니다.</h1>
    <h2>등급 [<?=$_SESSION['loginUserLevelName'] ?>]</h2>
    <button type="button" onClick="logOut();">로그아웃</button>
  </div>
  <ul class="apiSubMenu">
  </ul>
</div>