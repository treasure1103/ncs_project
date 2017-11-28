var nowSort =''; //현재 과정 검색
var allSort =''; // 전체 과정 검색
var enabled = ''; // 과정 노출여부

function listAct(){
    loadingAct();
    var list ='';
    $.get('../api/apiContentsMapping.php','companyID='+companyID+nowSort,function(data){
        var totalCount = data.totalCount
        list +='<div class="nowMap" onclick="selectMap(this)">';
        list +='<h1>현재 등록된 과정</h1>';
        list +='<form class="searchForm" action="javascript:nowSearch()">';
        list +='<input type="text" name="contentsName">';
        list +='<button type="submit"></button>';
        list +='<button type="button" onclick="btnClear(this)"></button>';
        list +='</form>';
        list +='<form>';
        list +='<input type="hidden" name="companyID" value="'+companyID+'">';
        list +='<input type="hidden" name="del" value="Y">';
        list +='<ul>';
        if (totalCount != 0){
            $.each(data.contentsMapping, function(){
                list +='<li>';
                list +='<button type="button" class="fRight" onclick="mapping(this,\''+this.contentsCode+'\',\'del\')"></button>';
                if (this.sort01Name == null || this.sort02Name == null){
					if(this.sort01 == null || this.sort01 == ''){
						var sort01 = "미등록";
					} else {
						var sort01 = this.sort01;
					}
					if(this.sort02 == null || this.sort02 == ''){
						var sort02 = "미등록";
					}else {
						var sort02 = this.sort02;
					}
                    list +='<h2>'+sort01+' > '+sort02+'</h2>';
                }else{
                    list +='<h2>'+this.sort01Name+' > '+this.sort02Name+'</h2>';
                }
				if(this.enabled == 'N'){					
					enabled = "미노출";
				}else {
					enabled = "노출";
				}
               list +='<h1>'+this.contentsName+' ('+this.contentsCode+') - '+enabled+'</h1>';
                list +='</li>';
            })
        }else{
            list += '<li>현재 등록된 과정이 없습니다.</li>'
        }
        list +='</ul>';
        list +='</form>';
        list +='</div>';    
    }).done(function(){
        $.get('../api/apiContents.php','list=999&mapping=Y'+allSort, function(data){
            list +='<div class="allMap" onclick="selectMap(this)">';
            list +='<h1>전체 과정</h1>';
            list +='<form class="searchForm" action="javascript:allSearch()">';
            list +='<input type="text" name="contentsName">';
            list +='<button type="submit"></button>';
            list +='<button type="button" onclick="btnClear(this)"></button>';
            list +='</form>';
            list +='<form>';
            list +='<input type="hidden" name="companyID" value="'+companyID+'">';
            list +='<input type="hidden" name="add" value="Y">';
            list +='<ul>';
            var totalCount = data.totalCount
            if (totalCount != 0){
                $.each(data.contents, function(){
                    list +='<li onclick="allChk(this,\''+this.contentsCode+'\')">';
                    if (this.sort01Name == null || this.sort02Name == null){
                       if(this.sort01 == null || this.sort01 == ''){
							var sort01 = "미등록";
						} else {
							var sort01 = this.sort01;
						}
						if(this.sort02 == null || this.sort02 == ''){
							var sort02 = "미등록";
						}else {
							var sort02 = this.sort02;
						}
						list +='<h2>'+sort01+' > '+sort02+'</h2>';
                    }else{
                        list +='<h2>'+this.sort01Name+' > '+this.sort02Name+'</h2>';
                    }
					if(this.enabled == 'N'){
						enabled = "미노출";
					}else {
						enabled = "노출";
					}
                    list +='<h1>'+this.contentsName+' ('+this.contentsCode+') - '+enabled+'</h1>';
                    list +='</li>';
                })
            }else{
                list += '<li>현재 등록된 과정이 없습니다.</li>'    
            }
            list +='</ul>';
            list +='</form>';
            list +='</div>';
         $('#contentsArea').html(list);
         loadingAct();
        })    
    })
    

}

//전체 과정 목록 선택
function allChk(obj,contentsCode){
    $(obj).closest('ul').find('button').remove();
    $(obj).prepend('<button type="button" onclick="mapping(this,\''+contentsCode+'\')" class="fLeft"></button>')
    $(obj).addClass('select').siblings().removeClass('select')
}

//과정 등록 및 취소
function mapping(obj,contentsCode,type){
    $(obj).closest('form').prepend('<input type="hidden" name="contentsCode" value="'+contentsCode+'">');
    var sendSerial = $(obj).closest('form').serialize();
    if (type == 'del'){
        var text = '삭제'
    }else{
        var text = '등록'
    }
	if(confirm(""+text+"하시겠습니까?")){
		$.ajax({
			url: '../api/apiContentsMapping.php',
			type:'POST',
			data:sendSerial,
			dataType:'text',
			success:function(data){
				alert(''+text+'되었습니다.');
                listAct()
			},
			fail:function(){
				alert(''+text+'에 실패하였습니다.')
			}
		})
	}
}

//과정 검색
function nowSearch(){
	searchValue = $('.nowMap .searchForm').serialize();
	searchValue = '&'+searchValue;
	nowSort = searchValue;
    listAct();
}

function allSearch(){
    searchValue = $('.allMap .searchForm').serialize();
    searchValue = '&'+searchValue;
    allSort = searchValue;
    listAct();
}

//검색 초기화
function btnClear(obj){
  if($(obj).closest('div').hasClass('nowMap')){
    nowSort = '';
    listAct();
  }else{
    allSort = '';
    listAct();
  }
}

//검색 선택 표시
function selectMap(obj){
    if (!$(obj).hasClass('select')){
        $(obj).addClass('select').siblings().removeClass('select')
    }
}

//로딩중
function loadingAct(){
	var loadingScreen = '<div class="loadingScreen"><img src="../images/global/loading.gif" alt="loading"></div>';
	if($('body').find('.loadingScreen').length == 0){
		$('body').append(loadingScreen);
	}else{
		$('.loadingScreen').fadeOut('fast',function(){
			$(this).remove();
		});
	}
}