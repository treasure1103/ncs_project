$(document).ready(function(){
	subDomainCheck()
});
function subDomainCheck(){
	$.get('/api/apiStudyCenter.php',{'companyID':subDomain},function(data){
		if(data.totalCount != 0 && subDomain != ''){
			top.location.href='http://'+subDomain+'.educenter.kr/studyCenter/';
		}
	})
	.done(function(){
		newNotice();
	})
}

function newNotice(){
	var noewNoticeAjax = $.get('/api/apiBoard.php',{'boardCode':'1','list':'5'},function(data){
		var lists = ''
		$.each(data.board, function(){
			lists += '<tr>'
			lists += '<th onClick="top.location.href=\'/bbs/?boardCode=1&seq='+this.seq+'\'">'+this.subject+'</th>'
			lists += '<td>'+this.inputDate.substr(0,10)+'</td>'
			lists += '</tr>'
		})
		//alert(lists)
		$('.BBSArea tbody').html(lists)
	})
	.done(function(){
		newContents();
	})
}

function newContents(){
	
}