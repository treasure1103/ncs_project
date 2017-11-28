<? include '../include/header.php' ?>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript">
  var contentsCode = '<?=$_GET[contentsCode]; ?>'; 
  var useApi = '../api/apiContents.php';
  var chapterApi = '../api/apiChapter.php';
  var reviewApi = '../api/apiBoard.php';
  //상단 액션 부분
  var views = '';
  $.get(useApi,{'contentsCode':contentsCode},function(data){
	  imageURL = data.previewImageURL;
	  bookURL = data.bookImageURL;
	  views += '<div class="navArea">';
	  views += '<h1>과정상세보기</h1>';
	  views += '<button type="button" onclick="self.close()" title="닫기"><img src="../images/admin/btn_close.png" alt="닫기" /></button>';
	  views += '</div>';
	  views += '<div class="lectureArea">';
	  $.each(data.contents, function(){
		  views += '<div class="summuryArea">';
		  if(this.previewImage != '' && this.previewImage != null){
			  views += '<img src="'+imageURL+this.previewImage+'" alt="'+this.contentsName+'" />';
		  }else{
			  views += '<img src="/images/lecture/img_noimage.png" alt="이미지가 준비중입니다." />'
		  }
		  views += '<h5>'+this.sort01Name+' <img src="../images/global/icon_triangle.png" alt="화살표" /> '+this.sort02Name+'</h5>'
		  views += '<h1>'+this.contentsName+'</h1>';
		  views += '<h2>총 <strong>'+this.chapter+'</strong>차시 / <strong>'+this.contentsTime+'시간</strong> 교육과정</h2>';
		  views += '<h3><strong>'+this.professor+'</strong> 강사</h3>';
		  views += '</div>';
		  

		  if (this.serviceType == 1){  // 환급 과정일때만 평가 항목 출력
			  //수료기준
			  views += '<h1>수료기준 및 수강정원</h1>'
			  views += '<table><tr>';
			  views += '<th>수강정원</th>';
			  views += '<th>총 진도율</th>';
			  views += '<th>중간평가</th>';
			  views += '<th>최종평가</th>';
			  views += '<th>과제</th>';
			  views += '</tr><tr>';
			  views += '<td rowspan="2"><strong>'+this.limited+'</strong>명</td>';
			  views += '<td rowspan="2"><strong>'+this.passProgress+'</strong>% 이상</td>';
			  views += '<td>총&nbsp;<strong>';
			  if(this.totalPassMid != 0){
				  views += this.totalPassMid+'</strong>점 / <strong>'+this.midRate+'</strong>% 반영';
			  }
			  views += '</td>';
			  views += '<td>총&nbsp;<strong>';
			  if(this.totalPassTest != 0){
				  views += this.totalPassTest+'</strong>점 / <strong>'+this.testRate+'</strong>% 반영';
			  }
			  views += '</td>';
			  views += '<td>';
			  if(this.totalPassReport != 0){
				  views += '총&nbsp;<strong>'+this.totalPassReport+'</strong>점 / <strong>'+this.reportRate+'</strong>% 반영';
			  } else {
				  views += '없음';
			  }
			  views += '</td>';
			  views += '</tr><tr>';
			  views += '<td colspan="3">반영된 평가, 과제 점수 합산 <strong>'+this.passScore+'</strong>점 이상</td>';
			  views += '</tr></table>';
			  
			  //교육비
			  views += '<h1>교육비 안내</h1>'
			  views += '<table><tr>';
			  views += '<th>교육비</th>';
			  views += '<th>환급 : 우선지원 기업</th>';
			  views += '<th>환급 : 대규모<br />(1000인 미만)</th>';
			  views += '<th>환급 : 대규모<br />(1000인 이상)</th>';
			  views += '</tr><tr>';
			  views += '<td><strong>'+toPriceNum(this.price)+'</strong>원</td>';
			  views += '<td><strong>'+toPriceNum(this.rPrice01)+'</strong>원</td>';
			  views += '<td><strong>'+toPriceNum(this.rPrice02)+'</strong>원</td>';
			  views += '<td><strong>'+toPriceNum(this.rPrice03)+'</strong>원</td>';
			  views += '</tr></table>';
		  } else {
			  views += '<h1>수료기준</h1>'
			  views += '총 진도율 <strong>'+this.passProgress+'</strong>% 이상';
		  }
		  
		  //교육교재안내
		  if(this.bookIntro != '' && this.bookIntro != null){
			  views += '<h1>교재정보</h1>'
			  views += '<div class="bookInfo">'
			  if(this.bookImage != '' && this.bookImage != null){
				  views += '<img src="'+bookURL+this.bookImage+'" alt="교재이미지">';
			  }else{
			  views += '<img src="/images/lecture/img_nobooks.png" alt="이미지가 준비중입니다." />'
			  }
			  views += '<h1>'+this.bookIntro+'</h1>'
			  views += '</div>';
		  }

		  //교육소개관련
		  if(this.intro != '' && this.intro != null){
			  views += '<h1>과정소개</h1>';
			  views += '<div class="infoArea">';
			  views += this.intro.replace(/\n/g,'<br />');
			  views += '</div>';
		  };
		  if(this.target != '' && this.target != null){
			  views += '<h1>교육대상</h1>';
			  views += '<div class="infoArea">';
			  views += this.target.replace(/\n/g,'<br />');;
			  views += '</div>';
		  };
		  if(this.goal != '' && this.goal != null){
			  views += '<h1>교육목표</h1>';
			  views += '<div class="infoArea">';
			  views += this.goal.replace(/\n/g,'<br />');;
			  views += '</div>';
		  };
		  //목차관련
		  views += '<h1>교육목차</h1>';
		  views += '<ol></ol>';
		  views += '</div>';
	  })
	  $('body').addClass('lectureDetail');
	  $('body').html(views);
  }).done(function(data){
	  $.each(data.contents, function(){
		  $.get(chapterApi,{'contentsCode':this.contentsCode},function(data){
			  var chapterWrite = '';
			  $.each(data.chapter,function(){
				  chapterWrite += '<li>'+this.chapterName+'</li>'
			  })
			  $('.lectureDetail ol').html(chapterWrite);
		  })
	  })		
  }).always(function(data){
	  $.each(data.contents, function(){
		  $.get(reviewApi,{'boardCode':'3','list':'5','page':'1','addItem01':this.contentsCode},function(data){
			  var reviewCount = data.totalCount
			  if(reviewCount != 0){
				  var reviewWrite = '';
				  reviewWrite += '<h1>수강후기<button type="button" onclick="openReview(\''+data.board[0].addItem01+'\');">댓글 더보기+</button></h1>';
				  reviewWrite += '<div class="reviewArea">';
				  reviewWrite += '<ul>'
				  $.each(data.board, function(){
					  reviewWrite += '<li>';
					  reviewWrite += '<div>';
					  reviewWrite += '<h3 class="scroe'+this.addItem02+'">[ <strong>'+this.addItem02+'</strong>/5점 ]</h3>';
					  reviewWrite += '<h1>'+this.userName.substr(0,this.userName.length-1)+'*('+this.userID.substr(0,this.userID.length-3)+'***)</h1>';
					  reviewWrite += '<h2>Date : '+this.inputDate+' | IP : '+this.userIP+'</h2>';
					  reviewWrite += '<p>'+this.content+'</p>';
					  reviewWrite += '</div>';
					  reviewWrite += '</li>';
				  })
				  reviewWrite += '</ul>'
				  reviewWrite += '</div>';
			  }
			  $('div.lectureArea').append(reviewWrite)
		  })
	  })
  })
</script>
</head>
<body>
</body>
</html>