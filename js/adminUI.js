//	관리자 UI 퍼포먼스, 레이아웃 스크립트
//	작성자 : 서영기
//

$(document).ready(function(){
	GNBAct('adminGNB')//관리자:adminGNB, 사용자:userGNB;
    //pageLayout();
});
/*
$(window).bind('resize', function(){
    window.resizeEvt;
    $(window).resize(function(){
        clearTimeout(window.resizeEvt);
        window.resizeEvt = setTimeout(function(){
			pageLayout()
        }, 250);
    });
});

function pageLayout() {
	var docuHeight = $('body').outerHeight();
	var windowHeight = $(window).height();
	var headerHeight = $('#header').outerHeight();
	var subHeightMin = windowHeight - headerHeight;
	var subHeightMax = docuHeight - headerHeight;
	//alert(docuHeight+'/'+windowHeight)
	if( docuHeight < windowHeight ){
		$('#subMenu').outerHeight(subHeightMin);
	}else{
		$('#subMenu').outerHeight(subHeightMax);
	}
}
*/

function logOut(){
	$.ajax({
		url:'../api/apiLogin.php',
		type:'DELETE',
		dataType:'text',
		success: function(data){
			alert('로그아웃되었습니다.');
			//top.location.href='../admin/00_login.php';
			top.location.href='../main';
		},
		error:function(){
			alert('로그아웃이 실패하였습니다.');
		}
	})
}