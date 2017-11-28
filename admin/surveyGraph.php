<? include '_header.php' ?>
<script type="text/javascript">
var userLevel = '<?=$_GET[userLevel]; ?>';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
//관리자 admin / 탈퇴회원 exit
</script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_globalModal.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1></h1>
  <div id="contentsArea">
<body>
	<div id="piechart_3d01" style="width:50%; height:50%;"></div>
	<div id="piechart_3d02" style="width:50%; height:50%;"></div>
	<div id="piechart_3d03" style="width:50%; height:50%;"></div>
	<div id="piechart_3d04" style="width:50%; height:50%;"></div>
		<?
			$queryA = " SELECT A.surveySeq,A.userAnswer, COUNT(A.userAnswer) AS userAnswerC FROM nynSurveyAnswer AS A
									LEFT OUTER
									JOIN nynLectureOpen AS B
									ON A.lectureOpenSeq=B.seq
									LEFT OUTER
									JOIN nynStudy AS C
									ON A.lectureOpenSeq=C.lectureOpenSeq AND A.userID=C.userID
									
									GROUP BY A.surveySeq,A.userAnswer HAVING A.userAnswer > 0 ORDER BY A.surveySeq, A.userAnswer";
			$resultA = mysql_query($queryA);
			$dataA = "['Survey01', 'Count'],";
			$dataB = "['Survey02', 'Count'],";
			$dataC = "['Survey03', 'Count'],";
			$dataD = "['Survey04', 'Count'],";
			while($rsA = mysql_fetch_array($resultA)) {
				SWITCH($rsA['userAnswer']) {
					CASE 1 :
						$surveyTitle = "매우 그렇다.";
						break;

					CASE 2 :
						$surveyTitle = "그렇다.";
						break;

					CASE 3 :
						$surveyTitle = "보통이다.";
						break;

					CASE 4 :
						$surveyTitle = "그렇지 않다.";
						break;

					CASE 5 :
						$surveyTitle = "매우 그렇지 않다.";
						break;
				}
				if($rsA['surveySeq'] == 1) {
					$dataA .= "['".$surveyTitle."', ".$rsA['userAnswerC']."], ";
				}
				if($rsA['surveySeq'] == 2) {
					$dataB .= "['".$surveyTitle."', ".$rsA['userAnswerC']."], ";
				}
				if($rsA['surveySeq'] == 3) {
					$dataC .= "['".$surveyTitle."', ".$rsA['userAnswerC']."], ";
				}
				if($rsA['surveySeq'] == 4) {
					$dataD .= "['".$surveyTitle."', ".$rsA['userAnswerC']."], ";
				}
			}
		?>
		<script>
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
					<?=$dataA?>
        ]);

        var options = {
          title: '1. 본 과정의 전반적인 학습내용에 만족하십니까?',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d01'));

				//PNG로 출력 시작 (삭제하면 html 출력)
				var my_div = document.getElementById('piechart_3d01');

				google.visualization.events.addListener(chart, 'ready', function () {
							my_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
				});
				//PNG로 출력 끝 (삭제하면 html 출력)
        chart.draw(data, options);
      }
		</script>
		<script>
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
					<?=$dataB?>
        ]);

        var options = {
          title: '2. 본 과정의 학습내용이 본인의 업무 활용에 도움이 되십니까?',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d02'));
				var my_div = document.getElementById('piechart_3d02');

				google.visualization.events.addListener(chart, 'ready', function () {
							my_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
				});
        chart.draw(data, options);
      }
		</script>
		<script>
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
					<?=$dataC?>
        ]);

        var options = {
          title: '3. 본 과정의 차시에서 제공되는 보기와 사례 구성이 적절합니까?',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d03'));
				var my_div = document.getElementById('piechart_3d03');

				google.visualization.events.addListener(chart, 'ready', function () {
							my_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
				});
        chart.draw(data, options);
      }
		</script>
		<script>
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
					<?=$dataD?>
        ]);

        var options = {
          title: '4. 학습 시 교육에 대한 안내, 질의 응답, 학습 독려 등이 적절하게 이루어졌다고 생각하십니까?',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d04'));
				var my_div = document.getElementById('piechart_3d04');

				google.visualization.events.addListener(chart, 'ready', function () {
							my_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
				});
        chart.draw(data, options);
      }
		</script>
</body>
</html>
  </div>
</div>
<? include '_footer.php' ?>