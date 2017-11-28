//보드 정보 선언
var sortData = '';
var useApi = '../api/apiContentSel.php';
var boardApi = '../api/apiBoard.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = listCount ? listCount :10;
var pagerCount = 10; //페이저카운트


function listAct(){
	//상단 액션 부분
	var actionArea = '';
	actionArea += '<h1>수강후기 통계</h1>';
	actionArea += '<div class="searchArea"><form class="searchForm">';
	actionArea += '<input type="hidden" name="locaSel" value="1104" />';
	actionArea += '<select name="contentSel" onchange="excelAct()">';
    actionArea += '</select>';
	//actionArea += '<button type="excelAct()">통계 그래프 보기</button>';
	actionArea += '</form></div>';
	var chartArea = '<div id="barchart01" style="margin-left:20%;width:1000px;height:500px;"></div>';

	$('#contents > h1').after(actionArea);
	$('#contentsArea').html(chartArea);
	companyPrint();
	if(contentSel != ''){
		printChart(s1,s2,s3,s4,s5)
	}
}

function companyPrint(){
	$.get(useApi,function(data){
		var optWrite = '<option value="">기관을 선택해주세요</option>';
		$.each(data.contentSel, function(){
			optWrite += '<option value="'+this.contentsCode+'"';
			//console.log(this.contentsCode +'/'+ contentSel)
			if(this.contentsCode == contentSel){
				optWrite += ' selected="selected"'
			}
			optWrite += '>'+this.contentsCode+' | '+this.contentsName+'</option>';
		})
		$('.searchArea select[name="contentSel"]').html(optWrite);
	})
}

function excelAct(){
	searchValue = $('.searchForm').serialize();
	//document.searchForm.target = "ifrm";
	//document.searchForm.action = "studyChart.php?"+searchValue;
	//document.searchForm.submit();
	location.href="../admin/09_study_stats.php?"+searchValue;
}

function printChart(s1,s2,s3,s4,s5){
	loadingAct();
	if(s1==0 && s2==0 && s3==0 && s4==0 && s5==0 ){
		alert('수집된 데이터가 없습니다.')
	}else{
		//혼합차트 데이터
		s1 = [s1];
		s2 = [s2];
		s3 = [s3];
		s4 = [s4];
		s5 = [s5];

		var ticks = [['1점',s1], ['2점',s2], ['3점',s3], ['4점',s4] , ['5점',s5]];
		//바챠트1
		var mixTicks = ['과목'];

		var plot2 = $.jqplot('barchart01', [ticks], {
			//title:'과목',
			seriesDefaults:{
				renderer:$.jqplot.BarRenderer,
				rendererOptions:{
					varyBarColor:true,
					barWidth:100
				},
				pointLabels: {
					 show: true
				}
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
				}
			}
		});

		var lists = '';
		var listAjax = $.get(boardApi,'boardCode=3&addItem01='+contentSel+'&page='+page+'&list='+listCount,function(data){
		totalCount = data.totalCount;
		var i = 1
		if(page == 1){
			i = 1
		}else{
			i = page * listCount + 1
		}
		limit = (page - 1) * listCount;
		i = data.totalCount - limit; //게시글 번호 역순

		lists += '<div class="BBSList" style="width:1000px; margin:0 auto;">';
		lists += '<ul class="reviewList">';
		lists += '<li class="reviewTitle">';
		lists += '<h1 style="width:5.5%">번호</h1>'
		lists += '<h1 style="width:15%">별점</h1>'
		lists += '<h1>과정명 / 내용</h1>'
		lists += '</li>';
		if(data.totalCount != 0 ){
			$.each(data.board, function(){
				lists += '<li>';
				lists += '<h1>'+i+'</h1>';
				lists += '<h2 class="scroe'+this.addItem02+'">[ <strong>'+this.addItem02+'</strong>/5점 ]</h2>';
				lists += '<div><p>'+this.content+'</p></div>';
				lists += '</li>';
				i--
			});
		}else{
			lists += '<li class="noList">게시글이 없습니다.</li>'
		}
		lists += '</ul></div>';
		$('#contentsArea').after(lists)
		//pagerAct();
		loadingAct();
		})
	}
}