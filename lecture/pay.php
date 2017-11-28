<? include '../include/header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>';
var seq = '<?=$_GET[seq]; ?>';
var sort01 = '<?=$_GET[sort01]; ?>';
var sort02 = '<?=$_GET[sort02]; ?>';
var contentsCode = '<?=$_GET[contentsCode]; ?>';
$(document).ready(function(){
	GNBAct('userGNB');  
});
</script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/userContents.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.payForm input[type="radio"]').click(function() {
      if($('#remit02').is(':checked')) {
        $('.payForm .BBSWrite').show();
      }else{
        $('.payForm .BBSWrite').hide();
      }
    });
  })
</script>
<script type="text/javascript">
function LPad(digit, size, attatch) {
    var add = "";
    digit = digit.toString();

    if (digit.length < size) {
        var len = size - digit.length;
        for (i = 0; i < len; i++) {
            add += attatch;
        }
    }
    return add + digit;
}

function makeoid() {
	var now = new Date();
	var years = now.getFullYear();
	var months = LPad(now.getMonth() + 1, 2, "0");
	var dates = LPad(now.getDate(), 2, "0");
	var hours = LPad(now.getHours(), 2, "0");
	var minutes = LPad(now.getMinutes(), 2, "0");
	var seconds = LPad(now.getSeconds(), 2, "0");
	var timeValue = years + months + dates + hours + minutes + seconds; 
	document.getElementById("LGD_OID").value = "test_" + timeValue;
	document.getElementById("LGD_TIMESTAMP").value = timeValue;
}

/*
* 인증요청 처리 
*/
function doPay() {
	// OID, TIMESTAMP 생성
	makeoid();
	// 결제창 호출
	document.getElementById("LGD_PAYINFO").submit();
}

