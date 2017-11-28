//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//게시판 보기 스크립트 시작
function writeCenter(companyID){
	//상단메뉴
	$('.searchArea').remove();
	
	//출력변수 지정
	
		
	if(companyID != ''){
		var writeAjax = $.get(centerApi,{'companyID':companyID},function(data){
			$.each(data.studyCenter, function(){
				seq = this.seq;
				companyID = this.companyID;
				attachURL = this.attachURL;
				studyColor = this.studyColor;
				studyRequestStart = this.studyRequestStart ? this.studyRequestStart : '1900-01-01';
				studyRequestEnd = this.studyRequestEnd ? this.studyRequestEnd : '1900-01-01';
				studyStart = this.studyStart ? this.studyStart : '1900-01-01';
				studyEnd = this.studyEnd ? this.studyEnd : '1900-01-01';
				studyColor = this.studyColor;
				studyLogo = this.studyLogo;
				studyMainImg = this.studyMainImg;
				studyFooterImg = this.studyFooterImg;
				contentsMapping = this.contentsMapping;
				if(this.mainContents != null){
					contentsCode = this.mainContents[0].contentsCode;
					contentsName = this.mainContents[0].contentsName;
				}else {
					contentsCode ='';
					contentsName ='';
				}               
			})
			writePrint()
		})
	}else{
		writePrint()
	}
	
	//게시판 생성
	function writePrint(){
		var writes = '';
		var startDate = '';
		var endDate = '';

		writes += '<form class="writeform" method="post" action="'+centerApi+'" enctype="multipart/form-data">';
		//seq값 선언
		writes += '<input type="hidden" name="seq" value="'+seq+'" />';			
		writes += '<input type="hidden" name="companyID" value="'+companyID+'" />';			
		//입력영역 시작
		writes += '<ul>';

		//사이트주소
		writes += '<li>';
		writes += '<h1>사이트 URL</h1>';
		writes += '<a href="http://'+companyID+'.ncscenter.kr" target="_blank">'+companyID+'.ncscenter.kr</a>';
		writes += '</li>';		

		//로고 등록
		writes += '<li>';
		writes += '<div class="halfDiv">';
		writes += '<h1>로고 등록<br />(size자유, png)</h1>';
		if(studyLogo == 'N' || studyLogo == null){
			//writes += '<input type="file" name="studyLogo" class="name" value="'+studyLogo+'" />';
			writes += '<input type="file" name="studyLogo" class="name" />'
		}else{
			writes += '<div id="studyLogo" class="attachFile"><img src="'+attachURL+studyLogo+'" style="width:100px;"><br /><button type="button" >등록이미지</button></div>';
			writes += '<input type="file" name="studyLogo" class="name" />';
		}
		
		writes += '</div>';

		//메인이미지 등록
		writes += '<div class="halfDiv">';
		writes += '<h1>메인이미지 등록<br />(가로 1000px,<br />세로 자유, jpg)</h1>';
		if(studyMainImg == 'N' || studyMainImg == null){
			writes += '<input type="file" name="studyMainImg" class="name" />'
		}else{
			writes += '<div id="studyMainImg" class="attachFile"><img src="'+attachURL+studyMainImg+'" style="width:100px;"><br /><button type="button">등록이미지</button></div>';
			writes += '&nbsp;<input type="file" name="studyMainImg" class="name" />';
		}
		writes += '</div>';
		writes += '</li>';

		//배경색 지정
		writes += '<li>';
		writes += '<h1>하단이미지 등록<br />(가로 1000px,<br />세로 자유, jpg)</h1>';
		if(studyFooterImg == 'N' || studyFooterImg == null){
			writes += '<input type="file" name="studyFooterImg" class="name" />'
		}else{
			writes += '<div id="studyFooterImg" class="attachFile"><img src="'+attachURL+studyFooterImg+'" style="width:100px;"><br /><button type="button">등록이미지</button></div>';
			writes += '&nbsp;<input type="file" name="studyFooterImg" class="name" />';
		}
		writes += '</li>';		

		var selected01 = '';
		var selected02 = '';
		var selected03 = '';
		var selected04 = '';
		var selected05 = '';
		var mapping01 = '';
		var mapping02 = '';

		if(studyColor == '1') {
			selected01 = 'selected="selected"';
		} else if(studyColor == '2') {
			selected02 = 'selected="selected"';
		} else if(studyColor == '3') {
			selected03 = 'selected="selected"';
		} else if(studyColor == '4') {
			selected04 = 'selected="selected"';
		}  else if(studyColor == '5') {
			selected05 = 'selected="selected"';
		}

		if(contentsMapping == 'N') {
			mapping01 = 'selected="selected"';
		} else if(contentsMapping == 'Y') {
			mapping02 = 'selected="selected"';
		}

		//1 - 녹색
		//5 - 파검

		//배경색 지정
		writes += '<li>';
		writes += '<h1>배경색 지정</h1>';
		writes += '<select name="studyColor" id="studyColor" class="">';
		writes += '<option>선택하세요</option>';
		writes += '<option value="1" '+selected01+'>녹</option>';
		writes += '<option value="2" '+selected02+'>파랑</option>';
		writes += '<option value="5" '+selected05+'>파랑/검정</option>';
		writes += '</select>';
		writes += '</li>';

				
		//신청 기간
		writes += '<li>';
		writes += '<h1>신청 기간</h1>';
		writes += '<div class="datePicker"><input type="text" name="studyRequestStart" class="cal" value="'+studyRequestStart+'" readonly="readonly" /></div>&nbsp;~&nbsp;';
		writes += '<div class="datePicker"><input type="text" name="studyRequestEnd" class="cal"  value="'+studyRequestEnd+'" readonly="readonly" /></div>&nbsp;';
		writes += '</li>';
		
		//수강 기간
		writes += '<li>';
		writes += '<h1>수강 기간</h1>';
		writes += '<div class="datePicker"><input type="text" name="studyStart" class="cal" value="'+studyStart+'" readonly="readonly" /></div>&nbsp;~&nbsp;';
		writes += '<div class="datePicker"><input type="text" name="studyEnd" class="cal"  value="'+studyEnd+'" readonly="readonly" /></div>&nbsp;';
		writes += '</li>';	

		writes += '<li><h1>메인 콘텐츠</h1>';
		writes += '<div id="mainContents" class="address">';
		if(contentsName == null || contentsName == ''){
			contentsName ="현재 추천 과정이 없습니다.";
		}
		writes += '현재 과정 : '+contentsName;
		writes += '<br /><input name="searchName" type="text" />&nbsp;';
		writes += '<button type="button" onClick="searchSelect(\'mainContents\',\''+chainsearchApi+'\',\'contents\')">검색</button>';
		writes += '</div>';
		//div의 아이디값과 새로 등록될 select 네임의 동일화
		writes += '</li>';

		writes += '<li>';
		writes += '<div class="halfDiv">';
		writes += '<h1>콘텐츠 매핑<br>사용여부</h1>';
		writes += '<select name="contentsMapping" id="contentsMapping" onChange="contentsMappingS(this.value,\''+companyID+'\')">';
		writes += '<option>선택하세요</option>';
		writes += '<option value="N" '+mapping01+' >미사용</option>';
		writes += '<option value="Y" '+mapping02+' >사용</option>';
		writes += '</select>';
		writes += '</div>';
		writes += '<div id="append" class="halfDiv">';
		if(seq != ''){
			if(contentsMapping == 'Y'){
				writes += '<div>';
				writes +='<h1>콘텐츠 매핑</h1>';
				writes +='<button type="button" class="studyMap" onclick="studyMap(\''+companyID+'\')">과정등록하기</button>';
				writes += '</div>';
			}
		}
		writes += '</div>';
		writes += '</li>';

		/*
		//메인 콘텐츠
		writes += '<li><h1>메인 콘텐츠</h1>';
		writes += '<div id="mainContents" class="address"><input name="searchName" type="text" value="'+contentsName+'" />';
		writes += '<button type="button" onClick="searchSelect(\'mainContents\',\''+chainsearchApi+'\',\'contents\')">검색</button>';
		writes += '<input name="mainContents" type="text" value="'+contentsName+'/'+contentsCode+'"  readonly="readonly" style="width:330px; margin-left:10px;"/></div>';
		writes += '</li>';
		*/

		//버튼영역
		writes += '<div class="btnArea">';
		writes += '<button type="button" onClick="multipartSendData(\'writeform\', \'modal\')">';
		if(seq != ''){
			writes += '수정하기';
		}else{
			writes += '등록하기';
		}
		writes += '</button>'
		writes += '<button type="button" onClick="listAct('+page+')">목록보기</button>';
		writes += '</div>';
		writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
		
		writes += '</form>';
		
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		pickerAct();//데이트피커 사용
		findOpt();
		emailSelect();
        mapping(companyID) // 과정 맵핑 노출
		$('#zipCodeArea, .findZipCode').click(function(){zipCodeFind()})
		var	mustInput = '&nbsp;&nbsp;<strong class="price">(*)</strong>';
		$('.mustCheck > h1').append(mustInput)//필수요소 사용
		$('input[name="companyID"]').keydown(function(){keyCheck('numbEng',this)})
		$('input[type="tel"]').keyup(function(){keyCheck('numb',this)})
	}

    function mapping(companyID){
        var allCount = '';
        $.get('../api/apiContents.php', function(data){
            allCount = data.totalCount;
        }).done(function(){
            $.get('../api/apiContentsMapping.php','companyID='+companyID+'',function(data){
                var totalCount = data.totalCount;
                if (totalCount != 0){
                    $('.studyMap').after('<span>&nbsp;현재 <strong class="red" style="padding:0;">'+totalCount+'</strong>개 과정이 등록되어있습니다.</span>')
                }else if(totalCount == allCount){
                    $('.studyMap').after('<span>&nbsp;현재 전체 과정이 등록되어있습니다.</span>')
                }else{
                    $('.studyMap').after('<span>&nbsp;현재 등록된 과정이 없습니다.</span>')
                }
            })
        })
    }
}

function contentsMappingS(type,companyID){
	if(type == 'Y'){
		$('.writeform li #append').append('<div><h1>콘텐츠 매핑</h1><button type="button" class="studyMap" onclick="studyMap(\''+companyID+'\')">과정등록하기</button></div>');
	}else{
		$('.writeform li #append div').remove();
	}
}
//공통화를 위한 페이지 막음

function viewAct(seq){
	writeAct(seq)
}

function studyMap(companyID){
    var companyMapLink = './02_companyMap.php?companyID='+companyID+'&mapping=Y';
    window.open(companyMapLink,"location=yes,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","study")
}