<? include '_header.php' ?>

<script type="text/javascript">
if(loginUserLevel == 7){
	top.location.href='../admin/04_study.php?locaSel=0701';
}
</script>
<!-- 우편번호 -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<!-- //우편번호 -->
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/commonCompany.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1></h1>
  <div id="contentsArea"></div>
</div>
<? include '_footer.php' ?>
