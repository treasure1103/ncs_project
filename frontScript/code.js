//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//공통선언
var useApi = '../api/apiCategory.php'
var totalCount = '';
var titleNav = '';
var divisionNum = '';
var page = '';
var seq = '';
var writeSeq = '';

//리스트액션
function listAct(writeSeqNum){	
	writeSeqNum = writeSeqNum ? writeSeqNum : '';
	writeSeq = writeSeqNum;
	var actionArea = '';
	actionArea += '<div class="inputArea"><form class="writeform"><input type="hidden" name="seq" value=""><input type="hidden" name="division" value=""><table>';
	actionArea += '<tr><th style="width:50px;">순번</th><th style="width:15%;">코드</th><th style="width:15%;">이름</th><th>설명 or link</th><th style="width:70px;">사용여부</th><td style="width:140px;" rowspan="2"><button type="button" onClick="sendData(\''+useApi+'\',\'writeform\')">등록</button></td></tr>';
	actionArea += '<tr><td><input type="tel" name="orderBy" style="width:40px" maxlength="4" /></td><td><input type="text" name="value01" /></td><td><input type="text" name="value02" /></td><td><input type="text" name="value03" /></td><td><select name="enabled"><option>Y</option><option>N</option></select></td></tr>'
	actionArea += '</table></form></div>';
	
	$('div.inputArea').remove();
	$('#contents > h1').after(actionArea);
	
	var contents = '';	
	contents += '<h1></h1>'
	contents += '<table><thead><tr>';
	contents += '<th style="width:60px;">순번</th>';
	contents += '<th style="width:60px;">고정수</th>';
	contents += '<th style="width:80px;"><img src="../images/admin/icon_folder.png" alt="folder" /></th>';
	contents += '<th style="width:10%;">코드</th>';
	contents += '<th style="width:10%;">이름</th>';
	contents += '<th>설명</th>';
	contents += '<th style="width:80px;">사용여부</th>';
	contents += '<th style="width:120px;">등록일</th>';
	contents += '<th style="width:180px;">수정</th>';
	contents += '</tr></thead><tbody>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	//게시물 소팅부분
	ajaxAct()

}

function ajaxAct(){
	var listAjax = $.get(useApi,{'seq':writeSeq},function(data){
		totalCount = data.totalCount;
		var divisionNum = data.division
		$('#contentsArea > h1').html('<img src="../images/admin/icon_folder.png" alt="folder" />&nbsp;&nbsp;현위치 :: '+data.location);
		$('.writeform input[name="division"]').val(divisionNum);
		var lists = ''				
		if (totalCount != 0){
			$.each(data.category, function(){
				lists += '<tr class="line'+this.seq+'">';
				lists += '<td><input type="tel" name="orderBy" value="'+this.orderBy+'" style="width:40px;" /></td>';
				lists += '<td><input type="hidden" name="division" value="'+divisionNum+'">'+this.seq+'</td>';
				lists += '<td><a href="javascript:listAct('+this.seq+')"><img src="../images/admin/icon_openfolder.png" alt="open Folder" /></a></td>';
				lists += '<td><input type="text" name="value01" value="'+this.value01+'" /></td>';
				lists += '<td><input type="text" name="value02" value="'+this.value02+'" /></td>';
				lists += '<td><input type="text" name="value03" value="'+this.value03+'" /></td>';
				if(this.enabled != 'Y'){
					lists += '<td><select name="enabled"><option value="Y">Y</option><option value="N" selected="selected">N</option></select></td>';
				}else{
					lists += '<td><select name="enabled"><option value="Y" selected="selected">Y</option><option value="N">N</option></select></td>';
				}				
				lists += '<td>'+this.inputDate.substr(0,10)+'</td>';
				lists += '<td><button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'copys\')">복제</button> / <button type="button"  onClick="lineSendData(\''+useApi+'\','+this.seq+',\'modifys\')">수정</button> / <button type="button" onClick="deleteData(\''+useApi+'\','+this.seq+')">삭제</button></td>';
				lists += '</tr>';
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">아직 등록된 목록이 없습니다.</td></tr>';
		}
		lists += '</tbody></table>';
		$('.BBSList tbody').html(lists);
	})
}