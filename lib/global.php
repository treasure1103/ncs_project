<?
$PHP_SELF = $_SERVER['PHP_SELF']; 
$PathUrls = urlencode($PHP_SELF);  // 현재 경로 encode
$PATH = explode("/",$PHP_SELF);
$method = $_SERVER['REQUEST_METHOD']; // method 식별
date_default_timezone_set('Asia/Seoul');
$inputDate = date('Y-m-d H:i:s'); // 현재 시간
$userIP = $_SERVER['REMOTE_ADDR']; // IP 출력
$searchType = "";
$searchType = $_GET['searchType']; // 검색 요청 타입
$searchValue = $_GET['searchValue']; // 검색 요청 값
$sortType = $_GET['sortType']; // 정렬 타입
$sortValue = $_GET['sortValue']; // 정렬 차순
$list = $_GET['list']; // 리스트
$page = $_GET['page']; // 페이지

//$global_query = "SELECT companyCode, companyName, companyID, phone01, phone02, phone03, fax01, fax02, fax03, elecEmail01, elecEmail02 FROM nynCompany WHERE seq=2";
//$global_result = mysql_query($global_query);
//$global_rs = mysql_fetch_array($global_result);

//$CompanyID = $global_rs['companyID']; // 회사코드
//$_siteName = $global_rs['companyName']; // 사이트명
//$_adminMail = $global_rs['elecEmail01']."-".$global_rs['elecEmail02']; // 관리자 메일
//$_adminMail = $global_rs['elecEmail01']."-".$global_rs['elecEmail02']; // 관리자 메일
//$_csPhone = $global_rs['phone01']."-".$global_rs['phone02']."-".$global_rs['phone03'];  // 고객센터 전화번호
//$_csFax = $global_rs['fax01']."-".$global_rs['fax02']."-".$global_rs['fax03'];  // 팩스
$CompanyID = "ncs"; // 회사코드
$_siteName = "ncs이러닝센터"; // 사이트명
$_siteURL = "ncscenter.kr"; // 도메인
$_adminMail = "cslim003@dutycenter.co.kr"; // 관리자 메일
$_csPhone = "02-2631-7652";  // 고객센터 전화번호
$_csFax = "02-2631-7654";  // 팩스

$_smsNumber = "0226317652";  // 문자 발신번호
$_companyCode = "4438800296";  // 사업자번호
//$_companyCode = $global_rs['companyCode'];  // 사업자번호
$_mainContentsEA = "3";  // 대표과정 선정 수
//세션 관련 설정
$SesstionTimeOutMAX = 60 * 60 * 8; // 8시간 으로 셋팅
$SesstionTimeOutMIN = 60 * 60 * 8; // 8시간 으로 셋팅
$maxlifetime = $SesstionTimeOutMAX;
$minlifetime = $SesstionTimeOutMIN;
?>