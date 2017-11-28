//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//게시판 보기 스크립트 시작
function writeAct(writeSeq, Types, testType){
	writeSeq = writeSeq ? writeSeq : '';
	seq = writeSeq;
	Types = Types ? Types : writeType;
	writeType = Types;
	//testType = testType ? testType : '';
	//상단메뉴
	$('.searchArea').remove();
	
	//출력변수 지정 - 컨텐츠 등록 수정용
	var contentsCode = ''; // 차시 중복 사용가능
	var contentsName = ''; // 차시 중복 사용가능
	var previewImage = '';
	var chapter = ''; //차시 중복 사용 가능
	var contentsTime = '';
	var limited = '';
	var price = '';
	var rPrice01 = '';
	var rPrice02 = '';
	var rPrice03 = '';
	var intro = '';
	var target = '';
	var goal = '';	//차시 중복사용 가능
	var professor = ''; //차시 중복사용 가능
	var passCode = '';
	var passProgress = '';
	var passTest = '';
	var passReport = '';
	var contentsPeriod = '';
	var contentsExpire = '';
	var contentsGrade = '';
	var sort01 = '';
	var sort02 = '';
	var bookImage = '';	
	var bookPrice = '';
	var bookIntro = '';	
	var mobile = '';	
	var serviceType = '';	
	var sourceType = '';	
	var cp = '';	
	var commission = '';
	var testTime = '';
	var mid01EA = '';
	var mid02EA = '';
	var mid03EA = '';
	var mid04EA = '';
	var test01EA = '';
	var test02EA = '';
	var test03EA = '';
	var test04EA = '';
	var reportEA = '';
	var mid01Score = '';
	var mid02Score = '';
	var mid03Score = '';
	var mid04Score = '';
	var test01Score = '';
	var test02Score = '';
	var test03Score = '';
	var test04Score = '';
	var reportScore = '';
	var midRate = '';
	var testRate = '';
	var reportRate = '';
	var testAvailable = '';
	var enabled = '';
	var previewImageURL = ''; //미리보기 이미지
	var bookImageURL = ''; //교재 이미지	
	var totalPassMid = '';
	var totalPassTest = '';
	var totalPassReport = '';
	var passScore = '';
	var progressCheck = '';
	var attachFile = '';
	var midTestChapter = '';
	var midTestProgress = '';
	
	//출력변수 지정 - 차시수정
	var chapterName = '';
	var content = '';
	var activity = '';
	var chapterPath = '';
	var chapterSize = '';
	var chapterMobilePath = '';
	
	if(seq != ''){
		if(writeType=='contentsWrite'){
			var writeAjax = $.get(useApi,{'seq':seq},function(data){
				previewImageURL = data.previewImageURL; //미리보기 이미지
				bookImageURL = data.bookImageURL; //교재 이미지
				$.each(data.contents, function(){
					seq = this.seq
					contentsCode = this.contentsCode;
					contentsName = this.contentsName;
					previewImage = this.previewImage;
					chapter = this.chapter;
					contentsTime = this.contentsTime;
					limited = this.limited;
					price = this.price;
					rPrice01 = this.rPrice01;
					rPrice02 = this.rPrice02;
					rPrice03 = this.rPrice03;
					intro = this.intro;
					target = this.target;
					goal = this.goal;
					professor = this.professor;
					if(this.passCode == null) {
						passCode = '';
					} else {
						passCode = this.passCode;
					}
					passProgress = this.passProgress;
					passTest = this.passTest;
					passReport = this.passReport;
					if(this.contentsPeriod == null) {
						contentsPeriod = '';
					} else {
						contentsPeriod = this.contentsPeriod;
					}
					if(this.contentsExpire == null) {
						contentsExpire = '';
					} else {
						contentsExpire = this.contentsExpire;
					}
					contentsGrade = this.contentsGrade;
					sort01 = this.sort01;
					sort02 = this.sort02;
					bookImage = this.bookImage;
					bookPrice = this.bookPrice;
					bookIntro = this.bookIntro;
					mobile = this.mobile;
					serviceType = this.serviceType;
					sourceType = this.sourceType;
					cp = this.cp;
					commission = this.commission;
					testTime = this.testTime;
					mid01EA = this.mid01EA;
					mid02EA = this.mid02EA;
					mid03EA = this.mid03EA;
					mid04EA = this.mid04EA;
					test01EA = this.test01EA;
					test02EA = this.test02EA;
					test03EA = this.test03EA;
					test04EA = this.test04EA;
					reportEA = this.reportEA;
					mid01Score = this.mid01Score;
					mid02Score = this.mid02Score;
					mid03Score = this.mid03Score;
					mid04Score = this.mid04Score;
					test01Score = this.test01Score;
					test02Score = this.test02Score;
					test03Score = this.test03Score;
					test04Score = this.test04Score;
					reportScore = this.reportScore;
					midRate = this.midRate;
					testRate = this.testRate;
					reportRate = this.reportRate;
					testAvailable = this.testAvailable;
					enabled = this.enabled;
					totalPassMid = this.totalPassMid;
					totalPassTest = this.totalPassTest;
					totalPassReport = this.totalPassReport;
					passScore = this.passScore;
					progressCheck = this.progressCheck;
					attachFile = this.attachFile;
					midTestChapter = this.midTestChapter;
					midTestProgress = this.midTestProgress;
					//mainView = this.mainView;
				})	
				writePrint()
			})
		}else if(writeType == 'chapterWrite' || writeType == 'testWrite' || writeType == 'reportWrite'){
			var checkApi = '';
			if(writeType == 'chapterWrite'){
				checkApi = chapterApi;
			}else if(writeType == 'testWrite'){
				checkApi = testApi;
			}else if(writeType == 'reportWrite'){
				checkApi = reportApi;
			}
			var writeAjax = $.get(checkApi,{'contentsCode':seq, 'testType':testType},function(data){
				contentsCode = data.contentsCode;
				contentsName = data.contentsName;
				writePrint()
			})
		}else{
		}
	}else{
		writePrint()
	}

		
	//게시판 생성
	function writePrint(){
		var writes ='';
		if(writeType=='contentsWrite'){
			//파일등록
			if(seq == ''){
				writes += '<h1>'+contentsName+'</h1>'
				writes += '<h2>파일로 업로드하기</h2>'
				writes += '<form class="fileUploadform" method="post" action="contentsUpload.php" enctype="multipart/form-data">';
				writes += '<ul>';
				writes += '<li>'
				writes += '<h1>등록양식</h1>'
				writes += '<button type="button" onclick="location.href=\'../attach/contents/과정등록(양식).xlsx\'">양식 내려받기</button>&nbsp;';
				writes += '<button type="button" onclick="location.href=\'../attach/contents/과정등록(샘플).xlsx\'">샘플보기</button>';
				writes += '&nbsp;<strong class="price">(샘플파일 확인 후 등록하시기 바랍니다.)</strong>'
				writes += '</li>'
				writes += '</li>'
				writes += '<li>'
				writes += '<h1>파일등록</h1>'
				writes += '<input type="file" name="uploadFile" />&nbsp;<button type="submit">파일업로드</button>'
				writes += '</li>'
				writes += '</ul>';
				writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
				writes += '</form>'

				writes += '<h2>파일로 업로드하기 (심사결과정보 반영)</h2>'
				writes += '<form class="fileUploadform" method="post" action="contentsPassUpload.php" enctype="multipart/form-data">';
				writes += '<ul>';
				writes += '<li>'
				writes += '<h1>등록양식</h1>'
				writes += '<button type="button" onclick="location.href=\'../attach/contents/심사반영(양식).xlsx\'">양식 내려받기</button>&nbsp;';
				writes += '<button type="button" onclick="location.href=\'../attach/contents/심사반영(샘플).xlsx\'">샘플보기</button>';
				writes += '&nbsp;<strong class="price">(샘플파일 확인 후 등록하시기 바랍니다.)</strong>'
				writes += '</li>'
				writes += '</li>'
				writes += '<li>'
				writes += '<h1>파일등록</h1>'
				writes += '<input type="file" name="uploadFile2" />&nbsp;<button type="submit">파일업로드</button>'
				writes += '</li>'
				writes += '</ul>';
				writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
				writes += '</form>'
			}
			writes += '<h2>직접입력하기</h2>'
			writes += '<form class="writeform" method="POST" action="/api/apiContents.php" enctype="multipart/form-data">';
			
			//seq값 선언
			writes += '<input type="hidden" name="seq" value="'+seq+'" />';
			
			//입력영역 시작
			writes += '<ul>';
			
			//등급,코드, 사이트노출
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>등급/과정코드</h1>';
			writes += '<select name="contentsGrade" class="'+contentsGrade+'">'+optWrite['contentsGrade']+'</select>'
			if(seq == ''){
				writes += '&nbsp;/&nbsp;<storng class="price">과정코드는 신규등록시 자동생성됩니다.</strong>'
			}else{
				writes += '&nbsp;/&nbsp;<strong>'+contentsCode+'</strong>'
			}
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>사이트노출</h1>';
			writes += '<select name="enabled" class="'+enabled+'">'+optWrite['enabled']+'</select>'
			writes += '</div>'
			writes += '</li>';
			
			//심사코드
			writes += '<li>'
			writes += '<h1>심사코드</h1>';
			writes += '<input type="text" name="passCode" class="name" value="'+passCode+'" />';
			writes += '&nbsp;&nbsp;<strong class="price">한기대 과정등록 코드 입력</strong>'
			writes += '</li>';
			
			//과정분류
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>과정분류</h1>';
			writes += '<select name="sort01" class="'+sort01+'" onchange="changeSortw2(this,\'\')"><option value="">대분류 선택</option>'+optWrite['lectureCode']+'</select> ';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>수강방법</h1>';
			writes += '<select name="serviceType" class="'+serviceType+'">';
			writes += '<option value="1">환급</option>';
			writes += '<option value="3">일반</option>';
			writes += '</select>';
			writes += '</div>'
			writes += '</li>';
			
			//심사코드
			writes += '<li>'
			writes += '<h1>과정명</h1>';
			writes += '<input type="text" name="contentsName" class="subject" value="'+contentsName+'" />';
			writes += '</li>';

			//과정이미지
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>과정이미지</h1>';
			if(previewImage == '' || previewImage == null){
				writes += '<input type="file" name="previewImage" />'
			}else{
				writes += '<div id="previewImage" class="attachFile"><img src="'+previewImageURL+previewImage+'" style="width:100px;"><br /><button type="button" onclick="deleteFileAct(\'previewImage\')">첨부파일삭제</button></div><input type="checkbox" name="delFile01" value="Y" />';
			}
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>진도체크방식</h1>';
			writes += '<select name="progressCheck" class="'+progressCheck+'">';
			writes += '<option value="pageCheck">페이지</option>';
			writes += '<option value="timeCheck">시간</option>';
			writes += '</select>';
			writes += '</div>'
			writes += '</li>'
			
			//차시수 시간
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>차시수</h1>';
			writes += '<input type="text" name="chapter" class="month" value="'+chapter+'" /> 차시';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>교육시간</h1>';
			writes += '<input type="text" name="contentsTime" class="month" value="'+contentsTime+'" /> 시간';
			writes += '</div>'
			writes += '</li>';
			
			//가격관련
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>교육비용</h1>';
			writes += '<input type="text" name="price" class="price" value="'+price+'" /> 원';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>우선지원</h1>';
			writes += '<input type="text" name="rPrice01" class="price" value="'+rPrice01+'" /> 원';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>대규모<br />1000인 미만</h1>';
			writes += '<input type="text" name="rPrice02" class="price" value="'+rPrice02+'" /> 원';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>대규모<br />1000인 이상</h1>';
			writes += '<input type="text" name="rPrice03" class="price" value="'+rPrice03+'" /> 원';
			writes += '</div>'
			writes += '</li>';
			
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>내용전문가</h1>';
			writes += '<input type="text" name="professor" class="name" value="'+professor+'" />'
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>학급정원</h1>';
			writes += '<input type="text" name="limited" class="price" value="'+limited+'" /> 명';
			writes += '</div>'
			writes += '</li>';

			//컨텐츠 기간
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>컨텐츠 유효기간</h1>';
			writes += '<div class="datePicker"><input type="text" name="contentsPeriod" class="cal"  value="'+contentsPeriod+'" readonly="readonly" /></div>';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>인정만료일</h1>';
			writes += '<div class="datePicker"><input type="text" name="contentsExpire" class="cal"  value="'+contentsExpire+'" readonly="readonly" /></div>';
			writes += '</div>'
			writes += '</li>';
			
			//CP사, CP수수료
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>CP사</h1>';
			writes += '<input type="text" name="cp" class="name" value="'+cp+'" />';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>CP 수수료</h1>';
			writes += '<input type="text" name="commission" class="price" value="'+commission+'" /> %';
			writes += '</div>'
			writes += '</li>';
			
			//모바일지원, 플레이방법
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>모바일지원</h1>';
			writes += '<select name="mobile" class="'+mobile+'">'+optWrite['enabled']+'</select>';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>파일 형식</h1>';
			writes += '<select name="sourceType" class="'+sourceType+'">'+optWrite['sourceType']+'</select>';
			writes += '</div>'
			writes += '</li>';

			//학습자료 등록
			writes += '<li>'
			writes += '<h1>학습자료 등록</h1>';
			if(attachFile == '' || attachFile == null){
				writes += '<input type="file" name="attachFile" />'
			}else{
				writes += '<div id="attachFile" class="attachFile"><a href="fileDownLoad.php?fileName='+encodeURI(attachFile)+'&link='+encodeURIComponent(previewImageURL+attachFile)+'" target="_blank">'+attachFile+'</a><br /><button type="button" onclick="deleteFileAct(\'attachFile\')">첨부파일삭제</button></div><input type="checkbox" name="delFile01" value="Y" />';
			}
			writes += '</li>'

			//참고도서 이미지
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>교재이미지</h1>';
			if(bookImage == '' || bookImage == null){
				writes += '<input type="file" name="bookImage" />'
			}else{
				writes += '<div id="bookImage" class="attachFile"><img src="'+bookImageURL+previewImage+'"><br /><button type="button" onclick="deleteFileAct(\'bookImage\')">첨부파일삭제</button></div><input type="checkbox" name="delFile01" value="Y" />';
			}
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>교재비</h1>';
			writes += '<input type="text" name="bookPrice" class="price" value="'+bookPrice+'" /></select>';
			writes += '</div>'
			writes += '</li>';
			
			//참고도서설명
			writes += '<li>'
			writes += '<h1>참고도서설명</h1>';
			writes += '<input type="text" name="bookIntro" class="subject" value="'+bookIntro+'" />';
			writes += '</li>'
			
			//문항관련
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:객관식 문항수</h1>';
			writes += '<input type="tel" name="mid01EA" class="year" value="'+mid01EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:객관식 배점</h1>';
			writes += '<input type="tel" name="mid01Score" class="year" value="'+mid01Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:단답형 문항수</h1>';
			writes += '<input type="tel" name="mid02EA" class="year" value="'+mid02EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:단답형 배점</h1>';
			writes += '<input type="tel" name="mid02Score" class="year" value="'+mid02Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:서술형 문항수</h1>';
			writes += '<input type="tel" name="mid03EA" class="year" value="'+mid03EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:서술형 배점</h1>';
			writes += '<input type="tel" name="mid03Score" class="year" value="'+mid03Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:진위형 문항수</h1>';
			writes += '<input type="tel" name="mid04EA" class="year" value="'+mid04EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간:진위형 배점</h1>';
			writes += '<input type="tel" name="mid04Score" class="year" value="'+mid04Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:객관식 문항수</h1>';
			writes += '<input type="tel" name="test01EA" class="year" value="'+test01EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:객관식 배점</h1>';
			writes += '<input type="tel" name="test01Score" class="year" value="'+test01Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:단답형 문항수</h1>';
			writes += '<input type="tel" name="test02EA" class="year" value="'+test02EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:단답형 배점</h1>';
			writes += '<input type="tel" name="test02Score" class="year" value="'+test02Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:서술형 문항수</h1>';
			writes += '<input type="tel" name="test03EA" class="year" value="'+test03EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:서술형 배점</h1>';
			writes += '<input type="tel" name="test03Score" class="year" value="'+test03Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:진위형 문항수</h1>';
			writes += '<input type="tel" name="test04EA" class="year" value="'+test04EA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>최종:진위형 배점</h1>';
			writes += '<input type="tel" name="test04Score" class="year" value="'+test04Score+'" /> 점';
			writes += '</div>'
			writes += '</li>';
			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>레포트 문항수</h1>';
			writes += '<input type="tel" name="reportEA" class="year" value="'+reportEA+'" /> 문항';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>레포트 배점</h1>';
			writes += '<input type="tel" name="reportScore" class="year" value="'+reportScore+'" /> 점';
			writes += '</div>'
			writes += '</li>';

			writes += '<li>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간평가 응시 차수</h1>';
			writes += '<input type="tel" name="midTestChapter" class="year" value="'+midTestChapter+'" /> 차시 이후 부터';
			writes += '</div>'
			writes += '<div class="halfDiv">'
			writes += '<h1>중간평가 응시 진도율</h1>';
			writes += '<input type="tel" name="midTestProgress" class="year" value="'+midTestProgress+'" /> % 이상 응시 가능';
			writes += '</div>'
			writes += '</li>';

			//시험제한시간
			writes += '<li>'
			writes += '<h1>시험제한시간</h1>';
			writes += '<div class="address">';
			writes += '<input type="tel" name="testTime" class="year" value="'+testTime+'" /> 분';
			writes += '</div>';
			writes += '</li>'
			
			//수료기준
			writes += '<li>'
			writes += '<h1>수료기준</h1>';
			writes += '<div class="address">';
			writes += '<strong class="price">진도율</strong> : <input type="tel" name="passProgress"  class="year" value="'+passProgress+'" /> %이상&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;'
			writes += '<strong class="price">중간평가</strong> : 총점 <input type="tel" name="totalPassMid"  class="year" value="'+totalPassMid+'" />&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;'
			writes += '<strong class="price">최종평가</strong> : 총점 <input type="tel" name="totalPassTest"  class="year" value="'+totalPassTest+'" /> 점 중 <input type="tel" name="passTest"  class="year" value="'+passTest+'" /> 점 이상&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;'
			writes += '<strong class="price">레포트</strong> : 총점 <input type="tel" name="totalPassReport"  class="year" value="'+totalPassReport+'" /> 점 중 <input type="tel" name="passReport"  class="year" value="'+passReport+'" /> 점 이상&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;'
			writes += '<strong class="price">총점</strong> : <input type="tel" name="passScore"  class="year" value="'+passScore+'" /> 점 이상 수료';
			writes += '</div>';
			writes += '</li>'

			//반영비율
			writes += '<li>'
			writes += '<h1>반영비율</h1>';
			writes += '<div class="address">';
			writes += '<strong class="price">중간평가</strong> : <input type="tel" name="midRate"  class="year" value="'+midRate+'" />%&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;';
			writes += '<strong class="price">최종평가</strong> : <input type="tel" name="testRate"  class="year" value="'+testRate+'" />%&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;';
			writes += '<strong class="price">레포트</strong> : <input type="tel" name="reportRate"  class="year" value="'+reportRate+'" />%';
			writes += '</div>';
			writes += '</li>'

			//과정소개
			writes += '<li>'
			writes += '<h1>과정소개</h1>';
			writes += '<textarea name="intro">'+intro+'</textarea>';
			writes += '</li>'
			
			//교육대상
			writes += '<li>'
			writes += '<h1>교육대상</h1>';
			writes += '<textarea name="target">'+target+'</textarea>';
			writes += '</li>'
			
			//교육대상
			writes += '<li>'
			writes += '<h1>교육목표</h1>';
			writes += '<textarea name="goal">'+goal+'</textarea>';
			writes += '</li>'
			
			writes += '</ul>';
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="multipartSendData(\'writeform\')">';
			if(seq==''){
				writes += '등록하기';
			}else{
				writes += '수정하기';
			}
			writes += '</button>';
			writes += '<button type="button" onClick="deleteData(useApi,'+this.seq+')">삭제하기</button>';
			writes += '<button type="button" onClick="listAct(page)">목록보기</button>';
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			writes += '</form>';
		}else if(writeType == 'chapterWrite' || writeType == 'testWrite' || writeType == 'reportWrite'){
			//과정 매핑등록
			/*
			writes += '<h1>'+contentsName+'&nbsp;<span>[&nbsp;'+contentsCode+'&nbsp;]</span></h1>';
			writes += '<h2>타 과정에서 가져오기</h2>'
			writes += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
			writes += '<select name="searchType">';	
			writes += '<option value="contentsName">과정명</option>';
			writes += '<option value="contentsCode">과정코드</option>';
			writes += '<option value="phone">CP사</option>';
			writes += '</select>&nbsp;';
			writes += '<input type="text" class="subject" name="searchValue" />&nbsp;';
			writes += '</form>'
			writes += '&nbsp</div>';
			*/
			
			//등록차시 불러오기
			writes += '<h1>'+contentsName+'</h1>'
			if(writeType== 'testWrite' || writeType== 'reportWrite'){
				if(writeType== 'testWrite'){
					if(testType=='mid'){
						writes += '<h2>중간평가 문항보기</h2>'
					} else {
						writes += '<h2>최종평가 문항보기</h2>'
					}
					writes += '<div class="BBSList">'
					writes += '<table class="sortArea" style="margin-bottom:5px;"><thead>';
					writes += '<th style="width:20%">진위형 문항수 | 출제수</th>';
					writes += '<th style="width:20%">객관식 문항수 | 출제수</th>';
					writes += '<th style="width:20%">단답형 문항수 | 출제수</th>';
					writes += '<th style="width:20%">서술형 문항수 | 출제수</th>';
					writes += '<th style="width:20%">문제연결</th>';
				}else if(writeType== 'reportWrite'){
					writes += '<h2>과제 문항보기</h2>'
					writes += '<div class="BBSList">'
					writes += '<table class="sortArea" style="margin-bottom:5px;"><thead>';
					writes += '<th style="width:50%;">문항수</th>';
					writes += '<th style="width:50%;">복사/삭제</th>';
				}
				writes += '</thead><tbody>';
				writes += '</tbody></table>';
			}else if(writeType== 'chapterWrite'){
				writes += '<h2>차시 정보</h2>'
				writes += '<div class="BBSList">'
			}
			writes += '<table class="listArea"><thead>';
			if(writeType== 'chapterWrite'){
				writes += '<th style="width:50px">차시</th>';
				writes += '<th>차시명</th>';
				writes += '<th style="width:150px;">미리보기</th>';
				if(loginUserLevel <= 4) {
					writes += '<th style="width:180px;">복사/삭제</th>';
				}
			}else if(writeType== 'testWrite'){
				writes += '<th style="width:60px">문제번호</th>';
				writes += '<th style="width:80px;">문제유형</th>';
				writes += '<th>문제</th>';
				writes += '<th style="width:80px;">등록체크</th>';
				writes += '<th style="width:80px;">배점</th>';
				writes += '<th style="width:80px;">출처차시</th>';
				writes += '<th style="width:180px;">복사/삭제</th>';
			}else if(writeType== 'reportWrite'){
				writes += '<th style="width:60px">문제번호</th>';
				writes += '<th>문제</th>';
				writes += '<th style="width:80px;">배점</th>';
				writes += '<th style="width:60px;">출처차시</th>';
				writes += '<th style="width:180px;">복사/삭제</th>';
			}
			writes += '</thead><tbody>';
			writes += '</tbody></table>';
			writes += '</div>';

			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="listAct()">목록보기</button>';
			writes += '</div>';

			if(loginUserLevel <= 4) {
					//파일등록
					writes += '<h2>파일로 업로드하기</h2>'
					if(writeType == 'chapterWrite'){
						writes += '<form class="fileUploadform" method="post" action="chapterUpload.php" enctype="multipart/form-data">';
					}else if(writeType == 'testWrite'){
						writes += '<form class="fileUploadform" method="post" action="testUpload.php" enctype="multipart/form-data">';
					}else if(writeType == 'reportWrite'){
						writes += '<form class="fileUploadform" method="post" action="reportUpload.php" enctype="multipart/form-data">';
					}
					writes += '<ul>';
					writes += '<li>'
					writes += '<h1>등록양식</h1>'
					if(writeType == 'chapterWrite'){
						//차시등록 xls예시
					writes += '<button type="button" onclick="location.href=\'../attach/contents/차시등록(양식).xlsx\'">양식 내려받기</button>&nbsp;';
					writes += '<button type="button" onclick="location.href=\'../attach/contents/차시등록(샘플).xlsx\'">샘플보기</button>';
					writes += '&nbsp;<strong class="price">(샘플파일 확인 후 등록하시기 바랍니다.)</strong>'
					}else if(writeType == 'testWrite'){
						//문제등록 xls예시
					writes += '<button type="button" onclick="location.href=\'../attach/contents/평가등록(양식).xlsx\'">양식 내려받기</button>&nbsp;';
					writes += '<button type="button" onclick="location.href=\'../attach/contents/평가등록(샘플).xlsx\'">샘플보기</button>';
					writes += '&nbsp;<strong class="price">(샘플파일 확인 후 등록하시기 바랍니다.)</strong>'
					}else if(writeType == 'reportWrite'){
						//과제등록 xls예시
					writes += '<button type="button" onclick="location.href=\'../attach/contents/과제등록(양식).xlsx\'">양식 내려받기</button>&nbsp;';
					writes += '<button type="button" onclick="location.href=\'../attach/contents/과제등록(샘플).xlsx\'">샘플보기</button>';
					writes += '&nbsp;<strong class="price">(샘플파일 확인 후 등록하시기 바랍니다.)</strong>'
					}			
					writes += '</li>'
					writes += '<li>'
					writes += '<h1>파일등록</h1>'
					writes += '<input type="file" name="uploadFile" />&nbsp;<button type="submit">파일업로드</button>'
					writes += '</li>'
					writes += '</ul>';
					writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
					writes += '</form>'

				//개별등록,수정
				writes += '<h2><a name="bottom">개별등록/수정</a></h2>'
				if(writeType == 'chapterWrite'){
					writes += '<form class="writeform" method="post" action="'+chapterApi+'" enctype="multipart/form-data">';
				}else if(writeType == 'testWrite'){
					writes += '<form class="writeform" method="post" action="'+testApi+'" enctype="multipart/form-data">';
				}else if(writeType == 'reportWrite'){
					writes += '<form class="writeform" method="post" action="'+reportApi+'" enctype="multipart/form-data">';
				}
				writes += '<input type="hidden" name="seq" />'
				writes += '<input type="hidden" name="contentsCode" value="'+contentsCode+'" />'
				writes += '<input type="hidden" name="testType" value="'+testType+'">';
				writes += '<ul>';
				
				if(writeType == 'chapterWrite'){
					//차시번호
					writes += '<li>';
					writes += '<h1>차시번호</h1>';
					writes += '<input type="tel" class="month" name="chapter" /> 차시';
					writes += '</li>';			
					//차시명
					writes += '<li>';
					writes += '<h1>차시명</h1>';
					writes += '<input type="text" class="subject" name="chapterName" />';
					writes += '</li>';
					//차시목표
					writes += '<li>';
					writes += '<h1>차시목표</h1>';
					writes += '<textarea name="goal"></textarea>';
					writes += '</li>';
					//훈련내용
					writes += '<li>';
					writes += '<h1>훈련내용</h1>';
					writes += '<textarea name="content"></textarea>';
					writes += '</li>';
					//학습활동
					writes += '<li>';
					writes += '<h1>학습활동</h1>';
					writes += '<textarea name="activity"></textarea>';
					writes += '</li>';
					//내용전문가, 차시프레임수
					writes += '<li>';
					writes += '<div class="halfDiv">';
					writes += '<h1>차시 페이지 수</h1>';
					writes += '<input type="tel" class="year" name="chapterSize" />';
					writes += '</div>';
					writes += '<div class="halfDiv">';
					writes += '<h1>모바일 페이지 수</h1>';
					writes += '<input type="tel" class="year" name="chapterMobileSize" />';
					writes += '</div>';
					writes += '</li>';
					//모바일 데이터 용량
					writes += '<li>';
					writes += '<h1>모바일 데이터 용량</h1>';
					writes += '약 <input type="tel" class="year" name="mobileDataSize" /> MB (모바일 수강 입장 시 데이터 용량을 알려줍니다.)';
					writes += '</li>';
					//플레이어 경로
					writes += '<li>';
					writes += '<h1>플레이어 경로</h1>';
					writes += '<input type="text" class="subject" name="player" />';
					writes += '</li>';
					//서버경로
					writes += '<li>';
					writes += '<h1>서버경로</h1>';
					writes += '<input type="text" class="subject" name="chapterPath" />';
					writes += '</li>';
					//모바일경로
					writes += '<li>';
					writes += '<h1>모바일경로</h1>';
					writes += '<input type="text" class="subject" name="chapterMobilePath" />';
					writes += '</li>';

				}else if(writeType == 'testWrite'){				
					//문제번호,문제분류
					writes += '<li>';
					writes += '<div class="halfDiv">'
					writes += '<h1>문제번호</h1>';
					writes += '<input type="tel" name="examNum" class="year">';
					writes += '</div>'
					writes += '<div class="halfDiv">'
					writes += '<h1>문제분류</h1>';
					writes += '<select name="examType" onchange="changeInput(this)">'+optWrite['examType']+'</select>';
					writes += '</div>'
					writes += '</li>';				
					//배점,출처차시
					writes += '<li>';
					writes += '<div class="halfDiv">'
					writes += '<h1>배점</h1>';
					writes += '<input type="tel" name="score" class="year">';
					writes += '</div>'
					writes += '<div class="halfDiv">'
					writes += '<h1>출처차시</h1>';
					writes += '<input type="tel" name="sourceChapter" class="year">';
					writes += '</div>'
					writes += '</li>';
					//지문내용
					writes += '<li><h1>지문내용<br /><br />';
					writes += '<button type="button" onClick="editorOpen(\''+testApi+'\',\'exam\',\''+writeType+'\')">내용편집</button>';
					writes += '</h1>'
					writes += '<div class="textInputs">';
					writes += '<div id="exam" class="examView" onClick="editorOpen(\''+testApi+'\',\'exam\',\''+writeType+'\')"></div>';
					writes += '<textarea name="exam" style="display:none;"></textarea>'
					writes += '</div>'
					writes += '</li>';
					//보기
					for(i=1; i<6 ; i++){
						writes += '<li class="answerA"><h1>보기 '+i+'.<br />';
						writes += '[ 정답 : <input type="checkbox" name="answer" id="answer'+i+'" value="'+i+'" /><label for="answer'+i+'"></label>]<br />';
						writes += '<button type="button" onClick="editorOpen(\''+testApi+'\',\'example0'+i+'\')">내용편집</button>';
						writes += '</h1>'
						writes += '<div class="textInputs">';
						writes += '<div id="example0'+i+'" class="examView" onClick="editorOpen(\''+testApi+'\',\'example0'+i+'\')"></div>';
						writes += '<textarea name="example0'+i+'" style="display:none;"></textarea>'
						writes += '</div>';
						writes += '</li>';
					}
					//정답입력
					writes += '<li class="answerB" style="display:none"><h1>정답입력</h1>';
					writes += '<textarea name="answerText"></textarea>';
					writes += '</li>';
					//해설내용
					writes += '<li><h1>해설내용(채점기준)<br /><br />';
					writes += '<button type="button" onClick="editorOpen(\''+testApi+'\',\'exam\')">내용편집</button>';
					writes += '</h1>';
					writes += '<div class="textInputs">';
					writes += '<div id="commentary" class="examView" onClick="editorOpen(\''+testApi+'\',\'commentary\')"></div>';
					writes += '<textarea name="commentary" style="display:none;"></textarea>'
					writes += '</div>';
					writes += '</li>';
				}else if(writeType == 'reportWrite'){
					//문제번호, 출처차시
					writes += '<li>';
					writes += '<div class="halfDiv">';
					writes += '<h1>문제번호</h1>';
					writes += '<input type="text" class="year" name="examNum" /> 번';
					writes += '</div>';
					writes += '<div class="halfDiv">';
					writes += '<h1>출처차시</h1>';
					writes += '<input type="tel" class="year" name="sourceChapter" /> 차시';
					writes += '</div>';
					writes += '</li>';			
					//과제등록
					writes += '<li>';
					writes += '<h1>과제등록';
					writes += '<button type="button" onClick="editorOpen(\''+reportApi+'\',\'example\',\'report\')">내용편집</button>';
					writes += '</h1>';
					writes += '<div class="textInputs">';
					writes += '<input type="file" name="examAttach" /><br />';
					writes += '<div id="exam" class="examView" onClick="editorOpen(\''+reportApi+'\',\'exam\',\'report\')"></div>';
					writes += '<textarea name="exam" style="display:none"></textarea>';
					writes += '</li>';
					//답안등록
					writes += '<li>';
					writes += '<h1>답안등록';
					writes += '<button type="button" onClick="editorOpen(\''+reportApi+'\',\'examView\',\'report\')">내용편집</button>';
					writes += '</h1>';
					writes += '<div class="textInputs">';
					writes += '<input type="file" name="exampleAttach" /><br />';
					writes += '<div id="example" class="examView" onClick="editorOpen(\''+reportApi+'\',\'example\',\'report\')"></div>';
					writes += '<textarea name="example" style="display:none"></textarea>';
					writes += '</li>';
					//답안등록
					writes += '<li>';
					writes += '<h1>채점기준';
					writes += '<button type="button" onClick="editorOpen(\''+reportApi+'\',\'rubric\',\'report\')">내용편집</button>';
					writes += '</h1>';
					writes += '<div class="textInputs">';
					writes += '<input type="file" name="rubricAttach" /><br />';
					writes += '<div id="rubric" class="examView" onClick="editorOpen(\''+reportApi+'\',\'rubric\',\'report\')"></div>';
					writes += '<textarea name="rubric" style="display:none"></textarea>';
					writes += '</li>';
					//답안등록
					writes += '<li>';
					writes += '<h1>점수</h1>';
					writes += '<input type="tel" class="year" name="score">';
					writes += '</li>';
				}
				writes += '</ul>';

				writes += '<div class="btnArea">';
				writes += '<button type="button" onClick="resetInput()">초기화</button>';
				writes += '<button type="button" onClick="sendContens(\''+contentsCode+'\',\''+writeType+'\', \''+testType+'\')">등록하기</button>';
				writes += '<button type="button" onClick="listAct()">목록보기</button>';
				writes += '</div>';
				writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
				writes += '</form>';
			}

		}
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		
		matchingList(contentsCode, writeType, testType);
		findOpt();//selct 선택자 찾기
		emailSelect();//이메일 select 호출 사용시 같이 호출	
		pickerAct();//데이트피커 사용
		fileformAct();//파일 첨부 사용시	
		var	mustInput = '&nbsp;&nbsp;<strong class="price">(*)</strong>';
		$('.mustCheck > h1').append(mustInput)//필수요소 사용
		changeSortw2(sort01,sort02,1);
	}
}

