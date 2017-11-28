// 일반 form 등록 : sendData()
// 테이블 열 등록 : lineSendData(seq,등록/수정)
// 데이터 삭제    : deleteData(seq)
//
//	작성자 : 서영기

//공통선언 -  등록
function makeOption(type,subType,view, allType){
	//호출시 subType을 등록하면 호출 값도 서브타입으로 조회해야한다.
	//조회방식 optWrite['타입']
	//allType == 조건없이 전체 보이기
	var codes = '';
	if(subType != ''){
		codes = subType
	}else{
		codes= type
	}
	optWrite[codes] = '';//type별 배열선언
	
	var makeOpt = $.get('../api/apiCategory.php',{'value01':type,'value03':subType,'allType':allType},function(data){		
		$.each(data.category,function(){
			if(type !='userLevel'){
				optWrite[codes] += '<option value="'+ this.value01 +'">';
				if(view == 'both'){
					optWrite[codes] += this.value01 +'&nbsp;/&nbsp;'+ this.value03;
				}else{
					optWrite[codes] += this.value02;
				}
				optWrite[codes] += '</option>';
			}else{
				if(eval(this.value01) >= eval(loginUserLevel)){
					optWrite[codes] += '<option value="'+ this.value01 +'">';
					if(view == 'both'){
						optWrite[codes] += this.value01 +'&nbsp;/&nbsp;'+ this.value03;
					}else{
						optWrite[codes] += this.value02;
					}
					optWrite[codes] += '</option>';
				}
			}
		})
	})
	
}

function makeRadio(){
	
}

function makeCheckbox(){
}

function emailSelect(){
	$('select#email02').bind({
		change:function(){
			var inputVal = $(this).val()
			var inputs = $(this).attr('name')
			inputs = inputs.replace('Chk','')
			//$('input[name="'+inputs+'"]').val(inputVal)
			if(inputVal == ''){
				$('input[name="'+inputs+'"]').val('')
			}else{
				$('input[name="'+inputs+'"]').val(inputVal)
			}
		}
	})
}

function findOpt(){
	$('#contents, #modal').find('select').each(function(){
		var selOpt = $(this).attr('class');
			selOpt = selOpt ? selOpt : '';
			$(this).children('option').each(function() {
                if($(this).val() == selOpt){
				$(this).attr('selected','selected');
			}
        });
		$(this).removeAttr('class')
    });
}

function joinSelect(parentName,childrenName,childrenOpt,view){
	//view == both일때 카테고리 value와 값이 함께 호출
	var view = view ? view :'';
	var parentSelect = $('select[name="'+parentName+'"]');
	var childrenSelect = $('select[name="'+childrenName+'"]');
	
	parentSelect.bind({
		change:function(){
			var parentOpt = $('select[name="'+parentName+'"]').val();
			var changeOpt = '';
			var changeOpts = $.get('../api/apiCategory.php',{'value01':parentOpt},function(data){	
				if(parentOpt != ''){
					$.each(data.category,function(){
						changeOpt += '<option value="'+ this.value01 +'">';
						if(view == 'both'){
							changeOpt += this.value01 +'&nbsp;/&nbsp;'+ this.value02;
						}else{
							changeOpt += this.value02;
						}
						changeOpt += '</option>';
					})					
				}else{
					changeOpt += '<option value="">'+childrenOpt+'</option>';
				}
				childrenSelect.html(changeOpt)
			})
		}
	})
}

//해당 카테고리 변수에 대한 select 리턴
function returnSelect(viewCode,selValue,type){
	type= type?type:'';
	var selWrite = '<option value="">선택하세요</option>';
	for(var i=0 in categoryArr[viewCode]){
		var chkOpt = categoryArr[viewCode][i].split('#');
		selWrite += '<option value="'+chkOpt[0]+'"';
		if(selValue == chkOpt[0]){
			selWrite += ' selected="selected"';
		}
		selWrite += '>';
		if(type == 'both'){
			selWrite += chkOpt[0]+' | ';
		}
			selWrite += chkOpt[1];
		if(type == 'all'){
			selWrite += ' | '+chkOpt[2];
		}
		selWrite += '</option>';
	}
	return selWrite
}