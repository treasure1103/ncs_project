<?php
/*
  payreq_crossplatform ���� ���ǿ� �����ߴ� �Ķ���� ���� ��ȿ���� üũ
  ���� ���� �ð�(�α��� �����ð�)�� ������ ���� �ϰų� ������ ������� �ʴ� ��� DBó�� �Ͻñ� �ٶ��ϴ�.
*/
  session_start();
  if(!isset($_SESSION['PAYREQ_MAP'])){
  	echo "������ ���� �Ǿ��ų� ��ȿ���� ���� ��û �Դϴ�.";
  	return;
  }
  $payReqMap = $_SESSION['PAYREQ_MAP'];//���� ��û��, Session�� �����ߴ� �Ķ���� MAP
?>
<html>
<head>
	<script type="text/javascript">
	
		function setLGDResult() {
			parent.payment_return();
			try {
			} catch (e) {
				alert(e.message);
			}
		}
		
	</script>
</head>
<body onload="setLGDResult()">
<?php
  $LGD_RESPCODE = $_POST['LGD_RESPCODE'];
  $LGD_RESPMSG 	= $_POST['LGD_RESPMSG'];
  $LGD_PAYKEY	  = "";

  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
  $payReqMap['LGD_RESPMSG']	=	$LGD_RESPMSG;

  if($LGD_RESPCODE == "0000"){
	  $LGD_PAYKEY = $_POST['LGD_PAYKEY'];
	  $payReqMap['LGD_PAYKEY'] = $LGD_PAYKEY;
  }
  else{
	  echo "LGD_RESPCODE:" + $LGD_RESPCODE + " ,LGD_RESPMSG:" + $LGD_RESPMSG; //���� ���п� ���� ó�� ���� �߰�
  }
?>
<form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO">
<?php
	  foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";
    }
?>
</form>
</body>
</html>