//카테고리 두번째 셀렉트박스 생성
function changeSortw2(obj,sort2value,v){
	//alert(obj+'//'+sort2value);
	obj = (v==1)?obj:obj.options[obj.selectedIndex].value;
	sort2value = sort2value?sort2value:'';

	$('select[name="sort02"]').remove();
	if(obj != ''){
		$.get(categoryApi,{'value01':obj,'allType':'ALL'},function(data){
			var selectWrite = '';
			selectWrite += ' <select name="sort02" class="'+sort2value+'" >';
			selectWrite += '<option value="">소분류 선택</option>';
			$.each(data.category,function(){
				if(this.value01 == sort2value){ // 2차 카테고리 선택 값 찾기
					selectWrite += '<option value="'+this.value01+'" selected>';
				}else{
					selectWrite += '<option value="'+this.value01+'">';
				}
				selectWrite += this.value02;
				selectWrite += ' </option>';
			})
			selectWrite += '</select>'
			//alert(selectWrite);
			$('select[name="sort01"]').after(selectWrite);
		
		})
	}
}

function viewAct(seq){
	writeAct(seq)
}

function sampleID(contentsCode){
	if(confirm('콘텐츠 심사용 아이디를 등록하시겠습니까?')) {
		$.ajax({
			url:sampleIDApi,
			type:'POST',
			data:'contentsCode='+contentsCode,
			success:function(){
				alert('등록되었습니다.');
				ajaxAct();
			}
		})
	}
}

