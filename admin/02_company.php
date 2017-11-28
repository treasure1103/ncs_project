<? include '_header.php' ?>
<script type="text/javascript">
var userLevel = '<?=$_GET[userLevel]; ?>';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
//관리자 admin / 탈퇴회원 exit
</script>
<!-- 우편번호 -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<!-- //우편번호 -->
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_calendar.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_globalModal.js"></script>
<script type="text/javascript" src="../frontScript/company.js"></script>
<script type="text/javascript" src="../frontScript/companyList.js"></script>
<script type="text/javascript" src="../frontScript/companyWrite.js"></script>
<script type="text/javascript" src="../frontScript/studyCenter.js"></script>

</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1></h1>
  <div id="contentsArea">
  </div>
</div>
<? include '_footer.php' ?>
