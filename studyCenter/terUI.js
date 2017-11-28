$(document).ready(function(){
	gnbAct()
})

var studyCenterApi = $.get('/api/apiStudyCenter.php',{'companyID':subDomain},function(data){
	var footerWrite = '';
	var CSWrite = '';
	if(data.studyCenter[0].marketerID == 'kosia'){
		
		footerWrite += '<img src="../images/global/logo_footer_kosia.png" alt="이상에듀, 한국HR서비스산업협회" />';		
		footerWrite += '<address><strong>한국HR서비스산업협회</strong>&nbsp;&nbsp;|&nbsp;&nbsp;06149 서울특별시 강남구 선릉로 99길 16 신장빌딩4층<br />';
		footerWrite += '대표전화 : 02-553-1661&nbsp;|&nbsp;FAX : 02-553-1663<br />Copyright(c) 2016 KOREAN STANDARDS ASSOCIATION. ALL RIGHTS RESERVED.</address>';
		
		CSWrite += '<h1>고객센터</h1><h2>02.553.1661</h2>';
		CSWrite += '<table>';
		CSWrite += '<tr><th>시스템문의</th><td>02.6494.2010</td></tr>';
		CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
		CSWrite += '</table>';
		
		$('#GNB > div > a').html('<img src="/attach/studyCenter/logo_kosia.png" alt="logo" />'+data.studyCenter[0].companyName);
		$('#main_contents > img').attr('src','/attach/studyCenter/image_kosia.jpg');
		
	}else{
		if(subDomain == 'ksa' || subDomain == 'knfc'){
			footerWrite += '<address><strong>한국표준협회</strong>&nbsp;&nbsp;|&nbsp;&nbsp;06152 서울특별시 강남구 테헤란로 305 대표전화 : 1670-6009<br />Copyright(c) 2016 KOREAN STANDARDS ASSOCIATION. ALL RIGHTS RESERVED.</address>';
			CSWrite += '<h1>고객센터</h1><h2>02.6494.2010</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>팩스</th><td>02.6008.2012</td></tr>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'kspeaed') {
			footerWrite += '<address>한국건설안전기술사회 | 서울시 강남구 일원로9길 38, 형일빌딩 4층(일원동 666-7) | TEL)02-3453-8694~6 | FAX)02-3453-8697</address>';
			CSWrite += '<h1>고객센터</h1><h2>02-3453-8694</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>팩스</th><td>02-3453-8697</td></tr>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'mooyoung') {
			footerWrite += '<img src="../images/global/logo_footer.png" alt="이상에듀" />';
			footerWrite += '<ul>';
			footerWrite += '<li onclick="top.location.href=\'about.php\'">회사소개</li>';
			footerWrite += '<li onclick="top.location.href=\'agree.php\'">이용약관</li>';
			footerWrite += '<li onclick="top.location.href=\'private.php\'">개인정보 취급방침</li>';
			footerWrite += '<li onclick="top.location.href=\'eduinfo.php\'">환급교육 안내</li>';
			footerWrite += '</ul>';
			footerWrite += '<address><strong>(주)나야넷</strong>&nbsp;&nbsp;|&nbsp;&nbsp;주소 : 08506) 서울시 금천구 가산디지털1로131 A동 301-가호(가산동,, 비와이씨하이시티)<br />tel : 02.6494.2010&nbsp;&nbsp;|&nbsp;&nbsp;fax : 02.6008.2012&nbsp;&nbsp;|&nbsp;&nbsp;원격평생교육시설신고 : 제601호&nbsp;&nbsp;|&nbsp;&nbsp;사업자등록번호 : 783-87-00353<br />copyright 2016 Nayanet allright &amp; reserved</address>';
			CSWrite += '<h1>고객센터</h1><h2>02-739-4770</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>팩스</th><td> 0505-977-9090</td></tr>';
			CSWrite += '<tr><th>이메일</th><td>seoul@nayanet.kr</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'eduline') {
			CSWrite += '<h1>고객센터</h1><h2>1599.8230</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 21:00<br />주말 09:00 ~ 16:00)</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'officen') {
			CSWrite += '<h1>고객센터</h1><h2>02.6494.2010</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>팩스</th><td>02.6008.2012</td></tr>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'ubsocius') {
			CSWrite += '<h1>고객센터</h1><h2>02-855-0834</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:30 ~ 18:00</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'urbanworks') {
			CSWrite += '<h1>고객센터</h1><h2>02-855-0834</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:30 ~ 18:00</td></tr>';
			CSWrite += '</table>';
		}else if (subDomain == 'seoul') {
			footerWrite += '<img src="../images/global/logo_footer.png" alt="이상에듀" />';
			footerWrite += '<ul>';
			footerWrite += '<li onclick="top.location.href=\'about.php\'">회사소개</li>';
			footerWrite += '<li onclick="top.location.href=\'agree.php\'">이용약관</li>';
			footerWrite += '<li onclick="top.location.href=\'private.php\'">개인정보 취급방침</li>';
			footerWrite += '<li onclick="top.location.href=\'eduinfo.php\'">환급교육 안내</li>';
			footerWrite += '</ul>';
			footerWrite += '<address><strong>(주)나야넷</strong>&nbsp;&nbsp;|&nbsp;&nbsp;주소 : 서울 영등포구 문래동 6가 24-1 A사이테크시티 517호<br />TEL : 02.739.4770&nbsp;&nbsp;|&nbsp;&nbsp;원격평생교육시설신고 : 제601호&nbsp;&nbsp;|&nbsp;&nbsp;사업자등록번호 : 783-87-00353<br />copyright 2016 Nayanet allright &amp; reserved</address>';
			CSWrite += '<h1>고객센터</h1><h2>02.739.4770</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th style="width:90px;">수강신청접수</th><td>E-MAIL : seoul@nayanet.kr<br />FAX : 0505.977.9090</td></tr>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
			CSWrite += '</table>';
			
			$('#GNB > div > a').html('<img src="/attach/studyCenter/logo_kosia.png" alt="logo" />'+data.studyCenter[0].companyName);
			$('#main_contents > img').attr('src','/attach/studyCenter/image_kosia.jpg');
		}else{
			footerWrite += '<img src="../images/global/logo_footer.png" alt="이상에듀" />';
			footerWrite += '<ul>';
			footerWrite += '<li onclick="top.location.href=\'about.php\'">회사소개</li>';
			footerWrite += '<li onclick="top.location.href=\'agree.php\'">이용약관</li>';
			footerWrite += '<li onclick="top.location.href=\'private.php\'">개인정보 취급방침</li>';
			footerWrite += '<li onclick="top.location.href=\'eduinfo.php\'">환급교육 안내</li>';
			footerWrite += '</ul>';
			footerWrite += '<address><strong>(주)나야넷</strong>&nbsp;&nbsp;|&nbsp;&nbsp;주소 : 08506) 서울시 금천구 가산디지털1로131 A동 301-가호(가산동,, 비와이씨하이시티)<br />tel : 02.6494.2010&nbsp;&nbsp;|&nbsp;&nbsp;fax : 02.6008.2012&nbsp;&nbsp;|&nbsp;&nbsp;원격평생교육시설신고 : 제601호&nbsp;&nbsp;|&nbsp;&nbsp;사업자등록번호 : 783-87-00353<br />copyright 2016 Nayanet allright &amp; reserved</address>';
			CSWrite += '<h1>고객센터</h1><h2>02.6494.2010</h2>';
			CSWrite += '<table>';
			CSWrite += '<tr><th>팩스</th><td>02.6008.2012</td></tr>';
			CSWrite += '<tr><th>운영시간</th><td>평일 09:00 ~ 18:00<br />(점심시간 12:00 ~ 13:00)</td></tr>';
			CSWrite += '</table>';
		}
		$('#GNB > div > a').html('<img src="/attach/studyCenter/logo_'+subDomain+'.png" alt="logo" />');
		$('#main_contents > img').attr('src','/attach/studyCenter/image_'+subDomain+'.jpg');
	}
	$('#footer > div').html(footerWrite)
	$('#main_contents .CSCenter').html(CSWrite)
		
	
	//$('head title').html(data.studyCenter[0].companyName+' 사이버교육센터에 오신것을 환영합니다.');			  
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