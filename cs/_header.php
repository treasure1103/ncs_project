<? include '../lib/header.php' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>이상에듀 관리자 시스템</title>
<link rel="stylesheet" href="../css/adminStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var locaSel = '<?=$_GET[locaSel]; ?>'
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>"; 	//로그인 유저 이름
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";  //로그인 유저 아이디
  var loginCompanyID = '<?=$CompanyID;?>';  //로그된 회사 아이디
  if(loginUserID == '' || loginUserLevel > 8){
	  alert('로그인 상태가 아니거나 접근 권한이 없습니다.');
	  top.location.href='/index.html';	  
  }
  var pageMode = 'adminPage';

  // 로그인 체크
  function overlap_loginchk(){
	  //jsloginchk.src="../api/crossLogin.php";
	  $.ajax({
		  url:'../api/crossLogin.php',
		  dataType:'JSON',
		  success:function(data){
			  if(data.result != 'success'){
				  alert(data.result);
				  logOut();
			  }
		  }
	  })
	  window.setTimeout("overlap_loginchk()",60000);
  }
  $(document).ready(function(){
	  overlap_loginchk()
  })
</script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
<script type="text/javascript" src="../js/adminUI.js"></script>
<script type="text/javascript" id="crossLogin"></script>