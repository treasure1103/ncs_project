$(document).ready(function(){
	gnbAct();
})

var studyCenterApi = $.get('/api/apiStudyCenter.php',{'companyID':subDomain},function(data){
	var footerWrite = '';
	var CSWrite = '';
	/*
	footerWrite += '<img src="../images/global/logo_footer.png" alt="" />';
	footerWrite += '<ul>';
	footerWrite += '<li onclick="top.location.href=\'about.php\'">회사소개</li>';
	footerWrite += '<li onclick="top.location.href=\'agree.php\'">이용약관</li>';
	footerWrite += '<li onclick="top.location.href=\'private.php\'">개인정보 취급방침</li>';
	footerWrite += '<li onclick="top.location.href=\'eduinfo.php\'">환급교육 안내</li>';
	footerWrite += '</ul>';
	footerWrite += '<br /><address><strong>NCS이러닝센터</strong>&nbsp;&nbsp;|&nbsp;&nbsp;주소 : 서울시 양천구 오목로 209, 401호 NCS이러닝센터&nbsp;&nbsp;|&nbsp;&nbsp;tel : 02-2631-7652&nbsp;&nbsp;|&nbsp;&nbsp;fax : 02-2631-7654<br />사업자등록번호 : 443-88-00296 | 통신판매업 : 제2016-서울강서-0366호 | 대표자 : 이은영</address>';
	*/
	if(data.studyCenter[0].studyFooterImg != null || data.studyCenter[0].studyFooterImg != 'N') {
		footerWrite += '<img src="/attach/studyCenter/footer_'+subDomain+'.jpg" alt="footer">';
	} else {
		footerWrite += '';
	}
	CSWrite += '<h1>고객센터</h1><h2>'+data.studyCenter[0].phone+'</h2>';
	CSWrite += '<table>';
	CSWrite += '<tr><th>팩스</th><td>'+data.studyCenter[0].fax+'</td></tr>';
	CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
	CSWrite += '</table>';
	$('#GNB > div > a').html('<img src="/attach/studyCenter/logo_'+subDomain+'.png" alt="logo" />');
	$('#main_contents > img').attr('src','/attach/studyCenter/image_'+subDomain+'.jpg');
	$('#footer > div').html(footerWrite);
	$('#main_contents .CSCenter').html(CSWrite);
})
.done(function(data){
	contentsMapping = data.studyCenter[0].contentsMapping;
	$(document).ready(function(){
		var designType = data.studyCenter[0].studyColor;
		if(designType <= 9){
			designType = '0' + designType;
		}
		designType = 'type'+ designType;
		$('body').addClass(designType);		
		if (typeof seq != 'undefined' || typeof page != 'undefined' ){
			if(seq == ''){
				listAct();
			}else if(seq != ''){
				viewAct(seq);
			}
		}
	})		
})
function loginScript(){
  $('input[name="userID"]').bind({
	  focus:function(){
		  if($(this).val()=='아이디'){
			  $(this).val('')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='' || $(this).val()=='아이디'){
			  $(this).val('아이디')
		  }
	  }
  })
  $('input[name="pwd"]').bind({
	  focus:function(){
		  if($(this).attr('type')=='text'|| $(this).val()=='비밀번호'){
			  $(this).val('');
			  $(this).attr('type','password')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='비밀번호' || $(this).val()==''){
			  $(this).attr('type','text')
			  $(this).val('비밀번호');
		  }
	  }
  })
}

function logOut(){
	$.ajax({
		url:'../api/apiLogin.php',
		type:'DELETE',
		dataType:'text',
		success: function(data){
			//alert('로그아웃되었습니다.');
			window.location.reload();
		},
		error:function(){
			alert('로그아웃이 실패하였습니다.');
		}
	})
}

function addFavorite(){
	var bookmarkURL = window.location.href;
	var bookmarkTitle = document.title;
	var triggerDefault = false;

	if (window.sidebar && window.sidebar.addPanel) {
		// Firefox version < 23
		window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
	} else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
		// Firefox version >= 23 and Opera Hotlist
		var $this = $(this);
		$this.attr('href', bookmarkURL);
		$this.attr('title', bookmarkTitle);
		$this.attr('rel', 'sidebar');
		$this.off(e);
		triggerDefault = true;
	} else if (window.external && ('AddFavorite' in window.external)) {
		// IE Favorite
		window.external.AddFavorite(bookmarkURL, bookmarkTitle);
	} else {
		// WebKit - Safari/Chrome
		alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 키를 눌러 즐겨찾기에 등록하실 수 있습니다.');
	}

	return triggerDefault;
}

function gnbAct(){
	var divHeight = 81;
	var olHeight = '';
	$("#GNB ul").bind({
		mouseenter:function() {
			$('#GNB ol').each(function(){
				if(olHeight <= $(this).outerHeight()){
					olHeight = $(this).outerHeight();
				}
			})
			$("#GNB div:not(:animated)").animate({'height':divHeight+olHeight+'px'},250);
		},
		mouseleave:function() {
			$("#GNB div").animate({'height':divHeight+'px'},120);
		}
	});
}

function snbSel(selNum){     
   	$('#snb li:nth-child('+selNum+')').addClass('select');
}