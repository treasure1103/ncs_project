//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//리스트 소팅
var categoryLink = ''//카테고리 사용시 선택 메뉴탭	

function listAct(listPage){
	listPage = listPage ? listPage : 1;
	page = listPage;
	//상단 액션 부분
	//$('#contentsArea > h1').html(titleHtml);
	//상단 액션 부분
	//alert(boardMode);
	if(useSearch == "Y"){
		var actionArea = '';
		actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
		actionArea += '<select name="searchType">';	
		actionArea += '<option value="userName">이름</option>';
		actionArea += '<option value="userID">아이디</option>';
		actionArea += '<option value="subject">제목</option>';
		actionArea += '</select>&nbsp;';
		actionArea += '<input name="searchValue" type="text" />&nbsp;';
		actionArea += '<button type="button" onClick="searchAct()">검색하기</button></form>';
		actionArea += '</form>'
		actionArea += '&nbsp</div>';
		$('#contentsView').before(actionArea);
	}
	
	if(useCategory == "Y"){
		var categorys = ''
		categorys += '<ul class="BBSCategory">'
		var categorySort = $.get(categoryApi,{'value01':'boardCategory','divisionValue':boardCode},function(data){
			categorys += '<li id="cat" class="select">전체</li>';
			if(data.totalCount != 0){
				$.each(data.category,function(){
					if(this.enabled == 'Y'){
						categorys += '<li id="cat'+this.seq+'">'+this.value02+'</li>';
					}
				})
			}
			categorys += '</ul>'
			$('#contentsView').before(categorys);
			//카테고리 선택시 액션
			$('.BBSCategory li').bind({
				click:function(){
					$('.BBSCategory li').removeClass('select')
					$(this).addClass('select')
					categoryLink = $(this).attr('id')
					categoryLink = '&categorySeq='+categoryLink.slice(3);
					//alert(categoryLink)
					ajaxAct();
				}
			})			
		})		
	}	
	//게시판 상단 제목열 세팅
	var contents = ''
	if(boardMode == 1  || boardMode == 4){
		contents += '<table><thead><tr>';
		contents += '<th style="width:50px">번호</th>';
		if (useCategory == 'Y'){		
			contents += '<th style="width:10%;">카테고리</th>';
		}
		contents += '<th class="left">제목</th>';
		if (useName == 'Y'){
			contents += '<th style="width:15%;">작성자</th>';
		}
		if (useDateView == 'Y'){
			contents += '<th style="width:120px">작성일</th>';
		}
		if (useHitView == 'Y'){
			contents += '<th style="width:80px">조회수</th>';
		}
		contents += '</tr></thead><tbody>';
		contents += '</tbody></table>';
		if(eval(writePermit) >= eval(loginUserLevel)){
			contents += '<div class="btnArea"><button type="button" class="fRight" onClick="writeAct()">새글쓰기</button></div>';
		}
	}
	$('#contentsView').removeAttr('class')
	$('#contentsView').addClass('BBSList')
	$('#contentsView').html(contents);
	ajaxAct();
}

