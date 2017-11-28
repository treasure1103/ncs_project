<? include '../lib/header.php' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>이상에듀 상담 시스템</title>
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
<script type="text/javascript" id="crossLogin"></script>

<script type="text/javascript">
  var page = 1;
  var listCount = 10;
</script>
<script type="text/javascript" src="csScript.js"></script>
<link rel="stylesheet" href="csStyle.css" />
<script type="text/javascript" src="../frontScript/_pager.js"></script>
</head>

<body>
<div id="csCenter">
  <form method="post" class="csInput" action="javascript:inputDate('csInput')">
    <input type="hidden" name="addItem01" value="<?=$_SESSION['loginUserName'] ?>" />
    <h1>이상에듀 상담시스템</h1>
    <ul>
      <li>
        <h1>상담자명</h1>
        <input type="text" name="userName" />
      </li>
      <li>
        <h1>연락처</h1>
        <input type="tel" name="phone01" />&nbsp;-&nbsp;
        <input type="tel" name="phone02" />&nbsp;-&nbsp;
        <input type="tel" name="phone02" />&nbsp;-&nbsp;
      </li>
      <li>
        <h1>이메일</h1>
        <input type="text" name="email01" />&nbsp;@&nbsp;
        <input type="text" name="email02" />
      </li>
      <li>
        <h1>첨부파일</h1>
        <input type="file" name="attachFile01" />
      </li>
      <li>
        <h1>처리담당자</h1>
        <input type="text" name="addItem02" />
      </li>
      <li>
        <h1>상담내용</h1>
        <textarea name="content"></textarea>
      </li>
    </ul>
    <button type="submit">작성완료</button>
  </form>
  <div class="content">
    <form method="get">
      <select name="searchType">
        <option name="userID">ID</option>
        <option name="userName">상담자명</option>
        <option name="content">상담내용</option>
      </select>
      <button type="submit">검색</button>
    </form>
    <ul class="csList"></ul>
    <button type="button" onClick="moreList()"></button>
  </div>
</div>