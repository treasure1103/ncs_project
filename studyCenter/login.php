<? include '_header.php' ?>
<script type="text/javascript">
  var mode = '<?=$_GET[mode]; ?>'
  var page = '<?=$_GET[page]; ?>'
  mode = mode ? mode : '';
  if(loginUserID != ''){
	  alert(loginUserName+'님은 이미 로그인되어있습니다.')
	  top.location.href='index.php'
  }
  $(document).ready(function() {
	  loginPage(mode,page);
	  loginScript();
  });
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
</script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../frontScript/login.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="loginArea">
  <!--
  <img src="../images/member/title_login.png" alt="로그인" />
  <form id="login" action="javascript:actLogin(page)">
    <h1>이상에듀의 교육시스템의 원활한 이용을 위해서는<br />로그인이 필요합니다.</h1>
    <input type="text" value="아이디" name="userID" />
    <input type="text" value="패스워드" name="pwd" />
    <button type="submit"><img src="../images/member/btn_login.png" alt="로그인" />로그인</button>      
  </form>
  <a href="#">아이디 찾기</a>
  |
  <a href="#">비밀번호 찾기</a>
  |
  <a href="#">회원가입</a>
  -->
</div>

<? if($subDomain[0] == "mooyoung"){ ?>
		<div style="margin-top:20px;"></div>
		</body>
	</html>
<? } else { 
	include '_footer.php' ;
} ?>