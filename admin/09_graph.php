<? include '_header.php' ?>
<script type="text/javascript">
var userLevel = '<?=$_GET[userLevel]; ?>';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
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
		<div id="chartLogin"></div>
		<div id="chartProgress"></div>
		<div id="chartTestTime"></div>
		<div id="chartTestWeek"></div>
		<div id="chartReportTime"></div>
		<div id="chartReportWeek"></div>
		<div id="piechart_3d"></div>
		<?
			//로그인 시간별
			$query = " SELECT hour(loginTime) AS 'HOUR', COUNT(*) AS cnt
									FROM nynMemberHistory
									WHERE loginTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
									GROUP BY hour(loginTime)
									ORDER BY hour(loginTime)";
			$result = mysql_query($query);
			while($rs = mysql_fetch_array($result)) {
				$data .= "[".$rs['HOUR'].", ".$rs['cnt']."], ";
			}
		?>
		<script>
			google.charts.load('current', {packages: ['corechart', 'line']});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {
				var data = new google.visualization.DataTable();
				data.addColumn('number', 'X');
				data.addColumn('number', '로그인');
        
				data.addRows([
					<?=$data?>
				]);

				var options = {
						hAxis: {
							title: '시간'
						},
						vAxis: {
							title: '로그인 시간별'
						}

				};

				var chart = new google.visualization.LineChart(document.getElementById('chartLogin'));
				chart.draw(data, options);


        function resizeHandler () {
        chart.draw(data, options);
        }
        if (window.addEventListener) {
            window.addEventListener('resize', resizeHandler, false);
        }
        else if (window.attachEvent) {
            window.attachEvent('onresize', resizeHandler);
        }
			}
		</script>

		<?
			//학습진도 시간별
			$query2 = "SELECT HOUR(startTime) AS 'HOUR', COUNT(*) AS cnt
								FROM nynProgress
								WHERE startTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
								GROUP BY HOUR(startTime)
								ORDER BY HOUR(startTime)";
			$result2 = mysql_query($query2);
			while($rs2 = mysql_fetch_array($result2)) {
				$data2 .= "[".$rs2['HOUR'].", ".$rs2['cnt']."], ";
			}
		?>
		<script>
			google.charts.load('current', {packages: ['corechart', 'line']});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {
				var data = new google.visualization.DataTable();
				data.addColumn('number', 'X');
				data.addColumn('number', '학습진도');

				data.addRows([
					<?=$data2?>
				]);

				var options = {
						hAxis: {
							title: '시간'
						},
						vAxis: {
							title: '학습진도 시간별'
						},
            colors: ['red']
				};

				var chart = new google.visualization.LineChart(document.getElementById('chartProgress'));
				chart.draw(data, options);

        function resizeHandler () {
        chart.draw(data, options);
        }
        if (window.addEventListener) {
            window.addEventListener('resize', resizeHandler, false);
        }
        else if (window.attachEvent) {
            window.attachEvent('onresize', resizeHandler);
        }
			}
		</script>

		<?
			//최종평가응시 시간별
			$query3 = " SELECT hour(testSaveTime) AS 'HOUR', COUNT(*) AS cnt
									FROM nynStudy
									WHERE testSaveTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
									GROUP BY hour(testSaveTime)
									ORDER BY hour(testSaveTime) DESC";
			$result3 = mysql_query($query3);
			while($rs3 = mysql_fetch_array($result3)) {
				$data3 .= "[".$rs3['HOUR'].", ".$rs3['cnt']."], ";
			}
		?>
		<script>
			google.charts.load('current', {packages: ['corechart', 'line']});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {
				var data = new google.visualization.DataTable();
				data.addColumn('number', 'X');
				data.addColumn('number', '응시수');

				data.addRows([
					<?=$data3?>
				]);

				var options = {
						hAxis: {
							title: '시간'
						},
						vAxis: {
							title: '최종평가 시간별'
						}
				};

				var chart = new google.visualization.LineChart(document.getElementById('chartTestTime'));
				chart.draw(data, options);

        function resizeHandler () {
        chart.draw(data, options);
        }
        if (window.addEventListener) {
            window.addEventListener('resize', resizeHandler, false);
        }
        else if (window.attachEvent) {
            window.attachEvent('onresize', resizeHandler);
        }
			}
		</script>

		<?
			//최종평가응시 요일별
			$query4 = " SELECT CASE WHEN DAYOFWEEK(testSaveTime) = 1 THEN '일'
									WHEN DAYOFWEEK(testSaveTime) = 2 THEN '월'
									WHEN DAYOFWEEK(testSaveTime) = 3 THEN '화'
									WHEN DAYOFWEEK(testSaveTime) = 4 THEN '수'
									WHEN DAYOFWEEK(testSaveTime) = 5 THEN '목'
									WHEN DAYOFWEEK(testSaveTime) = 6 THEN '금'
									WHEN DAYOFWEEK(testSaveTime) = 7 THEN '토'
									ELSE '오류' END WEEK_NAME,
									COUNT(*) AS cnt FROM nynStudy
									WHERE testSaveTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
									GROUP BY DAYOFWEEK(testSaveTime)";
			$result4 = mysql_query($query4);
			while($rs4 = mysql_fetch_array($result4)) {
				$data4 .= "['".$rs4['WEEK_NAME']."', ".$rs4['cnt']."], ";
			}
		?>
		<script>
			google.charts.load('current', {packages: ['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				// Define the chart to be drawn.
				var data = google.visualization.arrayToDataTable([
					['요일', '응시수'],
					<?=$data4?>
				]);

				var options = {
					title: '최종평가 응시 요일별',
          colors: ['black']
				}; 

				// Instantiate and draw the chart.
				var chart = new google.visualization.ColumnChart(document.getElementById('chartTestWeek'));
				chart.draw(data, options);

        function resizeHandler () {
        chart.draw(data, options);
        }
        if (window.addEventListener) {
            window.addEventListener('resize', resizeHandler, false);
        }
        else if (window.attachEvent) {
            window.attachEvent('onresize', resizeHandler);
        }
      }
      google.charts.setOnLoadCallback(drawChart);
		</script>

		<?
			//과제 제출 시간별
			$query5 = " SELECT hour(reportSaveTime) AS 'HOUR', COUNT(*) AS cnt
									FROM nynStudy
									WHERE reportSaveTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
									GROUP BY hour(reportSaveTime)
									ORDER BY hour(reportSaveTime) DESC";
			$result5 = mysql_query($query5);
			while($rs5 = mysql_fetch_array($result5)) {
				$data5 .= "[".$rs5['HOUR'].", ".$rs5['cnt']."], ";
			}
		?>
		<script>
			google.charts.load('current', {packages: ['corechart', 'line']});
			google.charts.setOnLoadCallback(drawBasic);

			function drawBasic() {
				var data = new google.visualization.DataTable();
				data.addColumn('number', 'X');
				data.addColumn('number', '제출수');

				data.addRows([
					<?=$data5?>
				]);

				var options = {
						hAxis: {
							title: '시간'
						},
						vAxis: {
							title: '과제 제출 시간별'
						}
				};

				var chart = new google.visualization.LineChart(document.getElementById('chartReportTime'));
				chart.draw(data, options);

        function resizeHandler () {
        chart.draw(data, options);
        }
        if (window.addEventListener) {
            window.addEventListener('resize', resizeHandler, false);
        }
        else if (window.attachEvent) {
            window.attachEvent('onresize', resizeHandler);
        }
			}
		</script>

		<?
			//과제 제출 요일별
			$query6 = " SELECT CASE WHEN DAYOFWEEK(reportSaveTime) = 1 THEN '일'
									WHEN DAYOFWEEK(reportSaveTime) = 2 THEN '월'
									WHEN DAYOFWEEK(reportSaveTime) = 3 THEN '화'
									WHEN DAYOFWEEK(reportSaveTime) = 4 THEN '수'
									WHEN DAYOFWEEK(reportSaveTime) = 5 THEN '목'
									WHEN DAYOFWEEK(reportSaveTime) = 6 THEN '금'
									WHEN DAYOFWEEK(reportSaveTime) = 7 THEN '토'
									ELSE '오류' END WEEK_NAME,
									COUNT(*) AS cnt FROM nynStudy
									WHERE reportSaveTime BETWEEN '2016-01-01 00:00:00' AND '".$inputDate."'
									GROUP BY DAYOFWEEK(reportSaveTime)";
			$result6 = mysql_query($query6);
			while($rs6 = mysql_fetch_array($result6)) {
				$data6 .= "['".$rs6['WEEK_NAME']."', ".$rs6['cnt']."], ";
			}
		?>


		<?
			$queryA = " SELECT A.surveySeq,A.userAnswer, COUNT(A.userAnswer) AS userAnswerC FROM nynSurveyAnswer AS A
									LEFT OUTER
									JOIN nynLectureOpen AS B
									ON A.lectureOpenSeq=B.seq
									LEFT OUTER
									JOIN nynStudy AS C
									ON A.lectureOpenSeq=C.lectureOpenSeq AND A.userID=C.userID
									WHERE C.contentsCode='M06P8D' 
									GROUP BY A.surveySeq,A.userAnswer HAVING A.userAnswer > 0 ORDER BY A.surveySeq, A.userAnswer";
			$resultA = mysql_query($queryA);
			$dataA = "['Survey01', 'Count'],";
			while($rsA = mysql_fetch_array($resultA)) {
				if($rsA['surveySeq'] == 1) {
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
					$dataA .= "['".$surveyTitle."', ".$rsA['userAnswerC']."], ";
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

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
		</script>
  </div>
</div>
<? include '_footer.php' ?>


