<? include '_header.php' ?>
<? 	$surveySeq = $_GET['surveySeq']; ?>
<script type="text/javascript">
var userLevel = '<?=$_GET[userLevel]; ?>';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var surveySeq = '<?=$_GET[surveySeq]; ?>'; 
//관리자 admin / 탈퇴회원 exit
</script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_globalModal.js"></script>
<script type="text/javascript" src="../frontScript/essayFormList.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1></h1>
  <div id="contentsArea">
  </div>
</div>
<? include '_footer.php' ?>