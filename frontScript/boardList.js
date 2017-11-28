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
	var boardInfo = $.get('../api/apiBoardInfo.php',{'seq':boardCode},function(data){
		$.each(data.boardInfo, function(){
			boardName = this.boardName;
			boardMode = this.boardMode;
			useName = this.useName;
			useEmail = this.useEmail;
			usePhone = this.usePhone;
			usePassword = this.usePassword;
			useSecret = this.useSecret;
			useTop = this.useTop;
			useCategory = this.useCategory;
			useReply = this.useReply;
			useComment = this.useComment;
			useFile = this.useFile;
			useSearch = this.useSearch;
			useDateView = this.useDateView;
			useHitView = this.useHitView;
			memo = this.memo;
			titleHtml = boardName +'<span>'+ memo +'</span>';
		})
	}).done(function(){
		$.get('../api/apiBoardPermit.php',{'boardCode':boardCode},function(data){
			$.each(data.boardPermit, function(){
				listPermit = this.listPermit;
				viewPermit = this.viewPermit;
				writePermit = this.writePermit;
				replyPermit = this.replyPermit;
				deletePermit = this.deletePermit;
				commentPermit = this.commentPermit;
				secretPermit = this.secretPermit;
				topPermit = this.topPermit;
			})
		})
		.done(function(){
			listPage = listPage ? listPage : 1;
			page = listPage;
			//상단 액션 부분
			if(pageMode=='adminPage'){
				$('#contents > h1').html(titleHtml);
			}else{
				$('#titleArea > h2 > strong').html(boardName);
				$('#titleArea > h1').html(boardName);
			}
			//상단 액션 부분
			if(useSearch == "Y"){
				var actionArea = '';
				actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
				if(eval(writePermit) >= eval(loginUserLevel) && eval(boardMode) != 7){
					actionArea += '<button type="button" class="fRight" onClick="writeAct()">새글쓰기</button>';
				}
				actionArea += '<select name="searchType">';	
				actionArea += '<option value="userName">이름</option>';
				actionArea += '<option value="userID">아이디</option>';
				actionArea += '<option value="subject">제목</option>';
				actionArea += '</select>&nbsp;';
				actionArea += '<input name="searchValue" type="text" />&nbsp;';
				actionArea += '<button type="button" onClick="searchAct()">검색하기</button></form>';
				actionArea += '</form></div>';
				if(pageMode=='adminPage'){
					$('#contents > h1').after(actionArea);
				}else{
					$('#contentsArea').before(actionArea);
				}
			}
			
			if(useCategory == "Y"){
				var categorys = ''
				if(pageMode == 'studyCenterPage'){
					categorys += '<ul class="tabMenu">'
				}else{
					categorys += '<ul class="BBSCategory">'
				}				
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
					$('#contentsArea').before(categorys);
					//카테고리 선택시 액션
					$('.BBSCategory li, .tabMenu li').bind({
						click:function(){
							$('.BBSCategory li, .tabMenu li').removeClass('select')
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
				/*
				if(eval(writePermit) >= eval(loginUserLevel)){
					contents += '<div class="btnArea"><button type="button" class="fRight" onClick="writeAct()">새글쓰기</button></div>';
				}
				*/
			}
			$('#contentsArea').removeAttr('class')
			$('#contentsArea').addClass('BBSList')
			$('#contentsArea').html(contents);
			ajaxAct();
		})
	})
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
					$.each(data.boardTop, function(){
						lists += '<tr ';
						if(eval(boardMode) == 4){
							lists += 'id="line'+this.seq+'"'
						}
						lists += 'class="notice">';
						lists += '<td>[공지]</td>';
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
		}else if(eval(boardMode) == 7){ //수강후기
            var i = 1
            if(page == 1){
                i = 1
            }else{
                i = page * listCount + 1
            }
            limit = (page - 1) * listCount;
            i = data.totalCount - limit; //게시글 번호 역순

            lists += '<li class="reviewTitle">';
            lists += '<h1 style="width:5.5%">번호</h1>'
            lists += '<h1 style="width:15%">별점</h1>'
            lists += '<h1>과정명 / 내용</h1>'
            lists += '</li>';
            if(data.totalCount != 0 ){
                $.each(data.board, function(){
                    lists += '<li onclick="reviewAct(this)">';
                    lists += '<h1>'+i+'</h1>';
                    lists += '<h2 class="scroe'+this.addItem02+'">[ <strong>'+this.addItem02+'</strong>/5점 ]</h2>';
                    lists += '<div>';
                    lists += '<h1>['+this.subject+']</h1>';
                    lists += '<p>'+this.content+'</p>'
                    lists += '</div>';
                    lists += '</li>';
                    i--
                });
            }else{
                lists += '<li class="noList">게시글이 없습니다.</li>'
            }
            $('.BBSList ul').addClass('reviewList')
        }
		if(eval(boardMode) != 7){
			$('.BBSList tbody').html(lists)
		}else{
			$('.BBSList ul').html(lists)
		}
		pagerAct();
	})
	
}