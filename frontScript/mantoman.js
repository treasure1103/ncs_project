//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//공통선언

page = page ? page : 1;
var seq = seq ? seq : '';
var totalCount = '';
var topCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
var boardType = boardType ? boardType : '';
var viewType = '';

var useApi = '../api/apiConsult.php';
var categoryApi = '../api/apiCategory.php';

if(pageMode != 'userPage'){
	viewType = 'admin'
}

optWrite = new Array();
makeOption('phone01','','');
makeOption('mobile01','','');
makeOption('email02','','');

//카테고리선언