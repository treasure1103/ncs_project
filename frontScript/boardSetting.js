//alert (boardType+'/'+act+'//'+seq);
//보드 정보 선언
var useApi = '../api/apiBoardInfo.php';
var permitApi = '../api/apiBoardPermit.php';
var categoryApi = '../api/apiCategory.php';

//공통선언용
var page = '';
var seq = '';

//카테고리 사용선언
var optWrite = new Array();

makeOption('enabled','','');//사용여부
makeOption('fileNum','','');//사용여부
makeOption('userLevel','','');//사용여부
makeOption('boardMode','','');//사용여부

//게시판 사용 기능 정의
function listAct(){	
		var actionArea = '';
		actionArea += '<div class="inputArea"><form class="writeform">';
		actionArea += '<table><tr>';
		actionArea += '<th style="width:50px;">순서</th>';
		actionArea += '<th style="width:25%;">게시판명</th>';
		actionArea += '<th>설명</th>';
		actionArea += '<td style="width:80px;" rowspan="4"><button type="button" onClick="sendData(\''+useApi+'\',\'writeform\')">등록</button></td>';
		actionArea += '</tr><tr>';
		actionArea += '<td><input type="tel" name="orderBy" /></td>';
		actionArea += '<td><input type="text" name="boardName" /></td>';
		actionArea += '<td><input type="text" name="memo" /></td>';
		actionArea += '</tr></table></form></div>';
		
		$('#contents > h1').html('게시판관리<span>통합 카테고리를 관리하는 페이지입니다.</span>');
		$('div.inputArea').remove();
		$('#contents > h1').after(actionArea);
		
		var contents = '';
		contents += '<table><thead><tr>';
		contents += '<th style="width:60px;">순서</th>';
		contents += '<th style="width:80px;">게시판코드</th>';
		contents += '<th style="width:15%;">게시판명</th>';
		contents += '<th>설명</th>';
		contents += '<th stlye="width:140px">바로가기</th>';
		contents += '<th stlye="width:120px">기능설정</th>';
		contents += '<th stlye="width:120px">권한설정</th>';
		contents += '<th stlye="width:120px">카테고리</th>';
		contents += '<th stlye="width:180px">수정/복사/삭제</th>';
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		$('#contentsArea').addClass('BBSList');
		$('#contentsArea').html(contents);
		ajaxAct();
}

function ajaxAct(){
	var boardInfo = $.get(useApi,{},function(data){
		var lists = '';
		$.each(data.boardInfo, function(){
			lists += '<tr class="line'+this.seq+'">';
			lists += '<td><input type="tel" name="orderBy" value="'+this.orderBy+'" style="width:40px;" /></td>';
			lists += '<td>'+this.seq+'</td>';
			lists += '<td><input type="text" name="boardName" value="'+this.boardName+'" /></td>';
			lists += '<td><input type="text" name="memo" value="'+this.memo+'" /></td>';
			lists += '<td><button type="button" onClick="window.open(\'../admin/06_board.php?locaSel=0907&boardCode='+this.seq+'\')">바로가기</button></td>';
			lists += '<td><button type="button" onClick="modalAct(\'info\','+this.seq+')">설정</button></td>';
			lists += '<td><button type="button" onClick="modalAct(\'permit\','+this.seq+')">설정</button></td>';
			lists += '<td>';
			if(this.useCategory == 'Y'){
				lists += '<button type="button" onClick="modalAct(\'category\','+this.seq+')">설정</button>';
			}else{
				lists += '비사용'
			}
			lists += '</td>';
			lists += '<td>';
			lists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'modifys\')">수정</button> / ';
			lists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'copys\')">복사</button> / ';
			lists += '<button type="button" onClick="deleteData(\''+useApi+'\','+this.seq+')">삭제</button>';
			lists += '</td>';
			lists += '</tr>';
		});
		$('.BBSList tbody').html(lists)
	})
}

