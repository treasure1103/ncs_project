<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>사업주정보 임시 등록</title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var useApi = '/api/apiTempCompany.php';
	printAjax();

	function printAjax(){
		var printAjax = $.get(useApi,function(data){
			var tempList = '';
			var totList = '';
			var i = 1;
			var error = 'class="error"';

				if(data.totalCount != 0){
					totList += '<h1>총 등록 대기수: <br /><strong>'+data.totalCount+'개</strong><br />';
					totList += '<button type="button" onClick="allSubmit()">전체등록</button>&nbsp;';
					totList += '<button type="button" onClick="allDelete()">전체삭제</button></h1>';
					totList += '<div>';

					$.each(data.company, function(){
							totList +='<button type="button">'+this.name+' : '+this.count+'</button>';
					})
					totList += '</div>';
					$('.totalArea').html(totList);

						$.each(data.study, function(){
										tempList +='<tr class="line'+this.seq+'">';
										
										tempList +='<td class="center">'+i+'</td>';
										
										if(this.companyCode.length != 10) { // 사업자번호가 10자리가 아닌경우 error
											var companyCode = 'class="error"';
										} else {
											var companyCode = '';
										}
										if(this.companyCode == '' || this.companyCode == null) { // 사업자번호를 등록하지 않은 경우 error
											var companyCode = 'class="error"';
										} else {
											var companyCode = '';
										}
										if(this.companyID == '' || this.companyID == null) { // 회사 아이디를 등록하지 않은 경우 error
											var companyID = 'class="error"';
										} else {
											var companyID = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="companyName" value="'+this.companyName+'" '+companyName+'><br />'; //상호
										tempList +='(<input type="text" name="companyID" value="'+this.companyID+'" '+companyID+'> / <input type="text" name="companyCode" value="'+this.companyCode+'" '+companyCode+'>)'; //사업자번호


										tempList +='<input type="text" name="ceoName" value="'+this.ceoName+'" '+ceoName+'><br />'; //대표자명
										tempList +='(<input type="text" name="zipCode" value="'+this.zipCode+'" '+zipCode+'>)<input type="text" name="address01" value="'+this.address01+'" '+address02+'><input type="text" name="address01" value="'+this.address02+'" '+address02+'>'; //주소
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="tel" name="phone01" value="'+this.phone01+'" '+phone01+'>&nbsp;-&nbsp;';
										tempList +='<input type="tel" name="phone02" value="'+this.phone02+'" '+phone02+'>&nbsp;-&nbsp;';
										tempList +='<input type="tel" name="phone03" value="'+this.phone03+'" '+phone03+'><br/>';//회사 전화번호
										tempList +='<input type="text" name="fax01" value="'+this.fax01+'" '+fax01+'>&nbsp;@&nbsp;';
										tempList +='<input type="text" name="fax02" value="'+this.fax02+'" '+fax02+'>&nbsp;@&nbsp;';
										tempList +='<input type="text" name="fax03" value="'+this.fax03+'" '+fax03+'>';//회사 팩스번호
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="text" name="elecEmail01" value="'+this.elecEmail01+'" '+elecEmail01+'><input type="text" name="elecEmail02" value="'+this.elecEmail02+'" '+elecEmail02+'>';//전자계산서이메일
										tempList +='(<input type="text" name="kind" value="'+this.kind+'" '+kind+'> / <input type="text" name="part" value="'+this.part+'" '+part+' readonly>)'; //업태/업종
										tempList +='</td>';
										
										if(this.managerID == '' || this.managerID == null) { // 교육담당자 아이디 없을 경우 error
											var managerID = 'class="error"';
										} else {
											var managerID = '';
										}
										if(this.managerName == '' || this.managerName == null) { // 교육담당자 이름 없는경우 error
											var managerName = 'class="error"';
										} else {
											var managerName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="managerName" value="'+this.managerName+'" '+managerName+' readonly><br />'; //교육담당자 이름
										tempList +='(<input type="text" name="managerID" value="'+this.managerID+'" '+managerID+'>)'; //교육담당자 아이디
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="tel" name="managerPhone01" value="'+this.managerPhone01+'" >-<input type="tel" name="managerPhone02" value="'+this.managerPhone02+'" >-<input type="tel" name="managerPhone03" value="'+this.managerPhone03+'" >'; //교육담당자 연락처
										tempList +='<input type="text" name="managerEmail01" value="'+this.managerEmail01+'" '+managerEmail01+'><input type="text" name="managerEmail02" value="'+this.managerEmail02+'" '+managerEmail02+'>'; //교육담당자 이메일
										tempList +='</td>';

										if(this.staffID == '' || this.staffID == null) { // 운영담당자 아이디 없을 경우 error
											var staffID = 'class="error"';
										} else {
											var staffID = '';
										}
										if(this.staffName == '' || this.staffName == null) { // 운영담당자 이름 없는경우 error
											var staffName = 'class="error"';
										} else {
											var staffName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="staffName" value="'+this.staffName+'" '+staffName+' readonly><br />'; //운영담당자 이름
										tempList +='(<input type="text" name="staffID" value="'+this.staffID+'" '+staffID+'>)'; //운영담당자 아이디
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="tel" name="staffPhone01" value="'+this.staffPhone01+'" >-<input type="tel" name="staffPhone02" value="'+this.staffPhone02+'" >-<input type="tel" name="staffPhone03" value="'+this.staffPhone03+'" >'; //운영담당자 연락처
										tempList +='<input type="text" name="staffEmail01" value="'+this.staffEmail01+'" '+staffEmail01+'><input type="text" name="staffEmail02" value="'+this.staffEmail02+'" '+staffEmail02+'>'; //운영담당자 이메일
										tempList +='</td>';

										if(this.marketerID == '' || this.marketerID == null) { // 영업담당자 아이디 없을 경우 error
											var marketerID = 'class="error"';
										} else {
											var marketerID = '';
										}
										if(this.marketerName == '' || this.marketerName == null) { // 영업담당자 이름 없는경우 error
											var marketerName = 'class="error"';
										} else {
											var marketerName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="marketerName" value="'+this.marketerName+'" '+marketerName+' readonly><br />'; //영업담당자 이름
										tempList +='(<input type="text" name="marketerID" value="'+this.marketerID+'" '+marketerID+'>)'; //영업담당자 아이디
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="tel" name="marketerPhone01" value="'+this.marketerPhone01+'" >-<input type="tel" name="marketerPhone02" value="'+this.marketerPhone02+'" >-<input type="tel" name="marketerPhone03" value="'+this.marketerPhone03+'" >'; //영업담당자 연락처
										tempList +='<input type="text" name="marketerEmail01" value="'+this.marketerEmail01+'" '+marketerEmail01+'><input type="text" name="marketerEmail02" value="'+this.marketerEmail02+'" '+marketerEmail02+'>'; //영업담당자 이메일
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="text" name="companyScale" value="'+this.companyScale+'" '+companyScale+'><br />'; //회사규모
										tempList +='<input type="text" name="studyEnabled" value="'+this.studyEnabled+'" '+studyEnabled+' readonly>'; //사이버교육센터 사용여부
										tempList +='</td>';
					
										tempList +='<td>';
										tempList +='<input type="text" name="memo" value="'+this.memo+'">'; //메모
										tempList +='</td>';

										tempList +='<td class="center"><button type="button" onClick="lineSendData(\''+this.seq+'\');">수정</button>&nbsp;';
										tempList +='<button type="button" onClick="sendData(\'realUploadform\', \''+this.seq+'\');">삭제</button></td>';
										
										tempList +='</tr>';
										i++;
								})
								$('.infoArea tbody').html(tempList);

				} else {
					//tempList +='<tr><td colspan="20">등록된 수강 데이터가 없습니다.</td></tr>';
				}
		})
	}

function lineSendData(sendSeq){
	//type= copys, modifys
	var sendSeq = sendSeq ? sendSeq : '' ;
	var sendData = '';
	var sendObj = '';
	sendObj = $('.line'+sendSeq+'>td');

	var sendSerial = sendObj.each(function(){
		$(this).find('input, select').each(function() {
			sendData += $(this).attr('name');
			sendData += '=';
			sendData += $(this).val().replace(/&/g,'%26');
			sendData += '&';
        })
	})

	var msg = ''
	var resultMsg = ''
	msg = '수정하시겠습니까?'
	resultMsg = '수정되었습니다.'
	sendData += 'seq='+sendSeq;
	
	if(confirm(msg)){
		$.ajax({
			method:'POST',
			url: '../api/apiTempCompany.php',
			dataType:'text',
			data: sendData,
			success:function(data){
				alert('수정되었습니다.');
				printAjax();
				opener.location.reload();
			},
			fail:function(){
				alert('정상적으로 처리되지 않았습니다.')
			}
		})
	}
}

  function allSubmit(){
		  if(confirm("실제 등록을 진행할까요?")){
				loadingAct();
			  $.ajax({
				  url: '../api/apiTempCompany.php',
				  type:'PUT',
				  data:'allSubmit=Y',
				  dataType:'text',
				  success:function(data){
					  alert('등록 되었습니다.');
						opener.location.reload();
						window.close();
				  },
				  fail:function(){
					  alert('등록 실패하였습니다.');
				  }
			  })
				loadingAct();
			}
  }

  function allDelete(){
		  if(confirm("전체 삭제하시겠습니까?")){
			  $.ajax({
				  url: '../api/apiTempCompany.php',
				  type:'DELETE',
				  data:'allDelete=Y',
				  dataType:'text',
				  success:function(data){
					  alert('삭제 되었습니다.');
						opener.location.reload();
						window.close();
				  },
				  fail:function(){
					  alert('삭제 실패하였습니다.');
				  }
			  })
			}
  }

function loadingAct(){
	var loadingScreen = '<div class="loadingScreen"><img src="../images/global/loading.gif" alt="loading"></div>';
	if($('body').find('.loadingScreen').length == 0){
		$('body').append(loadingScreen);
	}else{
		$('.loadingScreen').fadeOut('fast',function(){
			$(this).remove();
		});
	}
}
</script>
<style type="text/css">
@import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
/* font-family:'Nanum gothic', serif; */
body { overflow:hidden; font-family:'Nanum gothic', serif; font-size:12px; }

div.infoArea { overflow-Y:scroll; height:672px; border-top:1px solid #666; border-bottom:1px solid #666; }
div.infoArea table { width:100%; border-collapse:collapse; font-size:12px; }
div.infoArea table td { background:#f4f4f4; border:1px solid #ccc; padding:10px 5px 4px; }
div.infoArea table th { border:1px solid #ccc; padding:8px 0; color:#666; }
div.infoArea table td, div.infoArea table th { line-height:20px; }
div.infoArea table td.center { text-align:center !important; }
div.infoArea input { height:26px; margin-bottom:6px; border:1px solid #999; padding:0 5px; vertical-align:middle; width:80px; }
div.infoArea input.error { border:2px solid #ff0000 !important; }
input[name="userName"], input[name="birth"] { width:46px !important; }
input[name="sex"], input[name="serviceType"] { width:20px !important; }
input[name="mobile01"], input[name="mobile02"], input[name="mobile03"] { width:32px !important; }
input[name="email01"], input[name="email02"] { width:80px !important; }
input[name="price"], input[name="rPrice"] { width:60px !important; }
div.infoArea button { height:32px; border:none; background:#666; color:#fff; font-weight:800; }

div.totalArea { overflow:hidden; border:1px solid #ccc; margin:10px 0; }
div.totalArea > h1 { float:left; width:200px; margin-right:10px; border-right:1px solid #ccc; text-align:center; font-size:15px; line-height:30px; }
div.totalArea > h1 strong { font-size:26px; font-weight:800; }
div.totalArea > h1 button { height:32px; border:none; background:#343434; font-style:15px; color:#fff; font-weight:800; }
div.totalArea > div { padding:10px; }
div.totalArea > div button { border:1px solid #666; margin:2.5px; background:#fff; line-height:32px; }
div.totalArea > div button:hover { background:#efefef; }
</style>
</head>

<body>
  <div class="totalArea"></div>
  <div class="infoArea">
    <form name="realUploadform" class="realUploadform" method="post">
      <table>
        <thead>
          <tr>
            <th style="width:30px;">번호</th>
            <th style="width:140px;">회사명<br />(사업자아이디 / 사업자번호)</th>
						<th style="width:180px;">대표자명<br />주소</th>
            <th style="width:70px;">회사 전화번호<br />회사 팩스번호</th>            
            <th style="width:70px;">전자계산서이메일<br />(업태 / 업종)</th>
            <th style="width:70px;">교육담당자<br />(교육담당자ID)</th>
            <th style="width:70px;">교육담당자 연락처<br />교육담당자 이메일</th>
						<th style="width:70px;">운영담당자<br />(운영담당자ID)</th>
            <th style="width:70px;">운영담당자 연락처<br />운영담당자 이메일</th>
						<th style="width:70px;">영업담당자<br />(영업담당자ID)</th>
            <th style="width:70px;">영업담당자 연락처<br />영업담당자 이메일</th>
            <th style="width:70px;">회사규모 <br />사이버교육센터 사용여부</th>
            <th style="width:70px;">메모</th>
            <th style="width:90px;">수정/삭제</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="notResult" colspan="15">등록된 수강 데이터가 없습니다.</td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</body>

</html>