  var useApi = '/api/apiStudyChapter.php';
  var progressApi = '/api/apiProgressCheck.php';
  var contentsCode = '<?=$_GET[contentsCode]; ?>'; //검색 페이지
  var chapter = '<?=$_GET[chapter]; ?>'; //검색 페이지
  var lectureOpenSeq = '<?=$_GET[lectureOpenSeq]; ?>'; //검색 페이지
  var types = '<?=$_GET[types]; ?>'; //검색 페이지
  var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
  var totalTime = 0;
  var studyChapter = '';
  var sendTime = 10000; //진도체크시간
  var nowPageURL = '';
  var nowPage = '';
  //var nameCheck = setInterval(checkStudy, 5000);
  var studyPopup = $.get(useApi,{'contentsCode':contentsCode,'chapter':chapter,'lectureOpenSeq':lectureOpenSeq},function(data){
	  var contentsName = data.contentsName;
	  $.each(data.progress, function(){
		  if(types == 'new'){
			  nowPageURL = this.chapterPath;
		  }else{
			  nowPageURL = this.lastPage;
		  }
		  totalTime = this.totalTime;
		  $('iframe').attr('src',nowPageURL);
		  $('#popupStudyArea > h1').html(contentsName)
		  $('#popupStudyArea > h2').html(this.chapter+'차시 | '+this.chapterName)
		  //$('#popupStudyArea > h5 strong').html(totalTime)
		  $('#popupStudyArea > div li').eq(0).children('h1').after(this.professor.replace(/\n/g,'<br />'))
		  $('#popupStudyArea > div li').eq(1).children('h1').after(this.goal.replace(/\n/g,'<br />'))
		  $('#popupStudyArea > div li').eq(2).children('h1').after(this.content.replace(/\n/g,'<br />'))
		  $('#popupStudyArea > div li').eq(3).children('h1').after(this.activity.replace(/\n/g,'<br />'))
	  })
	  $('iframe').bind({
		  load:function(){
			  checkSize();
			  checkChapter();
			  progressSend();
			  opener.parent.viewStudyDetail(seq,contentsCode,lectureOpenSeq,'Y');
		  }
	  })
  })
  .done(function(){
	  
	  //타이머
	  timeCheck = setInterval(function(){timer()},1000);
	  
	  totalTime = Number(totalTime);
	  function timer(){
		  totalTime += 1;		  
		  //각각의 형태만들기
		  hour = Math.floor(totalTime / 3600);
		  minute = Math.floor( (totalTime-(hour*3600)) / 60 );
		  sec = totalTime - (hour*3600) - (minute*60);

		  // hh:mm:ss 형태를 유지하기 위해 한자리 수일 때 0 추가
		  if(hour < 10) hour = "0" + hour;
		  if(minute < 10) minute = "0" + minute;
		  if(sec < 10) sec = "0" + sec;
		  $('#popupStudyArea > h5 strong').html(hour+':'+minute+':'+sec);		  
	  }
	  
	  //진도보내기 시간별
	  progressCheck = setInterval(function(){progressSend()},sendTime)	  
  })
  
  function progressSend(){
	  //alert(lectureOpenSeq+'/'+contentsCode+'/'+chapter+'/'+nowPage+'/'+nowPageURL+'/'+totalTime)
	  var sendData = 'lectureOpenSeq='+lectureOpenSeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&nowPage='+nowPage+'&nowPageURL='+nowPageURL+'&totalTime='+totalTime;
	  $.post(progressApi,sendData)
  }
  
  function checkChapter(){
	  nowPageURL = frames[0].document.location.href;
	  nowPages = nowPageURL.slice(7);//http자르기
	  var filearr = nowPages.split("/");
	  //페이지진도 계산
	  var selectFile = filearr.length -1;
	  var nowPageFull = filearr[selectFile].replace('.html','').replace('.htm','');
	  var nowPageURLFull = filearr[selectFile];
	  nowPage = Number(nowPageFull.substr(nowPageFull.length-2,2));//페이지 진도
	  
	  //현재 주소값 계산
	  var nowAddress = filearr[0];
	  nowAddress = nowAddress.length;
	  nowPageURL = nowPageURL.slice(7+Number(nowAddress))
  }
  function checkSize(){
	  var flashWidth = $('iframe').contents().find('object').attr('width');
	  var flashHeight = $('iframe').contents().find('object').attr('height');
	  var windowWidth = Number(flashWidth) + 360 ;
	  var windowHeight = Number(flashHeight) + 74;
	  $('iframe').css({'width':flashWidth+'px','height':flashHeight+'px'})
	  $('#studyPopup > div').css({'height':flashHeight+'px'})
	  window.resizeTo(windowWidth,windowHeight)
  }
  function tabAct(num){
	  $('#popupStudyArea > ul li').removeClass('select');
	  $('#popupStudyArea > div, #popupStudyArea > form').css('display','none');
	  if(num == 1){
		  $('#popupStudyArea > ul li').eq(0).addClass('select');
		  $('#popupStudyArea > div').css('display','block');
	  }else{
		  $('#popupStudyArea > ul li').eq(1).addClass('select');
		  $('#popupStudyArea > form').css('display','block');
	  }
  }
  
  function endStudy(){
	  progressSend();
      opener.parent.viewStudyDetail(seq,contentsCode,lectureOpenSeq,'Y');
	  window.close();
  }