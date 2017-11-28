function modalAlign(){
	var modalMarginX = Number($('#modal > div:not(".closeArea")').outerWidth())/2*-1;
	var modalMarginY = Number($('#modal > div:not(".closeArea")').outerHeight())/2*-1;
	modalMarginX = modalMarginX + 'px';
	modalMarginY = modalMarginY + 'px';
	$('#modal').prepend('<div class="closeArea"></div>')
	$('#modal div.closeArea').bind('click',function(e){
		$('#modal').remove();		
	});
	$('#modal > div:not(".closeArea")').css({'margin-top':modalMarginY,'margin-left':modalMarginX})
}

function modalClose(){
	$('#modal').remove();
}

//리스트형태 검색 search Ajax
function searchAct(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	page = 1;
	ajaxAct(searchValue);
}

//검색 후 셀렉트 생성
function searchSelect(obj,apiName,optValue){
	var searchValue = '';

	$('#'+obj+'>input').each(function(){
		if($(this).val() != ''){
			searchValue += $(this).attr('name');
			searchValue += '=';
			searchValue += encodeURIComponent($(this).val());
			//2017-05-10 이응민 추가 ->
			searchValue += '&';
			searchValue += 'sqlLimit=memberSearch';
			if (loginUserLevel == 5) {
				searchValue += '&';
				searchValue += 'userID='+loginUserID;
			}
			//2017-05-10 이응민 추가 끝
			if(apiName=='../api/apiMember.php'){
				if(typeof(optValue) != 'undefined') {
					searchValue += '&';
					searchValue += 'userLevel='+optValue;
				}
			}else if(apiName=='../api/apiSearch.php'){
				if(typeof(optValue) != 'undefined') {
					searchValue += '&';
					searchValue += 'searchMode='+optValue;
				}
			}
			$.get(apiName,searchValue,function(data){
				var makeSelect = ''
				if(data.totalCount != 0){
					if(apiName=='../api/apiMember.php'){
						$.each(data.member, function(){
							makeSelect += '<option value="'+this.userID+'">'
							makeSelect += this.userName+'&nbsp;|&nbsp;'+this.userID+'&nbsp;|&nbsp;'+ this.company.companyName+'&nbsp;';
							makeSelect += '</option>'
						})
					}else if(apiName=='../api/apiCompany.php'){
						$.each(data.company, function(){
							makeSelect += '<option value="'+this.companyCode+'">'
							makeSelect += this.companyName+'&nbsp;|&nbsp;'+this.companyCode+'&nbsp;';
							makeSelect += '</option>'
						})
					}else if(apiName=='../api/apiSearch.php'){
						$.each(data.searchResult, function(){
							makeSelect += '<option value="'+this.searchCode+'">'
							makeSelect += this.searchName+'&nbsp;|&nbsp;'+this.searchCode+'&nbsp;';
							makeSelect += '</option>'
						})
					}
					if($(document).find('select[name="'+obj+'"]').index() == -1){
						$('#'+obj).append('&nbsp;<select name="'+obj+'"></select>');				
					}
					$('select[name="'+obj+'"]').html(makeSelect);
				}else{
					alert('검색결과가 없습니다.')
				}
			})
		}else{
			alert('검색어를 입력해주세요')
		}
    });
}

//아이디 중복 체크
function idUseCheck(useApi,checkKey){
	var checkValues = $('input[name="'+checkKey+'"]').val();
	var checkData = checkKey+'='+checkValues
	$('input[name="idUseOk"]').prop('checked',false);
	$.ajax({
		url:useApi,
		type:'PUT',
		data:checkData,
		dataType:'json',
		success: function(data){
			if(data.result == 'success'){
				alert('사용가능한 아이디입니다.')
				$('input[name="idUseOk"]').prop('checked',true);
			}else{
				alert('중복 또는 사용할 수 없는 아이디입니다.');
				$('input[name="idUseOk"]').prop('checked',false);
			}		
		}
	})
}

// 사업자아이디 중복 체크
function companyCodeCheck(useApi,checkKey){
	var checkValues = $('input[name="'+checkKey+'"]').val();
	var checkData = checkKey+'='+checkValues
	$('input[name="companyCodeOK"]').prop('checked',false);
	$.ajax({
		url:useApi,
		type:'PUT',
		data:checkData,
		dataType:'json',
		success: function(data){
			if(data.result == 'success'){
				alert('사용가능한 사업자번호입니다.')
				$('input[name="companyCodeOK"]').prop('checked',true);
			}else{
				alert('중복 또는 사용할 수 없는 사업자번호입니다.');
				$('input[name="companyCodeOK"]').prop('checked',false);
			}		
		}
	})
}