function modalAct(type,modalSeq){
	
	var modalWrite ='';
	if(type == 'info'){
		modalWrite +='<div id="modal"><div class="boardInfo">';
		modalWrite +='<h1>게시판 기능설정<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite +='<div class="inputArea" style="background:#fff;"><form class="boardInfoModal">';
		modalWrite +='<input type="hidden" name="seq" value="'+modalSeq+'" /><table></table></form>';
		modalWrite +='<div class="btnArea"><button onClick="sendData(\''+useApi+'\',\'boardInfoModal\',\'modalBoth\')">적용하기</button></div>';
		modalWrite += '<button type="button" class="btnRefresh" style="display:none">새로고침</button>'
		modalWrite +='</div></div></div>';
		$('#contents').after(modalWrite);
		modalInfo();
		modalAlign();
		$('.btnRefresh').click(function(){modalInfo()})
		
		function modalInfo(){
			var boardInfo = $.get(useApi,{'seq':modalSeq},function(data){
				var infoWrite = '';
				$.each(data.boardInfo,function(){
					infoWrite +='<tr>';
					infoWrite +='<th style="width:100px;">게시판종류</th>';
					infoWrite +='<th style="width:60px;">작성자명</th>';
					infoWrite +='<th style="width:60px;">이메일</th>';
					infoWrite +='<th style="width:60px;">휴대폰</th>';
					infoWrite +='<th style="width:60px;">비밀번호</th>';
					infoWrite +='<th style="width:60px;">비밀글</th>';
					infoWrite +='<th style="width:60px;">공지글</th>';
					infoWrite +='</tr><tr>';					
					infoWrite +='<td><select name="boardMode" class="'+this.boardMode+'">'+optWrite['boardMode']+'</select></td>';
					infoWrite +='<td><select name="useName" class="'+this.useName+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useEmail" class="'+this.useEmail+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="usePhone" class="'+this.usePhone+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="usePassword" class="'+this.usePassword+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useSecret" class="'+this.useSecret+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useTop" class="'+this.useTop+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='</tr><tr>';
					infoWrite +='<th>파일첨부</th>';
					infoWrite +='<th>카테고리</th>';
					infoWrite +='<th>답변</th>';
					infoWrite +='<th>댓글</th>';
					infoWrite +='<th>검색사용</th>';
					infoWrite +='<th>작성일</th>';
					infoWrite +='<th>조회수</th>';		
					infoWrite +='</tr><tr>';
					infoWrite +='<td><select name="useFile" class="'+this.useFile+'">'+optWrite['fileNum']+'</select></td>';
					infoWrite +='<td><select name="useCategory" class="'+this.useCategory+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useReply" class="'+this.useReply+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useComment" class="'+this.useComment+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useSearch" class="'+this.useSearch+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useDateView" class="'+this.useDateView+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='<td><select name="useHitView" class="'+this.useHitView+'">'+optWrite['enabled']+'</select></td>';
					infoWrite +='</tr>';
				})
				$('.boardInfoModal > table').html(infoWrite);
				findOpt();
			})
		}
	}else if(type== 'permit'){
		modalWrite +='<div id="modal"><div class="boardPermit">';
		modalWrite +='<h1>게시판 권한설정<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite +='<div class="inputArea" style="background:#fff;"><form class="boardPermitModal">';
		modalWrite +='<input type="hidden" name="boardCode" value="'+modalSeq+'" /><table></table></form>';
		modalWrite +='<div class="btnArea"><button onClick="sendData(\''+permitApi+'\',\'boardPermitModal\',\'modal\')">적용하기</button></div>';
		modalWrite += '<button type="button" class="btnRefresh" style="display:none">새로고침</button>'
		modalWrite +='</div></div></div>';
		$('#contents').after(modalWrite);
		modalPermit();
		modalAlign();
		$('.btnRefresh').click(function(){modalPermit()})
		
		function modalPermit(){
			var boardInfo = $.get(permitApi,{'boardCode':modalSeq},function(data){
				var permitWrite = '';
				$.each(data.boardPermit,function(){
					permitWrite +='<tr>';
					permitWrite +='<th style="width:130px;">목록조회</th>';
					permitWrite +='<th style="width:130px;">내용조회</th>';
					permitWrite +='<th style="width:130px;">글쓰기</th>';
					permitWrite +='<th style="width:130px;">답변달기</th>';
					permitWrite +='</tr></tr>'
					permitWrite +='<td><select name="listPermit" class="'+this.listPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="viewPermit" class="'+this.viewPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="writePermit" class="'+this.writePermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="replyPermit" class="'+this.replyPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='</tr></tr>'
					permitWrite +='<th>글삭제</th>';
					permitWrite +='<th>댓글달기</th>';
					permitWrite +='<th>비밀글사용</th>';
					permitWrite +='<th>공지글쓰기</th>';
					permitWrite +='</tr></tr>'
					permitWrite +='<td><select name="deletePermit" class="'+this.deletePermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="commentPermit" class="'+this.commentPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="secretPermit" class="'+this.secretPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='<td><select name="topPermit" class="'+this.topPermit+'">'+optWrite['userLevel']+'</select></td>';
					permitWrite +='</tr>';
				})
				$('.boardPermitModal > table').html(permitWrite);
				findOpt();
			})
		}
	}else if(type== 'category'){
		var boardInfo = $.get(categoryApi,{'value01':'boardCategory','divisionValue':modalSeq},function(data){
			modalWrite +='<div id="modal"><div class="boardCategory">';
			modalWrite +='<h1>게시판 카테고리설정<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
			modalWrite +='<div class="inputArea" style="background:#fff;">';
			modalWrite +='<form class="boardCategoryModal">';
			modalWrite +='<input type="hidden" name="seq" value="" />';
			modalWrite +='<input type="hidden" name="division" value="'+data.division+'" />';
			modalWrite +='<table>';
			modalWrite +='<tr>';
			modalWrite +='<th style="width:50px;">순번</th>';
			modalWrite +='<th>카테고리명</th>';
			modalWrite +='<th style="width:80px;">사용여부</th>';
			modalWrite +='<td rowspan="2" style="width:120px;"><button type="button" onClick="sendData(\''+categoryApi+'\',\'boardCategoryModal\',\'modal\')">적용하기</button></td>';
			modalWrite +='</tr><tr>';
			modalWrite +='<td><input type="tel" name="orderBy" style="width:40px;" /></td>';
			modalWrite +='<td><input type="text" name="value02" /></td>';
			modalWrite +='<td><select name="enabled">'+optWrite['enabled']+'</select></td>';
			modalWrite +='</table></form>';
			modalWrite +='</div>'
			modalWrite +='<div class="BBSList"><table>';
			modalWrite +='</table></div>';
			modalWrite += '<button type="button" class="btnRefresh" style="display:none">새로고침</button>'
			modalWrite +='</div>';
			$('#contents').after(modalWrite);
			modalCategory();
			modalAlign();
			$('.btnRefresh').click(function(){modalCategory()})
		})				
		function modalCategory(){
			var boardInfo = $.get(categoryApi,{'value01':'boardCategory','divisionValue':modalSeq},function(data){
				var divsionNum = data.division
				var categoryWrite = '';
				var i = 1;
				categoryWrite +='<tr>';
				categoryWrite +='<th style="width:50px;">번호</th>';
				categoryWrite +='<th style="width:50px;">순번</th>';
				categoryWrite +='<th>카테고리명</th>';
				categoryWrite +='<th style="width:80px;">사용여부</th>';
				categoryWrite +='<th style="width:180px;">수정/복사/삭제</th>';
				categoryWrite +='</tr>';
				if (data.totalCount != 0){
					$.each(data.category,function(){
						categoryWrite +='<tr class="modalLine'+this.seq+'">';
						categoryWrite +='<td style="display:none"><input type="hidden" name="division" value="'+divsionNum+'" /></td>';
						categoryWrite +='<td>'+i+'</td>';
						categoryWrite +='<td><input type="tel" name="orderBy" style="width:40px;" value="'+this.orderBy+'" /></td>';
						categoryWrite +='<td><input type="text" name="value02" value="'+this.value02+'" /></td>';
						categoryWrite +='<td><select name="enabled" class="'+this.enabled+'">'+optWrite['enabled']+'</select></td>';
						categoryWrite +='<td>';
						categoryWrite +='<button type="button" onClick="lineSendData(\''+categoryApi+'\','+this.seq+',\'copys\',\'modal\')">복사</button> / ';
						categoryWrite +='<button type="button" onClick="lineSendData(\''+categoryApi+'\','+this.seq+',\'modifys\',\'modal\')">수정</button> / ';
						categoryWrite +='<button type="button" onClick="deleteData(\''+categoryApi+'\','+this.seq+')">삭제</button></td>';
						categoryWrite +='</tr>';
						i ++;
					})
				}else{
					categoryWrite +='<tr><td colspan="20">등록카테고리가 없습니다.</td></tr>';
				}
				$('.boardCategory > .BBSList > table').html(categoryWrite);
				findOpt();
			})
		}
	}
}

function modalClose() {
	$('#modal').remove();
}