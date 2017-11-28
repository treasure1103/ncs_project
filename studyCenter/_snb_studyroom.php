<ul id="snb">
  <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=study.php'" <? }else{ ?> "top.location.href='study.php'" <? } ?>>진행중인과정</li>
  <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=history.php'" <? }else{ ?> "top.location.href='history.php'" <? } ?>>학습종료과정</li>
  <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='studyCenterOrder.php'" <? }else{ ?> "top.location.href='studyOrder.php'" <? } ?>>수강신청이력</li>
  <li onclick=<? if($_SESSION['loginUserID'] == "" ){ ?> "top.location.href='login.php?page=mantoman.php'" <? }else{ ?> "top.location.href='mantoman.php'" <? } ?>>상담신청이력</li>
</ul>