//체크박스 전체선택
function checkboxAllCheck(checkedClass){
	if($('#'+checkedClass).prop('checked')){
		$('.'+checkedClass).prop('checked',true);
	}else{
		$('.'+checkedClass).prop('checked',false);
	}
}

//가격형태 콤마 변환,복귀
function toPriceNum(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
function forPriceNum(x) {
    return x.toString().replace(/,/g, '');
}

//파일 폼
function fileformAct(){	
	$('input[type="file"]').each(function(){
		var thisName = $(this).attr('name');
		var preLabel = '';
		preLabel += '<label class="AttachFiles"><span>파일찾기</span>';
		preLabel += '<input type="file" name="'+thisName+'" style="display:none" onchange="fileAddAct(this,\''+thisName+'\')" />';
		preLabel += '</label>';
		$(this).after(preLabel);
		$(this).remove();
	})
}

//파일 첨부형태 변환
function fileAddAct(fileName,root){
	var insertSpan = $('input[name="'+root+'"]').parent('label').children('span');
	var fileName= fileName.value;
	fileName = fileName.replace('C:\\fakepath\\','');
	if(fileName != ''){
		insertSpan.html(fileName)
	}else{
		insertSpan.html('파일찾기')
	}
}
//파일 첨부 삭제
function deleteFileAct(inputName){
	var deleteDiv = $('#'+inputName)
	deleteDiv.parent('li, td, div').children('input[type="checkbox"]').prop('checked',true);
	var preLabel = '';
	preLabel += '<label class="AttachFiles"><span>파일찾기</span>';
	preLabel += '<input type="file" name="'+inputName+'" style="display:none" onchange="fileAddAct(this,\''+inputName+'\')" />';
	preLabel += '</label>';
	deleteDiv.after(preLabel);
	deleteDiv.remove()
}

//인풋내 문자열 검사
function keyCheck(keytypes,inputValue){
	if(keytypes == "numb"){
		var checkCode = inputValue.value.charCodeAt(inputValue.value.length-1); 
		var str;		
		if(inputValue.value.length > 0 && !(checkCode >= 48 && checkCode <= 57)) {
			 alert("숫자만 입력가능합니다.");			 
			 var thisLength = inputValue.value.length;
			 for(var i=0; i<thisLength; i++) {
			  checkCode_for = inputValue.value.charCodeAt( inputValue.value.length-1 ); 
			  if( !(checkCode_for >= 48 && checkCode_for <= 57) ) {
			   str = inputValue.value.substring(0, inputValue.value.length-1);
			   inputValue.value = str;
			  }
			 }			 
			 inputValue.focus();		 
		 }else if( inputValue.value.length == 0 || checkCode >= 48 && checkCode <= 57 ) {  		
			inputValue.focus(); 
		} 
	}else if(keytypes == "numbEng"){
		if(event.keyCode == 8 || event.keyCode == 9 || event.keycode == 37 || event.keyCode == 39 || event.keyCode == 46) return;		
		inputValue.value = inputValue.value.replace(/[\ㄱ-ㅎㅏ-ㅣ가-힇]/g,'');
	}
}

//팝업 및 쿠키;
function openPopup(name,linkAddress,linkType,images,width,height,left){
	left = left ? left : 25;
		var openPopupWindow = window.open("",'"'+name+'"','"toolbar=no, scrollbars=no, resizable=no, top=25, left='+left+' , width='+width+', height='+height+'"')
		var popupContents = '';
		popupContents += '<body style="margin:0; padding:0;">';
		popupContents += '<div class="popup">';
		if(linkAddress != ''){
			popupContents += '<a href="'+linkAddress+'" target="'+linkType+'">'
			popupContents += '<img src="../attach/popup/'+images+'" /><br />';
			popupContents += '</a>'
		}else{
			popupContents += '<img src="../attach/popup/'+images+'" /><br />';
		}
		popupContents += '<button type="button" onClick="self.close()" style="width:100%; height:40px; border:none; background:#565656; color:#efefef;">일주일간 이창을 열지 않음</button>'
		popupContents += '</div>';
		popupContents += '</body>';
		openPopupWindow.document.write(popupContents);
		popupContents = '';
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

//리스트 카운트 업데이트
function listCountUpdate(v){
	listCount = v;
}

//Json Parse시 없는 데이터 입력시 빈값 처리
function returnData(vals){
	if(vals == undefined){
		vals = '';
	}
	return vals;
}