function changeInput(vals){
	vals = vals.value;
	if(vals == 'A'){
		$('.answerB').css('display','none')
		$('.answerA').css('display','list-item')
	}else if(vals != 'A'){
		$('.answerA').css('display','none')
		$('.answerB').css('display','list-item')
	}
}

//챕터 리셋 등록
function writeChapter(){
	var sendData = $('.writeform').serialize();
	var contentsCodes = $('.writeform input[name="contentsCode"]').val();
	$.ajax({
		url:chapterApi,
		type:'POST',
		data:sendData,
		success:function(){
			if($('.writeform input[name="seq"]').val() == ''){
				alert('등록되었습니다.')
			}else{
				alert('수정되었습니다.')
			}
			chapterList(contentsCodes);
		}
	})
}
function resetInput(){
	if($('.writeform input').prop('name') != 'contentsCode'){
		$('.writeform input[type="text"], .writeform input[type="tel"], .writeform input[name="seq"], .writeform input[type="file"]').val('');
		$('label.AttachFiles span').html('파일찾기')
		$('.writeform textarea, .writeform div.examView').html('');
		$('input[type="checkbox"]').prop('checked',false);
		$('div.attachFile').each(function(){
            var deleteDiv = $(this);
			var inputName = $(this).attr('id');
			deleteDiv.parent('div').children('input[type="checkbox"]').remove();
			var preLabel = '';
			preLabel += '<label class="AttachFiles"><span>파일찾기</span>';
			preLabel += '<input type="file" name="'+inputName+'" style="display:none" onchange="fileAddAct(this,\''+inputName+'\')" />';
			preLabel += '</label>';
			deleteDiv.after(preLabel);
			deleteDiv.remove()
        });
	}
	$('.writeform button[type="submit"]').html('등록하기')
}

