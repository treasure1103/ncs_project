<?php
    /*
     * [������� ��û ������]
     *
     * LG���÷������� ���� �������� �ŷ���ȣ(LGD_TID)�� ������ ��� ��û�� �մϴ�.(�Ķ���� ���޽� POST�� ����ϼ���)
     * (���ν� LG���÷������� ���� �������� PAYKEY�� ȥ������ ������.)
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//LG���÷��� ���� ���� ����(test:�׽�Ʈ, service:����)
    $CST_MID                    = $_POST["CST_MID"];							//�������̵�(LG���÷������� ���� �߱޹����� �������̵� �Է��ϼ���)
																				//�׽�Ʈ ���̵�� 't'�� �ݵ�� �����ϰ� �Է��ϼ���.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //�������̵�(�ڵ�����)    
    $LGD_TID                	= $_POST["LGD_TID"];							//LG���÷������� ���� �������� �ŷ���ȣ(LGD_TID)
    
 	$configPath 				= "C:/lgdacom"; 								//LG���÷������� ������ ȯ������("/conf/lgdacom.conf") ��ġ ����.   
    
    require_once("./lgdacom/XPayClient.php");
    $xpay = &new XPayClient($configPath, $CST_PLATFORM);
    
    if (!$xpay->Init_TX($LGD_MID)) {
    	echo "LG���÷������� ������ ȯ�������� ���������� ��ġ �Ǿ����� Ȯ���Ͻñ� �ٶ��ϴ�.<br/>";
    	echo "mall.conf���� Mert Id = Mert Key �� �ݵ�� ��ϵǾ� �־�� �մϴ�.<br/><br/>";
    	echo "������ȭ LG���÷��� 1544-7772<br/>";
    	exit;
    }

    $xpay->Set("LGD_TXNAME", "Cancel");
    $xpay->Set("LGD_TID", $LGD_TID);
    
    /*
     * 1. ������� ��û ���ó��
     *
     * ��Ұ�� ���� �Ķ���ʹ� �����޴����� �����Ͻñ� �ٶ��ϴ�.
     */
    if ($xpay->TX()) {
        //1)������Ұ�� ȭ��ó��(����,���� ��� ó���� �Ͻñ� �ٶ��ϴ�.)
        echo "���� ��ҿ�û�� �Ϸ�Ǿ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }else {
        //2)API ��û ���� ȭ��ó��
        echo "���� ��ҿ�û�� �����Ͽ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }
?>
