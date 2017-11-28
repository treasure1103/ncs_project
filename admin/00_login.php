<? include '../lib/header.php' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_siteName?> 관리자 시스템</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=1280, user-scalable=yes">
<link rel="stylesheet" href="../css/adminStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var pageMode='adminPage';
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  if(loginUserID != ''){
	  top.location.href='../admin/00_index.php'
  }
  var subDomain = '<?=$_SERVER["HTTP_HOST"] ?>';
  subDomain = subDomain.replace('.<?=$_siteURL?>','');
  $(document).ready(function(){
	  if(subDomain != 'tutor'){
		  $('#loginPage > div > h1').html('관리자 모드 로그인')
	  }else{
		  $('#loginPage > div > h1').html('교·강사 모드 로그인')
	  }
  });
</script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../frontScript/login.js"></script>
</head>
<body id="loginPage">
<div>
  <img src="../images/global/logo_gnb.png" alt="로고" />
  <h1></h1>
  <div class="loginArea">
    <p>
      <strong>아이디</strong>와 <strong>비밀번호</strong>를 정확히 입력하신 후 로그인<br />버튼을 눌러주시기 바랍니다. 
    </p>
    <form id="login" action="javascript:actLogin()">
      <button type="submit" tabindex="3"><img src="../images/admin/btn_login.png" /><br />로그인</button>
      <h2>아이디</h2><input type="text" name="userID" tabindex="1" /><br />
      <h2>비밀번호</h2><input type="password" name="pwd" tabindex="2" />
    </form>
  </div>
</div>
</body>
</html>