<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		$today = subStr($inputDate,0,10);
		$yesterday = date("Y-m-d", strtotime($today."-1Day"));

		$query = "SELECT
								(SELECT count from nynCounter where wdate='0000-00-00') AS totalEA,
								(SELECT count from nynCounter where wdate='".$today."') AS todayEA,
								(SELECT count from nynCounter where wdate='".$yesterday."') AS yesterdayEA";
		$result = mysql_query($query);
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		while($rs = mysql_fetch_array($result)) {
			if(!$rs[totalEA]) {
				$totalEA = "0";
			} else {
				$totalEA = $rs['totalEA'];
			}
			if(!$rs[todayEA]) {
				$todayEA = "0";
			} else {
				$todayEA = $rs['todayEA'];
			}
			if(!$rs[yesterdayEA]) {
				$yesterdayEA = "0";
			} else {
				$yesterdayEA = $rs['yesterdayEA'];
			}
			$adminapi[totalEA] = $totalEA;
			$adminapi[todayEA] = $todayEA;
			$adminapi[yesterdayEA] = $yesterdayEA;
		}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		
	@mysql_close();
?>