function orderReg2(){
    var RRN01 = $('input[name="RRN01"]');
    var RRN02 = $('input[name="RRN02"]');
    var RRN = RRN01.val() + RRN02.val();
	var sendSerial = $('form.orderSubmit').serialize();

	if($('input[name="RRN01"]').val() == '' || $('input[name="RRN02"]').val() == ''){
		alert('주민등록번호를 입력해 주세요.');
		return;
	}
	if($('input[name="RRN01"]').val().length != 6 || $('input[name="RRN02"]').val().length != 7){
		alert('주민등록번호를 다시 입력해 주세요.');
		return;
	}

    //올바른 주민등록번호가 입력되는지 검사
    var total = 0;
    var cnt = 2;
    for (var i = 0; i < RRN.length - 1; i++) {
        if (cnt > 9) {
            cnt = 2;
        }
        total += parseInt(RRN.charAt(i)) * cnt;
        cnt++;
    }
    var check = (11 - (total % 11)) % 10;
    if(parseInt(check) != parseInt(RRN.charAt(12)))
    {
        alert("주민등록번호를 다시 입력해 주세요.");
        RRN01.value = "";
        RRN02.value = "";
        RRN01.focus();
        return false;
     }
    if(!$('.payForm input[type="radio"]').is(':checked') ){
		alert('결제방식을 선택해 주세요.');
		return;
	}

    if($('#remit02').is(':checked')) {
        if($('select[name="bank"]').val() == 'bank01'){
            alert('은행명을 선택해 주세요.');
            return;
        }
        if($('input[name="bankNum"]').val() == ''){
            alert('계좌번호를 입력해 주세요.');
            return;
        }
        if($('input[name="bankName"]').val() == ''){
            alert('입금자명 입력해 주세요.');
            return;
        }
    }
		doPay();
}
</script>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/study.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$siteName?><img src="../images/global/icon_triangle.png" alt="▶" />교육과정안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong>교육신청하기</strong></h2>
      <h1>교육신청하기</h1>
      <h3>내용을 잘 살펴보시고 신청하시기 바랍니다.</h3>
    </div>
    <!-- 동작호출부 -->
    <div class="pay">
      <h1>교육신청하기</h1>
			
			<form method="post" id="LGD_PAYINFO" action="../lib/payment/payreq_crossplatform.php">
				<input type="hidden" name="LGD_PRODUCTINFO" id="LGD_PRODUCTINFO" value="myLG070-인터넷전화기"/>
				<input type="hidden" name="LGD_AMOUNT" id="LGD_AMOUNT" value="50000"/>
				<input type="hidden" name="LGD_OID" id="LGD_OID" value="test_1234567890020"/>
				<input type="hidden" name="LGD_TIMESTAMP" id="LGD_TIMESTAMP" value="1234567890"/>
				<input type="hidden" name="LGD_TIMESTAMP" id="LGD_TIMESTAMP" value="1234567890"/>
				<input type="hidden" name="LGD_CUSTOM_USABLEPAY" id="LGD_CUSTOM_USABLEPAY" value="SC0010"/>
				<input type="hidden" name="LGD_WINDOW_TYPE" id="LGD_WINDOW_TYPE" value="iframe"/>
				<input type="hidden" name="LGD_BUYER" id="LGD_BUYER" value="홍길동"/>
				<input type="hidden" name="CST_MID" id="CST_MID" value="nayanet"/>
				<input type="hidden" name="CST_PLATFORM" id="CST_PLATFORM" value="test"/>
				<input type="hidden" name="LGD_BUYEREMAIL" id="LGD_BUYEREMAIL" value="ajjsss7899@naver.com"/>
				<input type="hidden" name="LGD_AMOUNT" id="LGD_AMOUNT" value="50000"/>
      <div class="BBSList">
        <table>
          <tr>
            <th>과정명</th>
            <th>기간</th>
            <th>교육비</th>
          </tr>
          <tr>
            <td>[의료인을 위한 맞춤 서비스 교육] 환자의 마음을 어루만지는 의료인을 위한 커뮤니케이션</td>
            <td>1개월</td>
            <td>0</td>
          </tr>
        </table>
      </div>
      <div class="BBSWrite">
        <ul>
          <li>
            <h1>수강기간</h1>
            2017-01-01 ~ 2017-01-31
          </li>
          <li>
            <h1>신청자</h1>
            김나영
          </li>
          <li>
            <h1>연락처</h1>
            010-9707-4622
          </li>
          <li>
            <h1>이메일</h1>
            ajjsss7899@naver.com
          </li>
          <li>
            <h1>주민등록번호</h1>
            <input class="name" type="tel" name="RRN01"> -
            <input class="name" type="password" name="RRN02">
            <div class="normalText" style="margin:10px 0;">
              신청하는 과정은 사업주지원훈련과정입니다. 근로자직업능력개발법 시행령 제52조의2(민감정보 및 고유식별정보의처리)에 의하여 주민번호 등을 처리할 수 있으며, 수집한 주민번호는 산업인력공단에 훈련 실시신고 후 바로 파기합니다.자세한 사업주지원훈련 정보는 <a href="http://esangedu.kr/eduinfo" target="_blank">이곳을 클릭</a>하여 확인하실 수 있습니다. 반드시 신청 정보를 확인 후 신청하시기 바랍니다.
            </div>
          </li>
        </ul>
      </div>
			</form>

      <form action="#" class="payForm">
        <span>결제방식 :</span>
        <input type="radio" name="pay" id="remit01"/><label for="remit01">신용카드</label>
        <input type="radio" name="pay" id="remit02"/><label for="remit02">무통장입금</label>
        <div class="BBSWrite">
          <ul>
            <li>
              <h1>은행선택</h1>
              <select name="bank">
                <option value="bank01">은행선택</option>
                <option value="bank02">국민은행</option>
              </select>
            </li>
            <li>
              <h1>계좌번호</h1>
              <input type="tel" name="bankNum"/>
            </li>
            <li>
              <h1>입금자명</h1>
              <input type="text" name="bankName" />
            </li>
          </ul>
        </div>
      </form>
      <div class="btnArea"><button type="button" onclick="orderReg2()">결제 및 신청하기</button></div>
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>