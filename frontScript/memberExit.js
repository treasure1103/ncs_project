//공통선언
var seq = '';
var totalCount = '';
var page = page ? page : 1;
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
var useApi = '../api/apiMember.php';

//리스트액션
function listAct(){

	//refreashData() // totalCount 호출
	//게시물 소팅부분
	var actionArea = '';
	actionArea += '<div class="searchArea">';
	actionArea += '<form class="searchForm" action="javascript:searchAct()">';
	actionArea += '<select name="searchType">';
	actionArea += '<option value="userID">아이디</option>';
	actionArea += '<option value="userName">이름</option>';
	actionArea += '</select>&nbsp;';
	actionArea += '<input type="text" name="searchValue" />&nbsp;';
	actionArea += '<button type="button" onClick="searchAct();">검색하기</button>';
	actionArea += '</form>';
	actionArea += '</div>';

	$('#contents > h1').after(actionArea);

	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:60px;">번호</th>';
	contents += '<th>아이디</th>';
	contents += '<th style="width:200px;">이름</th>';
	contents += '<th style="width:160px;">탈퇴일</th>';
	contents += '<th style="width:120px;">탈퇴사유</th>';
	contents += '</tr></thead><tbody>';
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
	pagerAct();

}
function ajaxAct(listPage, sortData){
	listPage = listPage ? listPage : 1;
	page = listPage
	sortData = sortData ? sortData : '';
	var listAct	 = $.get(useApi,'userDelete=Y&list='+listCount+'&page='+page+sortData,function(data){
		totalCount = data.totalCount;
		if (totalCount != 0){
			var lists = '';
			$.each(data.member, function(){
				lists += '<tr>';
				lists += '<td>'+this.seq+'</td>';
				lists += '<td>'+this.userID+'</td>';
				lists += '<td>'+this.userName+'</td>';
				lists += '<td>'+this.userDelete.inputDate+'</td>';
				lists += '<td><button type="button" onClick="modalAct('+this.seq+')">상세보기</button></td>';
				lists += '</tr>';
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">탈퇴회원이 없습니다.</td></tr>';
		}
		$('.BBSList tbody').html(lists)
	})
}

function modalAct(modalSeq){
	var boardInfo = $.get(useApi,{'userDelete':'Y','seq':modalSeq},function(data){
		var modalWrite =''
		modalWrite +='<div id="modal"><div class="memberExit">';
		modalWrite +='<h1>탈퇴 상세사유<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		$.each(data.member,function(){
			modalWrite +='<div>';		
			modalWrite +='<h1>'+this.userName+' ( ID : '+this.userID+' )<span>님의 탈퇴사유</span></h1>';
			modalWrite +='<h2>탈퇴 신청일 : '+this.userDelete.inputDate+'</h2>';
			modalWrite +='<p>'+this.userDelete.memo+'</p>';
			modalWrite +='<div>';
		})
		modalWrite +='</div></div>';
		$('#contents').after(modalWrite)
		modalAlign()
	})
}
