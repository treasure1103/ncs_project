
$(document).ready(function(){
	writeAct();
});

//보드 정보 선언
var useApi = '../api/apiMember.php';
var companyApi = '../api/apiCompany.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

//사용옵션 가져오기
optWrite = new Array();
makeOption('userLevel','user','')
makeOption('userLevel','admin','')
makeOption('phone01','','')
makeOption('mobile01','','')
makeOption('email02','','')
makeOption('bankName','','')
makeOption('sexType','','')
