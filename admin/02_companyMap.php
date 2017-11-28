<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=640, user-scalable=yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>콘텐츠 매핑</title>
<link rel="stylesheet" href="../css/adminStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../frontScript/studyMapping.js"></script>
<script type="text/javascript">
  var companyID = '<?=$_GET[companyID]; ?>'
  $(document).ready(function(){
    listAct(companyID);
    window.resizeTo(990,700)
  })
</script>
</head>

<body id="mapping">

<div id="contentsArea" class="mapping">
</body>
</html>