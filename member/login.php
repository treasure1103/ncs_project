<? include '../include/header.php' ?>
<script type="text/javascript">
  var mode = '<?=$_GET[mode]; ?>'
  var page = '<?=$_GET[page]; ?>'
  mode = mode ? mode : '';
  if(loginUserID != ''){
	  alert(loginUserName+'님은 이미 로그인되어있습니다.')
	  top.location.href='/main/'
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
<? include '../include/gnb.php' ?>
<div id="loginArea">
</div>

<? include '../include/footer.php' ?>