function ajaxAct(sortData){
	//게시물 소팅부분
	sortData = sortData ? sortData : '';//검색을 위한 소팅
	var lists = '';	
	var listAjax = $.get(useApi,'boardCode='+boardCode+'&page='+page+'&list='+listCount+sortData+categoryLink,function(data){
		totalCount = data.totalCount;
		if(eval(boardMode) == 1 || eval(boardMode) == 4){
			//초기 공지사항
			if(useTop == "Y"){
				if( eval(data.topCount) != 0 || eval(data.topCount) != ''){
					var i = 1;
					$.each(data.boardTop, function(){
						lists += '<tr ';
						if(eval(boardMode) == 4){
							lists += 'id="line'+this.seq+'"'
						}
						lists += 'class="notice">';
						lists += '<td>'+i+'</td>';
						if (useCategory == 'Y'){		
							lists += '<td>'+this.categoryName+'</td>';
						}
						lists += '<td class="left" '; 
						if(eval(boardMode) == 1){
							if(this.secret != "Y" ){ //|| loginUserType == 'admin' || loginUserID == this.userID
								lists += 'onclick="viewAct('+this.seq +')"';
							}else{
								lists += 'onclick="alert(\'a\')"'//'onclick="modalAct(\''+useApi+'\','+this.seq+')"';
							}
						}else if(eval(boardMode) == 4){
							lists += 'onclick="openView('+this.seq+')"';
						}
						lists += ' style="cursor:pointer">';
						if(this.replySeq != 0){
							lists += '<img src="../images/admin/icon_reply.png" />&nbsp;&nbsp;'
						}
						if(this.secret == 'Y'){
							lists += '<img src="../images/admin/icon_secret.png" />&nbsp;&nbsp;'
						}
						lists += this.subject;
						if(this.commentCount >= 1){
							lists += '&nbsp;&nbsp;<span>['+this.commentCount+']</span>'
						}
						if(this.attachFile01Name != null || this.attachFile02Name != null ){
							lists += '&nbsp;&nbsp;<img src="../images/admin/icon_addfile.png" />'
						}
						lists += '</td>';
						if (useName == 'Y'){
							lists += '<td>'+this.userName+'</td>';
						}
						if (useDateView == 'Y'){
							lists += '<td>'+this.inputDate.substring(0,10)+'</td>';
						}
						if (useHitView == 'Y'){
							lists += '<td>'+this.hits+'</td>';
						}
						lists += '</tr>';
						i++
					})
				}
			};
			
			if(eval(data.totalCount) != 0){
				var i = 1
				if(page == 1){
					i = 1
				}else{
					i = page * listCount + 1
				}
				$.each(data.board, function(){
					lists += '<tr ';
					if(eval(boardMode) == 4){
						lists += 'id="line'+this.seq+'"'
					}
					lists += '">';
					lists += '<td>'+i+'</td>'; 
					if (useCategory == 'Y'){		
						lists += '<td>'+this.categoryName+'</td>';
					}
					lists += '<td class="left" '; 
					if(eval(boardMode) == 1){
						if(this.secret != "Y" || loginUserType == 'admin' || loginUserID == this.userID ){ 
							lists += 'onclick="viewAct('+this.seq +')"';
						}else{
							lists += 'onclick="modalAct(\''+useApi+'\','+this.seq+',\'viewCheck\')"';
						}
					}else if(eval(boardMode) == 4){
						lists += 'onclick="openView('+this.seq+')"';
					}
					lists += ' style="cursor:pointer">';
					if(this.replySeq != 0){
						lists += '<img src="../images/admin/icon_reply.png" />&nbsp;&nbsp;'
					}
					if(this.secret == 'Y'){
						lists += '<img src="../images/admin/icon_secret.png" />&nbsp;&nbsp;'
					}
					lists += this.subject;
					if(this.commentCount >= 1){
						lists += '&nbsp;&nbsp;<span>['+this.commentCount+']</span>'
					}
					if(this.attachFile01Name != null || this.attachFile02Name != null ){
						lists += '&nbsp;&nbsp;<img src="../images/admin/icon_addfile.png" />'
					}
					lists += '</td>';
					if (useName == 'Y'){
						lists += '<td>'+this.userName+'</td>';
					}
					if (useDateView == 'Y'){
						lists += '<td>'+this.inputDate.substring(0,10)+'</td>';
					}
					if (useHitView == 'Y'){
						lists += '<td>'+this.hits+'</td>';
					}
					lists += '</tr>';
					i++
				})
			}else{
				lists += '<tr><td class="notResult" colspan="20">게시글이 없습니다.</td></tr>'
			}
		}else if(eval(boardMode) == 2){ //겔러리형
		}else if(eval(boardMode) == 3){ //웹진형
		}
		$('.BBSList tbody').html(lists)
		pagerAct();
	})
	
}