<?
	$geturl = explode(".",$_SERVER["HTTP_HOST"]);
	if($geturl[0] == 'm'){
		header("location:/m/");
	}

	$subDomain = $_SERVER['HTTP_HOST'];
	$subDomain = explode(".",$subDomain);
  include '../lib/header.php';
	$query = "SELECT companyName FROM nynCompany WHERE companyID='".$subDomain[0]."'";
	$result = mysql_query($query);
	$rs = mysql_fetch_array($result);
	$companyName = $rs['companyName'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=1080, user-scalable=yes">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title><?=$companyName?> 사이버 교육센터</title>
<link rel="stylesheet" href="../css/studyCenterLayout.css" />
<link rel="stylesheet" href="../css/studyCenterColor.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>"; 	//로그인 유저 이름
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";  //로그인 유저 아이디
  var pageMode = 'studyCenterPage';
  var subDomain = '<?=$_SERVER["HTTP_HOST"] ?>';
	var asc = '';
  subDomain = subDomain.replace('.ncscenter.kr','');
  var contentsMapping = '';
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
	  window.setTimeout("overlap_loginchk()",10000);
  }
  $(document).ready(function(){
	  overlap_loginchk();
  })

  //GNB 교육과정메뉴 출력
  $(window).load(function(){
	  $.get('../api/apiCategory.php',{'value01':'lectureCode', 'companyID':subDomain, 'asc':asc},function(data){
		  var topMenu = '';
		  $.each(data.category, function(){
			  topMenu += '<li onclick="top.location.href=\'lecture.php?sort01='+this.value01+'\'">';
        
			  topMenu += this.value02+'과정';
			  topMenu += '</li>';
		  })
      $('#GNB li.lectureMenu > h1').attr('onClick','top.location.href="lecture.php"');
		  $('#GNB li.lectureMenu ol').html(topMenu)
	  })
  })
</script>
<script type="text/javascript" src="../js/studyCenterUI.js"></script>
<script type="text/javascript" id="crossLogin"></script>
<? $fileName = explode("/",$_SERVER['PHP_SELF']); ?>