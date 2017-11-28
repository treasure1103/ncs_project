<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=640, user-scalable=yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<title>학습도움말</title>
<meta name="description" content="온라인 이러닝 교육, 고용보험 사업주 환급과정, 병원, 직무">
<link rel="stylesheet" href="../css/userStyle.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
	  window.resizeTo(668,640)
	  var imgMax = 11; //총이미지개수
	  var i=1;
	  var fileAddress = '../images/help_img/img_help';
	  
	  $('#helpDesk > div > button').bind({
		  click:function(){
			  var btnName=$(this).attr('class');
			  if(btnName == 'btnNext'){
				  if(i != imgMax ){
					  i++
					  if(i<=9){
						  i = '0'+i
					  }
					  $('#helpDesk > img:not(:animated)').fadeOut('fast',function(){
						  $('#helpDesk > img').attr('src',fileAddress+i+'.jpg');
						  $('#helpDesk > img').load(function(){
							  $('#helpDesk > img').fadeIn('fast');
						  });
					  })
					  $('#helpDesk > div > h2 > strong').html(i)
				  }else{
					  alert('마지막 이미지입니다.')
				  }		  
			  }else if(btnName == 'btnPrev'){
				  if(i != 1){
					  i--
					  if(i<=9){
						  i = '0'+i
					  }
					  $('#helpDesk > img:not(:animated)').fadeOut('fast',function(){
						  $('#helpDesk > img').attr('src',fileAddress+i+'.jpg');
						  $('#helpDesk > img').load(function(){
							  $('#helpDesk > img').fadeIn('fast');
						  });
					  })
					  $('#helpDesk > div > h2 > strong').html(i)
				  }else{
					  alert('첫번째 이미지입니다.')
				  }
			  }
		  }
	  })
  });
</script>
</head>

<body id="helpDesk">
<div>
  <button class="btnPrev" title="이전"><img src="../images/help_img/btn_left.png" alt="이전" /></button>
  <h1>학습도움말</h1>
  <h2>(<strong>1</strong>/11)</h2>
  <button class="btnNext" title="다음"><img src="../images/help_img/btn_right.png" alt="다음" /></button>
</div>
<img src="../images/help_img/img_help01.jpg" alt="도움말 이미지" />
</body>
</html>