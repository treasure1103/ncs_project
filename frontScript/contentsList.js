//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트 소팅
function listAct(page){
	
	$.get(categoryApi,{'value01':'lectureCode'},function(data){
		var optSort01 = '';
		optSort01 += '<option value="">전체</option>';
		$.each(data.category,function(){
			optSort01 += '<option value="'+this.value01+'">';
			optSort01 += this.value02;
			optSort01 += '</option>'
		})

		var actionArea = '';
		actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';

		if(loginUserLevel <= 4) {
			actionArea += '<button type="button" onClick="writeAct(\'\',\'contentsWrite\')" class="fRight">과정추가</button>';
			actionArea += '<button type="button" onClick="location.href=\'contentsListExcelA.php\'" class="fRight">엑셀 다운로드</button>';
		}

		actionArea += '<select name="searchType">';	
		actionArea += '<option value="contentsName">과정명</option>';
		actionArea += '<option value="contentsCode">과정코드</option>';
		actionArea += '<option value="cp">CP사</option>';
		actionArea += '</select>&nbsp;';
		actionArea += '<input type="text" name="searchValue" />&nbsp;';
		actionArea += '<button type="button" onClick="searchAct()">검색하기</button>';
		actionArea += '<span style="margin-left:40px;">과정분류</span>';
		actionArea += '<select name="sort01" onchange="changeSort2(this);ajaxAct('+page+',\'\',this)">'+optSort01+'</select>';
		if(loginUserLevel <= 3) {
			actionArea += '<span style="margin-left:40px;">대표과정</span>';
		}
		actionArea += '<select name="main" onchange="searchAct()">';	
		actionArea += '<option value="">전체</option>';
		actionArea += '<option value="Y">대표과정만</option>';
		actionArea += '</select>&nbsp;';
		actionArea += '<span style="margin-left:40px;">사이트노출</span>';
		actionArea += '<select name="enabled" onchange="searchAct()">';	
		actionArea += '<option value="">전체</option>';
		actionArea += '<option value="Y">노출</option>';
		actionArea += '<option value="N">숨김</option>';
		actionArea += '</select>&nbsp;';
		actionArea += '</form>'
		actionArea += '</div>';
		$('#contents > h1').after(actionArea);
		
		//게시물 소팅부분
		var contents = '';
		contents += '<table><thead><tr>';
		contents += '<th style="width:60px;">번호</th>';
		contents += '<th>과정등급/코드/과정명</th>';
		contents += '<th style="width:200px">메모</th>';
		contents += '<th style="width:120px;">총차시/교육시간</th>';
		contents += '<th style="width:200px;">교육비</th>';
		contents += '<th style="width:120px;">등급/심사코드</th>';
		contents += '<th style="width:80px;">모바일</th>';
		contents += '<th style="width:100px;">사이트노출</th>';
		if(loginUserLevel <= 3) {
			contents += '<th style="width:100px;">대표과정</th>';
		}
		contents += '<th style="width:120px;">유효기간/인정만료</th>';
		if(loginUserLevel <= 4) {
			contents += '<th style="width:120px;">수정/상세</th>';
		}
		contents += '</tr></thead><tbody>'	;
		contents += '</tbody></table>';
		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSList');
		$('#contentsArea').html(contents);
		ajaxAct();
	})
}

