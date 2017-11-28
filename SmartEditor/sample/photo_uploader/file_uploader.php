<?php
// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif");
	
	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	} else {
		$uploadDir = '../../../../attach/naverEditor/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}
		
		$newPath = $uploadDir.$_FILES['Filedata']['name'];
		$nameOK=1;
		$i=1;
		$fileName = $_FILES['Filedata']['name'];
		while($nameOK > 0){
			if(file_Exists($newPath)) { // 같은 파일명이 존재한다면
				$uploadDate = date('i');
				$fileName = $uploadDate.$i."_".$_FILES['Filedata']['name'];
				$newPath = $uploadDir.$fileName;
				$i++;
			} else {
				$nameOK = 0;
			}
		}

		@move_uploaded_file($tmp_name, $newPath);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".$fileName;
		$url .= "&sFileURL=/attach/naverEditor/".$fileName;
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}
	
header('Location: '. $url);
?>