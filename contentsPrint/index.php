<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=1280, user-scalable=yes">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<style type="text/css">
  /* 나눔고딕 */
  @import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
  /* font-family: 'Nanum Gothic', serif; */
  body { margin:0; padding:29pt; font-family: 'Nanum Gothic', serif; font-size:8pt; }
  body > h1 { margin:3pt 0 8pt; padding:0; font-size:14pt; font-weight:800; color:#0780c2; }
  body > h2 { margin:5pt 0 0; padding:0; font-size:11pt; font-weight:800; color:#034061; }
  
  strong.blue ,span.blue { color:#0780c2; }
  strong.red ,span.red { color:#c53030; }
  
  #summeryArea { overflow:hidden; float:left; width:23%; margin-right:2%; }
  #summeryArea > img, #summeryArea table, #ChapterArea table { width:100%; }
  #summeryArea > img { margin-bottom:5pt; }
  #summeryArea table { border-collapse:collapse; border:0.2pt solid #034061; }
  #summeryArea table th, #summeryArea table td { text-align:left; border:0.2pt solid #034061; padding:4pt; line-height:12pt; }
  #summeryArea table th { background:#68adde; color:#034061; width:50px;}
  #summeryArea table td.skyblue { background:#ddeaf3; width:80px;}
  #summeryArea > h1 { margin:8pt 0 3pt; padding:0 3pt; border-top:0.2pt solid #034061; border-bottom:0.2pt dashed #ccc; font-size:10pt; font-weight:800; color:#0780c2; line-height:24pt; }
  #summeryArea > h2 { margin:4pt 0; padding:0 0 0 5pt; border-left:2pt solid #0780c2; font-size:9pt; line-height:11pt; color:#034061; }
  #ChapterArea { overflow:hidden; width:75%; }
  #ChapterArea > div { overflow:hidden; height:1077pt; margin-bottom:61pt; border-bottom:0.5pt solid #ccc;  }
  #ChapterArea > div + div { height:501pt; padding-top:29pt; }
  #ChapterArea > div:last-child { padding-bottom:0; margin-bottom:0; }
  #ChapterArea table { border-collapse:collapse; border:0.2pt solid #999; }
  #ChapterArea table th, #ChapterArea table td { border:0.2pt solid #999; padding:2pt; font-size:3pt; line-height:9.7pt; }
  /*#ChapterArea table th, #ChapterArea table td { border:0.2pt solid #999; padding:2pt; font-size:3pt; line-height:8.9pt; }*/
  #ChapterArea table th { background:#ddeaf3; }
  #ChapterArea table td.center { text-align:center; }
  #ChapterArea table td { background:#fff; }
  #ChapterArea table .detialText { font-size:2pt; letter-spacing:-0.02em; }
</style>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  var contentCode = '<?=$_GET['code'] ?>'
  var contentsName = '<?=$_GET['name'] ?>'
  var contentsApi = '../api/apiContents.php'
  var chapterApi = '../api/apiChapter.php'
  var findValue = ''
  if(contentCode != ''){
	  findValue = 'contentsCode='+contentCode;
  }else{
	  findValue = 'searchType=contentsName&searchValue='+contentsName;
  }
  $.get(contentsApi,findValue,function(data){
	  if(data.totalCount == 0){
		  alert('검색어를 명확하게 입력해주세요')
	  }else{
		  var indexSel = data.contents[0]
		  $('body > h1').html(indexSel.contentsName);
		  var summeryHtml = ''
		  //교육과정이미지
		  summeryHtml += '<img src="'+data.previewImageURL+indexSel.previewImage+'" alt="'+indexSel.contentsName+'" />';
		  //교육과정 서머리
		  summeryHtml += '<table>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<th>교육기간</th>'
		  summeryHtml += '<td colspan="2"><strong>'+indexSel.chapter+'</storng>차시 / <strong>'+indexSel.contentsTime+'</storng>시간</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<th>학습대상</th>'
		  summeryHtml += '<td colspan="2">'+indexSel.target02+'</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<th rowspan="3">환급액</th>'
		  summeryHtml += '<td class="skyblue">우선지원</td>'
		  summeryHtml += '<td style="text-align:right;"><strong class="red">'+toPriceNum(indexSel.rPrice01)+'</storng>원</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<td class="skyblue">대규모<br />(1000인 미만)</td>'
		  summeryHtml += '<td style="text-align:right;"><strong class="red">'+toPriceNum(indexSel.rPrice02)+'</storng>원</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<td class="skyblue">대규모<br />(1000인 이상)</td>'
		  summeryHtml += '<td style="text-align:right;"><strong class="red">'+toPriceNum(indexSel.rPrice03)+'</storng>원</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '</table>'
		  summeryHtml += '<h1>수료기준 및 유의사항</h1>'
		  summeryHtml += '<h2>수료기준</h2>'
		  summeryHtml += '<table>'
		  summeryHtml += '<tr>'
		  summeryHtml += '<th>진도율</th>'
		  summeryHtml += '<td><strong>'+indexSel.passProgress+'%</strong>이상</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<th>평가과제</th>'
		  summeryHtml += '<td>';
		  summeryHtml += '총점 <strong>'+indexSel.passScore+'점</strong>이상'
		  summeryHtml += '<br /><span class="red">최종평가와 과제는<br />40점 미만 시 과락 적용</span>'
		  summeryHtml += '</td>'
		  summeryHtml += '</tr>'
		  summeryHtml += '<th>특이사항</th>'
		  if(indexSel.passReport == '0'){
			  summeryHtml += '<td>과제없음</td>'			  
		  }else{
			  summeryHtml += '<td>없음</td>'
		  }
		  summeryHtml += '</tr>'
		  summeryHtml += '</table>'
		  summeryHtml += '<h2>1일 최대 8차시까지 학습 가능</h2>'
		  summeryHtml += '<h2>설문 참여: 필수 응답</h2>'
		  $('#summeryArea').html(summeryHtml)
	  }
  })
  .done(function(data){
	  var chapterCode = data.contents[0].contentsCode;
	  $.get(chapterApi,{'contentsCode':chapterCode},function(data){
		  var totalCount = data.totalCount;
		  var chapterHtml = '';
		  var chapter = '';
		  
		  //table_top
		  var tableHead = '';
		  tableHead += '<div>';
		  tableHead += '<table>';
		  tableHead += '<colgroup>';
		  tableHead += '<col width="4%" />';
		  tableHead += '<col width="14%" />';
		  tableHead += '<col width="32%" />';
		  tableHead += '<col width="4%" />';
		  tableHead += '<col width="14%" />';
		  tableHead += '<col width="32%" />';
		  tableHead += '</colgroup>';
		  tableHead += '<tr>';
		  tableHead += '<th>차시</th>';
		  tableHead += '<th>차시명</th>';
		  tableHead += '<th>학습내용</th>';
		  tableHead += '<th>차시</th>';
		  tableHead += '<th>차시명</th>';
		  tableHead += '<th>학습내용</th>';
		  tableHead += '</tr>';
		  
		  //table_bottom
		  var tableFoot = '';
		  tableFoot += '</table>';
		  tableFoot += '</div>';		  
		  
		  var maxCount = 0;
		  var halfCount = 0;
		  
		  if(totalCount <= 22){
			  halfCount = Math.ceil(totalCount/2)
		  }else{
			  halfCount = 11
		  }
		  var page = Math.ceil(totalCount/22);
		  
		  var i=0;
		  var h=0;		  
		  for (i=0;i<page;i++){
			  chapterHtml += tableHead;
			  minCount = (i*22);
			  maxCount = ((i+1)*22);
			  if(maxCount >= totalCount){
				  maxCount = totalCount
			  }
			  if(i == (page-1)){
				  if(totalCount >= 23){
					  halfCount = Math.ceil((totalCount - minCount)/2);
				  }
			  }
			  for(h=minCount; h<maxCount;h++){
				  var targetCount = '';
				  if(halfCount == 1){
					  targetCount = minCount;
				  }else if(totalCount <= 22){
					  targetCount = halfCount
				  }else{
					  targetCount = maxCount-halfCount
				  }
				  if(h < targetCount){
					  var chater = ''
					  if(data.chapter[Number(h)].chapter >= 100){
						  chapter = '-'
					  }else{
						  chapter = eval(data.chapter[Number(h)].chapter);
					  }
					  chapterHtml += '<tr>'
					  chapterHtml += '<td class="center">'+chapter+'</td>'
					  chapterHtml += '<td>'+data.chapter[Number(h)].chapterName+'</td>'
					  chapterHtml += '<td class="detialText">'+data.chapter[Number(h)].content.replace(/\n/g,'<br />')+'</td>'
					  if(Number(h)+Number(halfCount) <= (totalCount-1)){
						  var plusChapter = '';
						  if(data.chapter[Number(h)+Number(halfCount)].chapter >= 100){
							  plusChapter = '-'
						  }else{
							  plusChapter = data.chapter[Number(h)+Number(halfCount)].chapter
						  }
						  chapterHtml += '<td class="center">'+plusChapter+'</td>'
						  chapterHtml += '<td>'+data.chapter[Number(h)+Number(halfCount)].chapterName+'</td>'
						  chapterHtml += '<td class="detialText">'+data.chapter[Number(h)+Number(halfCount)].content.replace(/\n/g,'<br />')+'</td>'
					  }else{
						  chapterHtml += '<td class="center" colspan="3">-</td>'
					  }
					  chapterHtml += '</tr>'
				  }
			  }
			  chapterHtml += tableFoot;
		  }
		  $('#ChapterArea').append(chapterHtml)
	  })	  
  })
  //가격형태 콤마 변환,복귀
  function toPriceNum(x) {
	  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }
</script>
</head>
<body>
<h1></h1>
<div id="summeryArea"></div>
<div id="ChapterArea"></div>
</body>