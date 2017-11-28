<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>수강 임시 등록</title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var useApi = '/api/apiTempRegister.php';
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

										if(this.userID == '' || this.userID == null) { // ID를 등록하지 않은 경우 error
											var userID = 'class="error"';
										} else {
											var userID = '';
										}
										if(this.userName == '' || this.userName == null) { // 등록된 수강생ID 가 없는 경우 error
											var userName = 'class="error"';
										} else {
											var userName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="userID" value="'+this.userID+'" '+userID+'>&nbsp;/&nbsp;'; //ID
										tempList +='<input type="text" name="userName" value="'+this.userName+'" '+userName+'><br />'; //이름

										if(this.birth == '' || this.birth == null) { // 생년월일이 없는 경우 error
											var birth = 'class="error"';
										} else {
											var birth = '';
										}
										if(this.birth.length != 6) { // 생년월일이 6자리보다 작은 경우 error
											var birth = 'class="error"';
										} else {
											var birth = '';
										}
										if(this.sex == '' || this.sex == null) { // 성별을 기입하지 않은 경우 error
											var sex = 'class="error"';
										} else {
											var sex = '';
										}
										tempList +='<input type="tel" name="birth" value="'+this.birth+'" '+birth+'>&nbsp;/&nbsp;'; //생년월일
										tempList +='<input type="tel" name="sex" value="'+this.sex+'" '+sex+'>'; //성별
										tempList +='</td>';

										if(this.mobile01 == '' || this.mobile01 == null) { // 전화번호 앞자리가 없는 경우 error
											var mobile01 = 'class="error"';
										} else {
											var mobile01 = '';
										}
										if(this.mobile02 == '' || this.mobile02 == null) { // 전화번호 중간자리가 없는 경우 error
											var mobile02 = 'class="error"';
										} else {
											var mobile02 = '';
										}
										if(this.mobile03 == '' || this.mobile03 == null) { // 전화번호 뒷자리가 없는 경우 error
											var mobile03 = 'class="error"';
										} else {
											var mobile03 = '';
										}
										if(this.email01 == '' || this.email01 == null) { // 이메일 앞자리가 없는경우 error
											var email01 = 'class="error"';
										} else {
											var email01 = '';
										}
										if(this.email02 == '' || this.email02 == null) { // 이메일 뒷자리가 없는경우 error
											var email02 = 'class="error"';
										} else {
											var email02 = '';
										}
										tempList +='<td>';
										tempList +='<input type="tel" name="mobile01" value="'+this.mobile01+'" '+mobile01+'>&nbsp;-&nbsp;';
										tempList +='<input type="tel" name="mobile02" value="'+this.mobile02+'" '+mobile02+'>&nbsp;-&nbsp;';
										tempList +='<input type="tel" name="mobile03" value="'+this.mobile03+'" '+mobile03+'><br/>';//휴대폰
										tempList +='<input type="text" name="email01" value="'+this.email01+'" '+email01+'>&nbsp;@&nbsp;';
										tempList +='<input type="text" name="email02" value="'+this.email02+'" '+email02+'>';//이메일
										tempList +='</td>';
										
										if(this.companyCode.length > 10) { // 사업자번호 10자리 넘을경우 error
											var companyCode = 'class="error"';
										} else {
											var companyCode = '';
										}
										if(this.companyName == '' || this.companyName == null) { // 회사명이 없는경우 error
											var companyName = 'class="error"';
										} else {
											var companyName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="companyCode" value="'+this.companyCode+'" '+companyCode+'><br />'; //사업자
										tempList +='<input type="text" name="companyName" value="'+this.companyName+'" '+companyName+' readonly>'; //회사명
										tempList +='</td>';

										tempList +='<td>';
										tempList +='<input type="text" name="lectureStart" value="'+this.lectureStart+'" ><br />'; //시작일
										tempList +='<input type="text" name="lectureEnd" value="'+this.lectureEnd+'" >'; //종료일
										tempList +='</td>';

										if(this.contentsCode.length != 6) { // 과정코드가 6자리가 아닌경우 error
											var contentsCode = 'class="error"';
										} else {
											var contentsCode = '';
										}
										if(this.contentsName == '' || this.contentsName == null) { // 과정코드가 6자리가 아닌경우 error
											var contentsName = 'class="error"';
										} else {
											var contentsName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="contentsCode" value="'+this.contentsCode+'" '+contentsCode+'><br />'; //콘텐츠코드
										tempList +='<input type="text" name="contentsName" value="'+this.contentsName+'" '+contentsName+' readonly>';//콘텐츠명
										tempList +='</td>';

										if(this.tutor == '' || this.tutor == null) { // 강사를 등록하지 않은 경우 error
											var tutor = 'class="error"';
										} else {
											var tutor = '';
										}
										if(this.tutorName == '' || this.tutorName == null) { // 등록된 강사ID가 없는 경우 error
											var tutorName = 'class="error"';
										} else {
											var tutorName = '';
										}
										tempList +='<td>';
										tempList +='<input type="text" name="tutor" value="'+this.tutor+'" '+tutor+'><br />'; //강사ID
										tempList +='<input type="text" name="tutorName" value="'+this.tutorName+'" '+tutorName+' readonly>'; //강사명
										tempList +='</td>';
					
										tempList +='<td>';
										tempList +='<input type="text" name="price" value="'+this.price+'"> 원<br /><input type="text" name="rPrice" value="'+this.rPrice+'"> 원'; //교육비, 환급비
										tempList +='</td>';

										tempList +='<td><input type="text" name="serviceType" value="'+this.serviceType+'"><br />';
										//tempList += this.inputDate.substr(0,10)+'<br />'+this.inputDate.slice(10)+'<br />';
										tempList += '수강횟수 : <strong>'+this.lectureEA+'</strong></td>';//수강구분,등록일,동일과정수강횟수
										tempList +='<td class="center"><button type="button" onClick="lineSendData(\''+this.seq+'\');">수정</button>&nbsp;';
										tempList +='<button type="button" onClick="deleteData(\''+this.seq+'\');">삭제</button></td>';
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
			url: '../api/apiTempRegister.php',
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

//공통선언 - 삭제
function deleteData(sendSeq){
	if(confirm("삭제하시겠습니까?")){
		$.ajax({
			url: '../api/apiTempRegister.php',
			type:'DELETE',
			data:{'seq':sendSeq},
			dataType:'text',
			success:function(data){
				top.location.reload();
				opener.location.reload();
			},
			fail:function(){
				alert('실패하였습니다.')
			}
		})
	}
}


  function allSubmit(){
		  if(confirm("실제 등록을 진행할까요?")){
				loadingAct();
			  $.ajax({
				  url: '../api/apiTempRegister.php',
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
				  url: '../api/apiTempRegister.php',
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
            <th style="width:140px;">개인정보<br />(ID/이름/생년월일/성별)</th>
            <th style="width:180px;">휴대폰<br />이메일</th>
            <th style="width:70px;">사업자번호<br />회사명</th>
            <th style="width:70px;">시작일<br />종료일</th>
            <th style="width:70px;">과정코드<br />과정명</th>
            <th style="width:70px;">첨삭강사ID<br />강사명</th>
            <th style="width:70px;">교육<br />환급비</th>
            <th style="width:70px;">수강구분<br />동일 수강횟수</th>
            <th style="width:90px;">수정/삭제</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="notResult" colspan="11">등록된 수강 데이터가 없습니다.</td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</body>

</html>