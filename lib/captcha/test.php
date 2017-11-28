<?php
session_start();
include("./simple-php-captcha.php");
$_SESSION = array();
$_SESSION['captcha'] = simple_php_captcha();

$NEW_Captcha = $_SESSION['captcha']['code'];
$PREV_Captcha = $_POST['PREV_Captcha'];
$POST_Captcha = $_POST['captcha'];
if(!empty($PREV_Captcha) && !empty($POST_Captcha)){
	if(strcasecmp($PREV_Captcha, $POST_Captcha) != 0){
		echo "<script>alert('이미지에 출력된 글자와 다릅니다.');</script>";
	} else {
		echo "<script>alert('정상');</script>";
	}
}
 

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta -->
    <meta name="Author" content="serpiko@hanmail.net" />
    <meta name="description" content="http://serpiko.tistory.com" />
 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
     
    <!-- link -->
    <link rel="stylesheet" type="text/css" href="" />
 
    <!-- script -->
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div id="wrap">
        <form method='post' action='<?=$PHP_SELF?>'>
            NEW_Captcha : <input type='text' size='100' value='<?=$NEW_Captcha?>' /> <br />
            PREV_Captcha : <input type='text' size='100' value='<?=$PREV_Captcha?>' /> <br />
            POST_Captcha : <input type='text' name='' size='100' value='<?=$POST_Captcha?>' /> <br /><br />
     
            할당된 캡챠<br />
            <img src="<?=$_SESSION['captcha']['image_src']?>" style='width:100px;border:1px solid blue;border-radius:7px;' /><br /><br />
                 
            사용자 캡챠 입력<br />
            captcha : <input type='text' size='100' name='captcha' value="" /> <br />
 
            <button type='submit'>확인</button>
<input type='hidden' size='100' name='PREV_Captcha' value="<?=$_SESSION['captcha']['code']?>" />
        </form>
    </div>
 
</body>
</html>