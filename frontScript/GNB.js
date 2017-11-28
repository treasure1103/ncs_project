//	관리자 UI 퍼포먼스, 레이아웃 스크립트
//	작성자 : 서영기
//
var GNBApi = '../api/apiCategory.php'

function GNBAct(type){
	var GNBS = '';
	if(type=="adminGNB"){
		var GNBSelect = locaSel.substr(0,2);
		var SubmenuSelect = locaSel.substr(2,2);
		//메인메뉴 출력
		var GNBAction = $.get(GNBApi,{'value01':type},function(data){			
			$.each(data.category, function(){
				var GNBSel = this.value01.substr(0,2);
				var GNBFirstPermit = Number(this.value01.substr(2,1));
				var GNBLastPermit = Number(this.value01.substr(3,1));
				if(this.enabled != 'N' && GNBFirstPermit <= loginUserLevel && GNBLastPermit >= loginUserLevel){
					GNBS +='<li id="'+this.seq+'" onClick="top.location.href=\''+this.value03+'\'"'
					if(GNBSel == GNBSelect){
						GNBS += 'class="select"';
					}
					GNBS +='>';
					GNBS += this.value02;
					GNBS += '</li>';
				}
			})
			$('.apiGNB').html(GNBS);
		})
		.done(function(){
			var subPrint = $('.apiGNB li.select').attr('id');
			//서브메뉴 출력
			var subMenuAction = $.get(GNBApi,{'seq':subPrint},function(data){
				var subMenus = '';
				$.each(data.category,function(){
					var subSel = this.value01.substr(0,2);
					var subFirstPermit = Number(this.value01.substr(2,1));
					var subLastPermit = Number(this.value01.substr(3,1));
					if(this.enabled != 'N' && subFirstPermit <= loginUserLevel &&  subLastPermit >= loginUserLevel ){
						subMenus += '<li id="'+this.seq+'" onClick="top.location.href=\''+this.value03+'\'"';
						if(subSel == SubmenuSelect){
							subMenus += 'class="select"';
						}
						subMenus += '>';
						subMenus += this.value02;
						subMenus += '</li>';
					}					
				})
				$('.apiSubMenu').html(subMenus);
				var pageTitleSeq = $('.apiSubMenu li.select').attr('id')
				var pageTitleAction = $.get(GNBApi,{'seq':pageTitleSeq},function(data){
					var pageTitle = '';
					if(data.totalCount != 0){
						$.each(data.category,function(){
							if(this.value01 == 'title'){
								pageTitle += this.value02 + '<span>' + this.value03  + '</span>';
							}
						})
						$('#contents > h1').html(pageTitle)
					}
				})
			})
		})
		.always(function(){
			pageMode == 'adminPage';
			if(typeof page != 'undefined' && typeof seq != 'undefined' ){
				if(seq != ''){
					viewAct(seq)
				}else{
					listAct(page)
				}
			}else{
				pageAct()
			}
		})
	}if(type=="userGNB"){
		pageMode == 'userPage';
		/*
		var currentPage = document.location.href;
		currentPage = currentPage.slice(7);
		var filearr = currentPage.split("/");
		var selectFile = filearr.length -1;
		var selectFolder = filearr.length - 2;
		var currentFile = filearr[selectFile];
		var currentFolder = filearr[selectFolder];
		*/
		if(seq != ''){
			viewAct(seq)
		}else{
			listAct(page)
		}
	}
}