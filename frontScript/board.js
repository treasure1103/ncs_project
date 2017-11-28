//alert (boardType+'/'+act+'//'+seq);
//보드 정보 선언
var useApi = '../api/apiBoard.php';
var categoryApi = '../api/apiCategory.php';
var commentApi = '../api/apiComment.php';
var seq = seq ? seq : '' ;
var totalCount = '';
var topCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
//var boardCode = '';
if (page ==''){
	page = 1
}

optWrite = new Array();
makeOption('phone01','','') //지역번호
makeOption('mobile01','','') //휴대폰 앞자리
makeOption('email02','','') //이메일종류

//게시판 사용 기능 정의
var boardName = '';
var boardMode = '';
var useName = '';
var useEmail = '';
var usePhone = '';
var usePassword = '';
var useSecret = '';
var useTop = '';
var useCategory = '';
var useReply = '';
var useComment = '';
var useFile = '';
var useSearch = '';
var useDateView = '';
var useHitView = '';
var memo = '';
var titleHtml = '';

//게시판 사용 권한 정의
var listPermit = '';
var viewPermit = '';
var writePermit = '';
var replyPermit = '';
var deletePermit = '';
var commentPermit = '';
var secretPermit = '';
var topPermit = '';

//로그인된 사용자 정보
var loginMoblie01 = '';
var loginMoblie02 = '';
var loginMoblie03 = '';
var loginPhone01 = '';
var loginPhone02 = '';
var loginPhone03 = '';
var loginEmail01 = '';
var loginEmail02 = '';

var loginUser = $.get('../api/apiLoginUser.php',{},function(data){
	loginMoblie01 = data.mobile01;
	loginMoblie02 = data.mobile02;
	loginMoblie03 = data.mobile03;
	loginEmail01 = data.email01;
	loginEmail02 = data.email01;
})

function modalAct(apiName,modalSeq,type,afterAct){
	var modalWrite =''
	modalWrite +='<div id="modal"><div>';
	modalWrite +='<h1>비밀번호 확인<button type="button" onClick="modalClose()"><img src="../../images/admin/btn_close.png" alt="닫기" /></button></h1>';	
	modalWrite +='<div><form class="chkPWD" action="javascript:chkPWD(\''+apiName+'\',\''+type+'\',\''+afterAct+'\')">';
	modalWrite +='<input type="hidden" name="seq" value="'+modalSeq+'">';
	modalWrite +='<input type="password" name="pwd">';
	modalWrite +='<div class="btnArea"><button type="button" onclick="chkPWD(\''+apiName+'\',\''+type+'\',\''+afterAct+'\')">비밀번호확인</button></div>';
	modalWrite +='</div></div>';
	$('#contents').after(modalWrite)
	modalAlign()
}
function chkPWD(apiName,type,afterAct){
	var sendCHKData = $('#modal form.chkPWD').serialize();
	var resultSeq = $('#modal input[name="seq"]').val();
	//공통선언 - 삭제
	if(type=="deletes"){		
		$.ajax({
			url: apiName,
			type:'DELETE',
			data:sendCHKData,
			dataType:'text',
			success:function(data){
				alert(data)
				if(data == 'success'){
					alert('삭제되었습니다.')
					if(afterAct == 'comment'){
						commentAct()
					}else{
						listAct()
					}
					modalClose();
				}else{
					alert('비밀번호가 일치하지 않습니다.')
				}
			},
			fail:function(){
				alert('삭제에 실패하였습니다.')
			}
		})
	}else if(type=="viewCheck"){
		$.get(apiName,'boardCode='+boardCode+'&'+sendCHKData,function(data){
			if(data.result == 'error'){
				alert('비밀번호가 잘못되었습니다.')
			}else{
				viewAct(resultSeq);
				modalClose();
			}
		})
	}
}