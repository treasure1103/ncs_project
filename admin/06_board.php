<? include '_header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var boardCode = '<?=$_GET[boardCode]; ?>'; //검색 페이지
</script>
<script type="text/javascript" src="../lib/SmartEditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/board.js"></script>
<script type="text/javascript" src="../frontScript/boardList.js"></script>
<script type="text/javascript" src="../frontScript/boardWrite.js"></script>
<script type="text/javascript" src="../frontScript/boardView.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1>
  </h1>
  <div id="contentsArea">
  </div>
</div>
<? include '_footer.php' ?>