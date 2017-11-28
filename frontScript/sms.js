//보드 정보 선언
var sortData = '';
var useApi = '../api/apiStudy.php';
var smsApi = '../api/apiSMS.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 100; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트