//챕터 불러오기
function matchingList(codes, types, testType){
	testType = testType ? testType : '';
	var matchingApi = '';
	if(writeType == 'chapterWrite'){
		matchingApi = chapterApi;
	}else if(writeType == 'testWrite'){
		matchingApi = testApi;
	}else if(writeType == 'reportWrite'){
		matchingApi = reportApi;
	}
	var writeAjax = $.get(matchingApi,{'contentsCode':codes, 'testType':testType},function(data){
		var contentsCodes = data.contentsCode;
		var sortCount = data.totalCount;
		var chapterLists = '';
		var sortLists = '';
		var aTypeEA = '';
		var bTypeEA = '';
		var cTypeEA = '';
		var dTypeEA = '';
		var test01EA = '';
		var test02EA = '';
		var test03EA = '';
		var test04EA = '';
		if(types == 'testWrite'){
			aTypeEA = data.aTypeEA;
			bTypeEA = data.bTypeEA;
			cTypeEA = data.cTypeEA;
			dTypeEA = data.dTypeEA;
				if(testType == 'mid'){
					test01EA = data.mid01EA;
					test02EA = data.mid02EA;
					test03EA = data.mid03EA;
					test04EA = data.mid04EA;
				} else {
					test01EA = data.test01EA;
					test02EA = data.test02EA;
					test03EA = data.test03EA;
					test04EA = data.test04EA;
				}
		}
		if(sortCount != 0){
			if(writeType=='chapterWrite'){
				$.each(data.chapter,function(){			
					chapterLists += '<tr>';
					chapterLists += '<td>';
					if(this.chapter >= 100){					
						chapterLists += '-';
					}else{
						chapterLists += this.chapter;
					}
					chapterLists += '</td>';
					chapterLists += '<td class="left" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\')" style="cursor:pointer;">'+this.chapterName+'</td>'
					chapterLists += '<td>';
					
					if(data.sourceType=='book') {
						var Sid = data.contentsCode + '000000999999' + loginUserID;
						var Chasi = this.chapter < 10 ? '0' + this.chapter : this.chapter;
						var screenWidth = screen.width;
						var screenHeight = screen.height;
						var popupAddress = '/viewer/index.html?Sid='+Sid+'&Code='+contentsCodes+'&Chasi='+Chasi+'&Page=99&MovePage=1&PreView=Y';
							chapterLists += '<button type="button" onClick="window.open(\''+popupAddress+'\',\'학습창\',\'top=0,left=0,height=\'+(screen.height-100)+\',width=\'+screen.width+\',location=yes,menubar=no,status=no,titlebar=yes,toolbar=no,scrollbar=no,resizeable=no,fullscreen=yes\',\'Study\')">PC</button>&nbsp;/&nbsp;';
						if(data.mobile == 'Y'){
							chapterLists += '<button type="button" onClick="window.open(\''+popupAddress+'\',\'학습창\',\'top=0,left=0,width=600,height=880,location=no,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no\',\'Study\')">모바일</button>';
						}else{
							chapterLists += '-';
						}
					} else {
						var PCLink = this.player+'/player/popupConfirm.php?contentsCode='+contentsCodes+'&chapter='+this.chapter+'&sourceType='+data.sourceType;
						var mobileLink = 'mobilePreview.html?contentsCode='+contentsCodes+'&chapter='+this.chapter;

						chapterLists += '<button type="button" onClick="window.open(\''+PCLink+'\',\'학습창\',\'top=0,left=0,location=yes,menubar=no,status=no,titlebar=yes,toolbar=no,scrollbar=no,resizeable=no\',\'Study\')">PC</button>&nbsp;/&nbsp;';															
						if(data.mobile == 'Y'){
							chapterLists += '<button type="button" onClick="window.open(\''+mobileLink+'\',\'학습창\',\'top=0,left=0,location=no,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no\',\'Study\')">모바일</button>';
						}else{
							chapterLists += '-';
						}
					}

					if(loginUserLevel <= 4) {
						chapterLists += '<td>';
						chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\')">수정</button>&nbsp;/&nbsp;';
						chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'copy\')">복사</button>&nbsp;/&nbsp;';
						chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'delete\')">삭제</button>';
						chapterLists += '</td>'
					}
					chapterLists += '</tr>'	
				})
			}else if(writeType=='testWrite'){
				var examCheck = '';
				sortLists += '<tr>'
				sortLists += '<td>'+dTypeEA+' | '+test04EA+'</td>';
				sortLists += '<td>'+aTypeEA+' | '+test01EA+'</td>';
				sortLists += '<td>'+bTypeEA+' | '+test02EA+'</td>';
				sortLists += '<td>'+cTypeEA+' | '+test03EA+'</td>';
				sortLists += '<td>';
				sortLists += '<button type="button" onClick="allDelete(\''+codes+'\',\'testWrite\',\''+testType+'\')">전체삭제</button>&nbsp;/&nbsp;';
				sortLists += '<button type="button" onClick="modalAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+')">문제매핑</button>&nbsp;/&nbsp;';
				sortLists += '<button type="button" onClick="previewAct(\''+codes+'\',\''+types+'\',\''+testType+'\')">전체보기</button>';
				sortLists += '</td>';
				sortLists += '</tr>'
				$.each(data.test,function(){		
					chapterLists += '<tr class="line'+this.seq+'">';
					chapterLists += '<td>'+this.examNum+'</td>';
					chapterLists += '<td>';
					if(this.examType == 'A'){
						chapterLists += '객관식';
						if(this.answer == '' || this.answer == null){
							examCheck = '정답누락';
						}
					}else if(this.examType == 'B'){
						chapterLists += '단답형'
						if(this.answerText == '' || this.answerText == null){
							examCheck = '정답누락';
						}
					}else if(this.examType == 'C'){
						chapterLists += '서술형'
						if(this.answerText == '' || this.answerText == null){
							examCheck = '정답누락';
						}
					}else if(this.examType == 'D'){
						chapterLists += '진위형'
						if(this.answer == '' || this.answer == null){
							examCheck = '정답누락';
						}
					}
					if(this.commentary == ''){
						examCheck += ' 해설누락';
					}
					if(examCheck == ''){
						examCheck = '정상';
					}
					chapterLists += '</td>';
					chapterLists += '<td class="left" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\',\''+testType+'\')" style="cursor:pointer;">'+this.exam+'</td>';
					chapterLists += '<td>'+examCheck+'</td>';
					chapterLists += '<td>'+this.score+'</td>';
					chapterLists += '<td>'+this.sourceChapter+'</td>';
					chapterLists += '<td>';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\',\''+testType+'\')">수정</button>&nbsp;/&nbsp;';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'copy\',\''+testType+'\')">복사</button>&nbsp;/&nbsp;';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'delete\',\''+testType+'\')">삭제</button>';
					chapterLists += '</td>';
					chapterLists += '</tr>';
					examCheck = '';
				})
			}else if(writeType=='reportWrite'){
				sortLists += '<tr>'
				sortLists += '<td>'+sortCount+'</td>';
				sortLists += '<td>';
				sortLists += '<button type="button" onClick="allDelete(\''+codes+'\',\'reportWrite\',\'\')">전체삭제</button>&nbsp;/&nbsp;';
				sortLists += '<button type="button" onClick="modalAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+')">문제매핑</button>&nbsp;/&nbsp;';
				sortLists += '<button type="button" onClick="previewAct(\''+codes+'\',\''+types+'\',\''+testType+'\')">전체보기</button>';
				sortLists += '</td>';
				sortLists += '</tr>'
				$.each(data.report,function(){		
					chapterLists += '<tr class="line'+this.seq+'">'
					chapterLists += '<td>'+this.examNum+'</td>'
					chapterLists += '<td class="left" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\')" style="cursor:pointer;">'+this.exam.replace(/\n/g,'<br />')+'</td>'
					chapterLists += '<td>'+this.score+'</td>'
					chapterLists += '<td>'+this.sourceChapter+'</td>'
					chapterLists += '<td>';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'modify\')">수정</button>&nbsp;/&nbsp;';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'copy\')">복사</button>&nbsp;/&nbsp;';
					chapterLists += '<button type="button" onClick="contentsAct(\''+writeType+'\',\''+contentsCodes+'\','+this.seq+',\'delete\')">삭제</button>';
					chapterLists += '</td>'
					chapterLists += '</tr>'	
				})
			}		
		}else{
			chapterLists += '<tr><td colspan="20">등록된 정보가 없습니다.</td></tr>';
		}
		if(writeType=='testWrite' || writeType=='reportWrite'){
			$('.BBSList table.sortArea tbody').html(sortLists)
		}
		$('.BBSList table.listArea tbody').html(chapterLists)
	})
}
//챕터개별 등록,수정,복사
function contentsAct(contypes,codes,seqNum,action,testType){
	testType = testType ? testType : '';
	var matchingApi = '';
	if(contypes == 'chapterWrite'){
		matchingApi = chapterApi;
	}else if(contypes == 'testWrite'){
		matchingApi = testApi;
	}else if(contypes == 'reportWrite'){
		matchingApi = reportApi;
	}
	$.get(matchingApi,{'seq':seqNum, 'contentsCode':codes, 'testType':testType},function(data){
		if(contypes == 'chapterWrite'){
			$.each(data.chapter, function(){
				if(action == 'copy'){
					var copyDate = ''
					copyDate += 'seq=&contentsCode='+codes+'&';
					copyDate += 'chapter='+this.chapter+'&';
					copyDate += 'chapterName'+this.chapterName.replace(/&/g,'%26')+'&';
					copyDate += 'goal='+this.goal.replace(/&/g,'%26')+'&';
					copyDate += 'content='+this.content.replace(/&/g,'%26')+'&';
					copyDate += 'activity='+this.activity.replace(/&/g,'%26')+'&';
					copyDate += 'professor='+this.professor.replace(/&/g,'%26')+'&';
					copyDate += 'player='+this.player.replace(/&/g,'%26')+'&';
					copyDate += 'chapterPath='+this.chapterPath.replace(/&/g,'%26')+'&';
					copyDate += 'chapterSize='+this.chapterSize.replace(/&/g,'%26')+'&';
					copyDate += 'chapterMobilePath='+this.chapterMobilePath.replace(/&/g,'%26')+'&';
					copyDate += 'chapterMobileSize='+this.chapterMobileSize.replace(/&/g,'%26')+'&';
					copyDate += 'mobileDataSize='+this.mobileDataSize.replace(/&/g,'%26')+'&';
					copyAct();
				}else if(action == 'modify'){
					resetInput();
					$('.writeform input[name="seq"]').val(this.seq);
					$('.writeform input[name="chapter"]').val(this.chapter);
					$('.writeform input[name="chapterName"]').val(this.chapterName);
					$('.writeform textarea[name="goal"]').html(this.goal);
					$('.writeform textarea[name="content"]').html(this.content);
					$('.writeform textarea[name="activity"]').html(this.activity);
					$('.writeform input[name="professor"]').val(this.professor);
					$('.writeform input[name="player"]').val(this.player);
					$('.writeform input[name="chapterPath"]').val(this.chapterPath);
					$('.writeform input[name="chapterSize"]').val(this.chapterSize);
					$('.writeform input[name="chapterMobilePath"]').val(this.chapterMobilePath);
					$('.writeform input[name="chapterMobileSize"]').val(this.chapterMobileSize);
					$('.writeform input[name="mobileDataSize"]').val(this.mobileDataSize);
					$('.writeform button[type="submit"]').html('수정하기');
					top.location.href='#bottom';
				}
			})
		}else if(contypes == 'testWrite'){
			$.each(data.test, function(){
				if(action == 'copy'){
					var copyDate = ''
					copyDate += 'seq=&contentsCode='+codes+'&';
					copyDate += 'examNum='+this.examNum+'&';
					copyDate += 'examType='+this.examType+'&';
					copyDate += 'chapterName'+this.chapterName.replace(/&/g,'%26')+'&';
					copyDate += 'exam='+this.exam.replace(/&/g,'%26')+'&';
					copyDate += 'example01='+this.example01.replace(/&/g,'%26')+'&';
					copyDate += 'example02='+this.example02.replace(/&/g,'%26')+'&';
					copyDate += 'example03='+this.example03.replace(/&/g,'%26')+'&';
					copyDate += 'example04='+this.example04.replace(/&/g,'%26')+'&';
					copyDate += 'answer='+this.answer+'&';
					copyDate += 'commentary='+this.commentary.replace(/&/g,'%26')+'&';
					copyDate += 'score='+this.score+'&';
					copyDate += 'sourceChapter='+this.sourceChapter;
					copyAct();
				}else if(action == 'modify'){
					resetInput();
					qSeq = this.seq;
					$('.writeform input[name="seq"]').val(this.seq);
					$('.writeform input[name="examNum"]').val(this.examNum);
					$('.writeform select[name="examType"]').val(this.examType);
					$('#exam').html(this.exam.replace(/\n/g,'<br />'));
					$('textarea[name="exam"]').val(this.exam.replace(/\n/g,'<br />'));
					$('#commentary').html(this.commentary.replace(/\n/g,'<br />'));
					$('textarea[name="commentary"]').val(this.commentary.replace(/\n/g,'<br />'));
					$('.writeform input[name="seq"]').val(this.seq);
					$('.writeform input[name="examNum"]').val(this.examNum);
					$('.writeform input[name="score"]').val(this.score);
					$('.writeform input[name="sourceChapter"]').val(this.sourceChapter);
					if(this.examType == "A"){
						$('input[type="checkbox"]').prop('checked',false);
						$('textarea[name="answer"]').html('');
						$('.answerA').css('display','list-item');
						$('.answerB').css('display','none');
						$('#example01').html(this.example01.replace(/\n/g,'<br />'));
						$('#example02').html(this.example02.replace(/\n/g,'<br />'));
						$('#example03').html(this.example03.replace(/\n/g,'<br />'));
						$('#example04').html(this.example04.replace(/\n/g,'<br />'));
						$('textarea[name="example01"]').val(this.example01.replace(/\n/g,'<br />'));
						$('textarea[name="example02"]').val(this.example02.replace(/\n/g,'<br />'));
						$('textarea[name="example03"]').val(this.example03.replace(/\n/g,'<br />'));
						$('textarea[name="example04"]').val(this.example04.replace(/\n/g,'<br />'));
						if(this.example05 != null){
							$('textarea[name="example05"]').val(this.example05.replace(/\n/g,'<br />'));
							$('#example05').html(this.example05.replace(/\n/g,'<br />'));
						}
						$('#answer'+this.answer).prop('checked',true);

					} else if(this.examType == "D"){
						$('input[type="checkbox"]').prop('checked',false);
						$('textarea[name="answer"]').html('');
						$('.answerA').css('display','list-item');
						$('.answerB').css('display','none');
						$('#example01').html(this.example01.replace(/\n/g,'<br />'));
						$('#example02').html(this.example02.replace(/\n/g,'<br />'));
						$('textarea[name="example01"]').val(this.example01.replace(/\n/g,'<br />'));
						$('textarea[name="example02"]').val(this.example02.replace(/\n/g,'<br />'));
						$('#answer'+this.answer).prop('checked',true);

					}else{
						$('.answerA').css('display','none');
						$('.answerB').css('display','list-item');
						$('input[type="checkbox"]').prop('checked',false);
						$('textarea[name="answerText"]').val(this.answerText);
					}
				}
				top.location.href='#bottom';
			})
		}else if(contypes == 'reportWrite'){
			$.each(data.report, function(){
				if(action == 'copy'){
					var copyDate = ''
					copyDate += 'seq=&contentsCode='+codes+'&';
					copyDate += 'examNum='+this.examNum+'&';
					copyDate += 'sourceChapter='+this.sourceChapter+'&';
					copyDate += 'chapterName'+this.chapterName.replace(/&/g,'%26')+'&';
					copyDate += 'exam='+this.exam.replace(/&/g,'%26')+'&';
					copyDate += 'examAttach='+this.examAttach.replace(/&/g,'%26')+'&';
					copyDate += 'example='+this.example.replace(/&/g,'%26')+'&';
					copyDate += 'exampleAttach='+this.exampleAttach.replace(/&/g,'%26')+'&';
					copyDate += 'rubric='+this.rubric.replace(/&/g,'%26')+'&';
					copyDate += 'rubricAttach='+this.rubricAttach.replace(/&/g,'%26')+'&';
					copyDate += 'score'+this.score.replace(/&/g,'%26');
					copyAct();
				}else if(action == 'modify'){
					resetInput();
					$('.writeform input[name="seq"]').val(this.seq);
					$('.writeform input[name="examNum"]').val(this.examNum);
					$('.writeform input[name="sourceChapter"]').val(this.sourceChapter);
					$('#exam').html(this.exam.replace(/\n/g,'<br />'));
					$('#example').html(this.example.replace(/\n/g,'<br />'));
					$('#rubric').html(this.rubric.replace(/\n/g,'<br />'));
					$('.writeform textarea[name="exam"]').val(this.exam.replace(/\n/g,'<br />'));
					$('.writeform textarea[name="example"]').val(this.example.replace(/\n/g,'<br />'));
					$('.writeform textarea[name="rubric"]').val(this.rubric.replace(/\n/g,'<br />'));
					$('.writeform input[name="score"]').val(this.score);
					var filePoint = 0;
					if(this.examAttach != null){
						var files = '<div id="examAttach" class="attachFile"><a href="fileDownLoad.php?fileName='+this.examAttach+'&link='+this.examAttachLink+'" target="_blank">'+this.examAttach+'</a><button type="button" onclick="deleteFileAct(\'examAttach\')">첨부파일삭제</button></div><input type="checkbox" name="delFile01" value="Y" />';
						$('.writeform label.AttachFiles:eq('+filePoint+')').after(files);
						$('.writeform label.AttachFiles:eq('+filePoint+')').remove();
					}else{
						filePoint ++ ;
					}
					if(this.exampleAttach != null){
						var files = '<div id="exampleAttach" class="attachFile"><a href="fileDownLoad.php?fileName='+this.exampleAttach+'&link='+this.exampleAttachLink+'" target="_blank">'+this.exampleAttach+'</a><button type="button" onclick="deleteFileAct(\'exampleAttach\')">첨부파일삭제</button></div><input type="checkbox" name="delFile02" value="Y" />';
						$('.writeform label.AttachFiles:eq('+filePoint+')').after(files);
						$('.writeform label.AttachFiles:eq('+filePoint+')').remove();
					}else{
						filePoint ++;
					}
					if(this.rubricAttach != null){
						var files = '<div id="rubricAttach" class="attachFile"><a href="fileDownLoad.php?fileName='+this.rubricAttach+'&link='+this.rubricAttachLink+'" target="_blank">'+this.rubricAttach+'</a><button type="button" onclick="deleteFileAct(\'rubricAttach\')">첨부파일삭제</button></div><input type="checkbox" name="delFile03" value="Y" />';
						$('.writeform label.AttachFiles:eq('+filePoint+')').after(files);
						$('.writeform label.AttachFiles:eq('+filePoint+')').remove();
					}
					top.location.href='#bottom';
				}
			})
		}
	})
	function copyAct(){
		$.ajax({
			url:matchingApi,
			data:copyDate,
			type:'POST',
			success:function(){
				alert('복사가 완료되었습니다.')
				matchingList(codes, contypes, testType);
			}
		})
	}
	if(action == 'delete'){
		$.ajax({
			url:matchingApi,
			data:'seq='+seqNum,
			type:'DELETE',
			dataType:'text',
			success:function(data){
				if(data == 'success'){
					alert('삭제되었습니다.');
					resetInput();
					matchingList(codes, contypes, testType);
				}else{
					alert('삭제 중 문제가 발생하였습니다.')
				}
			},
			done:function(){
			}
		})
	}
}