function ajaxAct(sortDatas,sort01,sort02){
	loadingAct();
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	sort01 = sort01 ? sort01 : '';
	sort02 = sort02 ? sort02 : '';

	if(sort01 != ''){
		sort01 = sort01.options[sort01.selectedIndex].value;
		sort01s = '&sort01='+sort01
	}
	if(sort02 != ''){
		sort02 = sort02.options[sort02.selectedIndex].value;
		sort02s = '&sort02='+sort02
	}
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sort01s+sort02s+sortData,function(data){
		totalCount = data.totalCount;
		var lists = '';
		var i = 1;
		var contentsGrade = '';
		var passCode = '';
		var contentsPeriod = '';
		var contentsExpire = '';
		var lectureStart = '';
		var midEA = '';
		var testEA = '';
		var reportEA = '';
		var BBSListBg = '';
		var i = totalCount;
		//alert(page);
		if(page != 1){
			i = totalCount - ((page-1)*listCount)
		}
		if (totalCount != 0){
			$.each(data.contents, function(){
				if(this.enabled == 'N'){
					BBSListBg = 'class="BBSListBg"';
				}
				lists += '<tr '+BBSListBg+'>';
				BBSListBg = '';
				
				//번호
				lists += '<td>'+i+'</td>';
				
				//등급,코드,과정명
				lists += '<td class="left">';
				if(this.contentsGrade == null || this.contentsGrade == '' || this.contentsPeriod == '') {
					contentsGrade = '등급 미등록';
				} else {
					contentsGrade = this.contentsGrade+' 등급';
				}
				if(this.passCode == null || this.passCode == '') {
					passCode = '심사코드 미등록';
				} else {
					passCode = this.passCode;
				}
				if(this.sort01Name == null) {
					var sort01Name = '대분류 미등록';
				} else {
					var sort01Name = this.sort01Name;
				}
				if(this.sort02Name == null) {
					var sort02Name = '소분류 미등록';
				} else {
					var sort02Name = this.sort02Name;
				}
				lists += ':: '+sort01Name+' >> '+sort02Name+'<br />';
				lists += '[&nbsp;'+contentsGrade+'&nbsp;]&nbsp;'; //과정등급
				lists += this.contentsCode+'<br />';
				lists += '<strong  onClick="globalModalAct(\'contentsView\',\'\',\''+this.contentsCode+'\')" style="cursor:pointer;">'+this.contentsName+'</strong><br />';
				if(loginUserLevel <= 4) {
					lists += '<button type="button" onClick="writeAct('+this.seq+',\'contentsWrite\')">과정정보 수정하기</button>';
				}
				lists += '</td>';

				//메모
				lists += '<td>';
				if (this.memo == null){
					memo = '';
				} else { 
					lists += this.memo;
				}
				lists += '</td>';

				//총차시
				lists += '<td>';
				lists += this.chapter+'차시&nbsp;/&nbsp;'+this.contentsTime+'시간<br />';
				lists += '<button type="button" onClick="writeAct(\''+this.contentsCode+'\',\'chapterWrite\')">차시보기</button><br />';
				lists += '</td>';
				
				//교육비
				lists += '<td class="right">';
				lists +='[일반 교육비] '+toPriceNum(this.price)+'원</br>';
				lists +='[우선지원] '+toPriceNum(this.rPrice01)+'원</br>';
				lists +='[대규모 / 1000인 미만] '+toPriceNum(this.rPrice02)+'원</br>';
				lists +='[대규모 / 1000인 이상] '+toPriceNum(this.rPrice03)+'원</br>';
				lists += '</td>';
				
				//등급/심사코드
				lists += '<td>';
				lists += contentsGrade+'<br />'+passCode;
				lists += '<button onClick="window.open(\'../contentsPrint/?code='+this.contentsCode+'\')">과정상세</button>'
				lists += '</td>';
				
				//모바일
				lists += '<td>';
				if(this.mobile =='Y'){
					lists += '지원';
				}else{
					lists += '미지원';
				}
				lists += '</td>';

				//사이트노출
				lists += '<td>';
				if(this.enabled =='Y'){
					lists += '노출';
				}else{
					lists += '숨김';
				}
				lists += '</td>';

				//대표과정
				if (loginUserLevel <= 3){
					lists += '<td>';
					if(this.mainContents =='Y'){
						lists += '선정됨<br />순번 : '+this.mainOrderBy;
					}else{
						lists += '<button type="button" onClick="mainContents(\''+this.seq+'\')">선정하기</button></td>';						
					}
					lists += '</td>';
				}				
				

				//유효기간/인정만료
				if(this.contentsPeriod == null || this.contentsPeriod == '') {
					contentsPeriod = '미등록';
				} else {
					contentsPeriod = this.contentsPeriod;
				}
				if(this.contentsExpire == null || this.contentsExpire == '') {
					contentsExpire = '미등록';
				} else {
					contentsExpire = this.contentsExpire;
				}
				if(this.lectureStart == null || this.lectureStart == '') {
					lectureStart = '생성하기';
				} else {
					lectureStart = this.lectureStart;
				}
				lists += '<td>'+contentsPeriod+'<br />'+contentsExpire+'<br />';
				if(loginUserLevel <= 4) {
					lists += '<button type="button" onClick="sampleID(\''+this.contentsCode+'\')">ID:'+lectureStart+'</button></td>';
				}

				//교육비
				midEA = parseInt(this.mid01EA)+parseInt(this.mid02EA)+parseInt(this.mid03EA)+parseInt(this.mid04EA);
				testEA = parseInt(this.test01EA)+parseInt(this.test02EA)+parseInt(this.test03EA)+parseInt(this.test04EA);
				reportEA = parseInt(this.reportEA);
				
				//수정/상세
				if(loginUserLevel <= 4) {
					lists += '<td>'
					lists += '<button type="button" onClick="writeAct(\''+this.contentsCode+'\',\'testWrite\',\'mid\')">중간평가 : '+midEA+'</button><br />';
					lists += '<button type="button" onClick="writeAct(\''+this.contentsCode+'\',\'testWrite\',\'final\')">최종평가 : '+testEA+'</button><br />';
					lists += '<button type="button" onClick="writeAct(\''+this.contentsCode+'\',\'reportWrite\')">과제관리 : '+reportEA+'</button><br />';
					lists += '</td>';
				}
				
				lists += '</tr>';
				i--;
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">검색 결과가 없습니다.</td></tr>';
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
		loadingAct();
	})
}

function changeSort2(obj){
	obj = obj.options[obj.selectedIndex].value;
	sort02s = '';
	$('select[name="sort02"]').remove();
	if(obj != ''){
		$.get(categoryApi,{'value01':obj},function(data){
			var selectWrite = '';
			selectWrite += '<select name="sort02" onchange="ajaxAct('+page+',\'\',\'\',this)">';
			selectWrite += '<option value="">전체</option>';
			$.each(data.category,function(){
				selectWrite += '<option value="'+this.value01+'">';
				selectWrite += this.value02;
				selectWrite += '</option>';
			})
			selectWrite += '</select>'
			$('select[name="sort01"]').after(selectWrite)
		})
	}
}

function mainContents(seq){
	if(confirm('대표과정으로 선정하시겠습니까? 현재 순번이 첫번째인 과정은 대표과정에서 제외되고 두번째 과정이 첫번째로 변경됩니다.')) {
		$.ajax({
			url:useApi,
			type:'POST',
			data:'main=Y&seq='+seq,
			dataType:'JSON',
			success:function(data){
				alert(data.result);
				ajaxAct();
			}
		})
	}
}