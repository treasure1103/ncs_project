<?
	include './lib/dbConnect.php'; 

	$topDirArray = explode("/", $PHP_SELF);
	$mainDirName = $topDirArray[count($topDirArray) - 2];
	$subDirNameType = $topDirArray[count($topDirArray) - 1];
	$subDirNameArray = explode(".", $subDirNameType);
	$subDirName = $subDirNameArray[count($subDirNameArray) - 2];

	$ipaddr = $_SERVER['REMOTE_ADDR']; // IP 출력
	//echo "SELECT MAX(seq), MAX(count), MAX(wdate) from nynCounter";
	$result = mysql_query("SELECT MAX(seq), MAX(count), MAX(wdate) from nynCounter");
	$row = mysql_fetch_array($result);
	$max_num = $row[0];    // 마지막 번호
	$max_count = $row[1];    // 전체 카운터
	$max_date = $row[2];    // 마지막 날짜

	//    처음 카운터를 실행할 경우 기본 값 입력하기
	if(!$max_count) {
			$dbresult = mysql_query("INSERT INTO nynCounter (count,wdate) VALUES (1,'0000-00-00')");
		}

	//    쿠키값을 검사하여 카운터 증가.
	if($cookie[$ipaddr] == "old") {
			SetCookie("cookie[$ipaddr]","old",0,"/",$_SERVER[HTTP_HOST]);    //    쿠키값이 존재할 경우 재 설정
	} else {
			SetCookie("cookie[$ipaddr]","old",0,"/",$_SERVER[HTTP_HOST]);    //    쿠키 재설정
			//    count 값 update 시키기


	$calNow = date("Y-m-d");
			//start
			$max_count = $max_count + 1;    //    total 카운터를 1 증가시킴
			$dbresult = mysql_query("UPDATE nynCounter SET count = '$max_count' WHERE wdate = '0000-00-00'");

			if($max_date != $calNow) {    // 마지막으로 접속한 날짜가 오늘과 같은지 비교
					$dbresult = mysql_query("INSERT INTO nynCounter (count,wdate) values (1,'$calNow')");
			} else {
					$result = mysql_query("SELECT count from nynCounter WHERE wdate='$calNow'");
					$row = mysql_fetch_array($result);
					$today_count = $row['count'] + 1;    // 오늘 카운터
					$dbresult = mysql_query("UPDATE nynCounter SET count = '$today_count' WHERE wdate = '$calNow' ");
			}
	}//echo $today_count;

	//토탈 카운트 구하기
	$dbquery = mysql_query("select count from nynCounter where wdate = '0000-00-00'");
	$row = mysql_fetch_row($dbquery);
	$total_count = $row[0];

	$calNow = date("Y-m-d");

	//오늘 카운트 구하기
	$dbquery = mysql_query("select count from nynCounter where wdate = '$calNow'");
	$row = mysql_fetch_row($dbquery);
	$to_count = $row[0];

	// 어제 카운트 구하기
	$year = date("Y");
	$month = date("m");
	$today = date("d");
	$y_day = $today - 1;

	$yesterday = date("Y-m-d", mktime(0,0,0,$month,$y_day,$year));

	$dbquery = mysql_query("select count from nynCounter where wdate = '$yesterday'");
	$row = mysql_fetch_row($dbquery);
	$y_count = $row[0];
?>