function editorOpen (apiName,editPart,types){
	var contentsCode = $('.writeform input[name="contentsCode"]').val();
	var testType = $('.writeform input[name="testType"]').val();
	var examNum = $('.writeform input[name="examNum"]').val();
	var editorText = $('#'+editPart).html();
	var modalWrite =''
	modalWrite +='<div id="modal">';
	if(types == 'report'){
		modalWrite += '<div class="modalReportEditor">';
	}else{
		modalWrite += '<div class="modalEditor">';
	}
	modalWrite += '<h1>보기편집<button type="button" onClick="modalClose()"><img src="../../images/admin/btn_close.png" alt="닫기" /></button></h1>';
	modalWrite += '<div>'
	if(testType == 'final'){
		var testName = '최종평가';
	}else{
		var testName = '진행단계평가';
	}
	var exampleType = '';
	if(editPart == 'exam'){
		exampleType = '문제';
	}else if(editPart == 'commentary'){
		exampleType = '해설내용';
	}else if(editPart == 'example'){
		exampleType = '답안등록';
	}else if(editPart == 'rubric'){
		exampleType = '채점기준';
	}else{
		exampleType = editPart.replace('example0','')+'번 보기';
	}
	var examNumType = '';
	if(examNum != ''){
		examNumType = examNum +'번 문항';
	}else{
		examNumType = '신규문항';
	}
	modalWrite += '<h1>'+examNumType+'&nbsp;|&nbsp;' + exampleType +'</h1>';	
	modalWrite += '<form class="editorArea" action="sample.php" method="post">';
	if(types == 'report'){
		modalWrite += '<textarea name="'+editPart+'" id="ir1" rows="10" cols="100" style="width:1054px; height:460px; display:none;">'+editorText+'</textarea>';
	}else{
		modalWrite += '<textarea name="'+editPart+'" id="ir1" rows="10" cols="100" style="width:827px; height:460px; display:none;">'+editorText+'</textarea>';
	}
	modalWrite += '</form>';
	modalWrite += '<div class="btnArea"><button type="button" onclick="submitContents(this,\''+editPart+'\')">정보등록</button></div>';
	modalWrite += '</div>';
	modalWrite += '<button type="button" class="btnRefresh" style="display:none">새로고침</button>'
	//--모달테두리
	modalWrite += '</div>';
	$('#contents').after(modalWrite);
	$('.btnRefresh').click(function(){editorView()})
	//editorView();	
	modalAlign();
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "ir1",
		sSkinURI: "../lib/SmartEditor/SmartEditor2Skin.html",	
		htParams : {
			bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseVerticalResizer : false,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
			//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
			fOnBeforeUnload : function(){
				//alert("완료!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});	
}		
//에디터 사용시 호출
var oEditors = [];

function submitContents(elClickedObj,changeID) {
	oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.	
	// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.	
	try {
		var changeText = $('#ir1').val();
		$('div#'+changeID).html(changeText);
		$('textarea[name="'+changeID+'"]').val(changeText);
	} catch(e) {}
	modalClose();
}

function sendContens(codes, types, testType){
	if(confirm('등록하시겠습니까?')){
		$('.writeform').ajaxForm({
			dataType:'JSON',
			beforeSubmit: function (data,form,option) {
				return true;
			},
			success: function(data,status){
				if(data.result != 'success'){
					alert(data.result);
				}else{
					alert("작성이 완료되었습니다.");
					matchingList(codes, types, testType);
					resetInput();
					top.location.href='#';
				}
			},
			error: function(){
				//에러발생을 위한 code페이지
				alert("작성중 문제가 생겼습니다..");
			}
		});	
		$('.writeform').submit();
	}
}

function allDelete(codes, types, testType){
	if(confirm('등록된 전체 문항을 삭제하시겠습니까? 삭제 후 복구 하실 수 없습니다.')){
		if(types == 'testWrite'){
			var apiSelect = testApi;
		} else {
			var apiSelect = reportApi;
		}
		$.ajax({
			url:apiSelect,
			type:'DELETE',
			data:'allDelete=Y&contentsCode='+codes+'&testType='+testType,
			success:function(){
				alert('삭제되었습니다.');
				matchingList(codes, types, testType);
			},
			error: function(){
				alert("오류가 발생하였습니다.");
				matchingList(codes, types, testType);
			}			
		})
	}
}

function previewAct(codes,types,testType){
	popupAddress = '_popup_contents.php?codes='+codes+'&types='+types+'&testType='+testType;
	window.open(popupAddress,"문제미리보기","menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","previewContents")
}