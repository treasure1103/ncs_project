<?
	$geturl = explode(".",$_SERVER["HTTP_HOST"]);
	if($geturl[0] == 'm'){
		header("location:/m/");
	}
?>
<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=1280, user-scalable=yes">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title><?=$_siteName?></title>

<!--네이버 검색 메타태그 추가 17.10.24 최원오 대리-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="국비환급 인터넷교육, 인터넷산업안전보건교육, 인터넷성희롱예방교육, 엔씨에스이러닝, 엔시에스이러닝,앤씨에스이러닝, 엔시에스이러닝">
<meta property="og:type" content="website">
<meta property="og:title" content="NCS이러닝센터(신)">
<meta property="og:description" content="국비환급 인터넷교육, 인터넷산업안전보건교육, 인터넷성희롱예방교육, 엔씨에스이러닝, 엔시에스이러닝,앤씨에스이러닝, 엔시에스이러닝">
<meta property="og:image" content="http://www.ncscenter.kr/images/global/logo_gnb.png">
<meta property="og:url" content="http://www.ncscenter.kr/">
<!--네이버 검색 메타태그 추가 17.10.24 최원오 대리-->

<link rel="stylesheet" href="../css/userStyle.css" />

<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var loginUserID = "<?=$_SESSION['loginUserID'] ?>";     	//로그인 유저 아이디
  var loginUserName = "<?=$_SESSION['loginUserName'] ?>"; 	//로그인 유저 이름
  var loginUserLevel = "<?=$_SESSION['loginUserLevel'] ?>";  //로그인 유저 아이디
  var pageMode = 'userPage';
	var subDomain = '<?=$_SERVER["HTTP_HOST"] ?>';
	var loginCompanyID = '<?=$CompanyID ?>';
	var foot_companyID = '<?=$CompanyID ?>';
	var _siteURL = '<?=$_siteURL?>'
	subDomain = subDomain.replace('.'+_siteURL,'');
  if (subDomain == _siteURL){
	  subDomain = '';
  }
  
  // 로그인 체크
  function overlap_loginchk(){
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
	  window.setTimeout("overlap_loginchk()",30000);
  }
  $(document).ready(function(){
	  overlap_loginchk()
  })
  
  //GNB 교육과정메뉴 출력
  $(window).load(function(){
	  $.get('../api/apiCategory.php',{'value01':'lectureCode'},function(data){
		  var topMenu = ''
		  $.each(data.category, function(){
			  topMenu += '<li onclick="top.location.href=\'/lecture/?sort01='+this.value01+'\'">';
			  topMenu += this.value02+'과정';
			  topMenu += '</li>';
		  })
 		  $('#GNB li.lectureMenu > h1').attr('onClick','top.location.href="/lecture/?sort01='+data.category[0].value01+'"');
		  $('#GNB li.lectureMenu ol').html(topMenu)
	  })
	  .done(function(){
		  $.get('../api/apiCompany.php',{'companyID':foot_companyID},function(data){
			  console.log(data.company);
			  var companySel = data.company[0]
			  foot_companyNames = companySel.companyName;
			  foot_companyAddress = companySel.zipCode+' ) '+companySel.address01+'&nbsp;'+companySel.address02;
			  
			  var companyphone01 = ''
			  var companyphone02 = ''
			  if(companySel.phone01 != null && companySel.phone01 != ''){
				  companyphone01 = '+82-'+companySel.phone01.replace("0","")+'-';
				  companyphone02 = companySel.phone01+'.';
			  }
			  foot_companyTel = companyphone01+companySel.phone02+'-'+companySel.phone03;
			  CS_companyTel = companyphone02+companySel.phone02+'.'+companySel.phone03;
			  
			  var companyfax01 = ''
			  var companyfax02 = ''
			  if(companySel.fax01 != null && companySel.fax01 != ''){
				  companyfax01 = '+82-'+companySel.fax01.replace("0","")+'-';
				  companyfax02 = companySel.fax01+'.';
			  }
			  foot_companyFax = companyfax01+companySel.fax02+'-'+companySel.fax03;
			  CS_companyFax = companyfax02+companySel.fax02+'.'+companySel.fax03;
			  
			  var footerAddress = '';
		  })
	  })
  })
</script>
<script type="text/javascript" src="../js/userUI.js"></script>
<script type="text/javascript" id="crossLogin"></script>
<? $fileName = explode("/",$_SERVER['PHP_SELF']); ?>