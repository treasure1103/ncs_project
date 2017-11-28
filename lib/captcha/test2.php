<?php
session_start();
$_SESSION = array();
include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
$PREV_Captcha = $_SESSION['captcha']['code'];
$POST_Captcha = $_POST['captcha'];

echo $PREV_Captcha;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Example &raquo; A simple PHP CAPTCHA script</title>
    <style type="text/css">
        img {
            border: solid 1px #ccc;
            margin: 0 2em;
        }
    </style>
</head>
<body>
    <p>
        <?php
        echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';
        ?>
    </p>
</body>
</html>