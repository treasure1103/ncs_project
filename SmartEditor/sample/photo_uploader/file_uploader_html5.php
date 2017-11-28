<?php
 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	$file->name = str_replace("\0", "", rawurldecode($headers['file_name']));
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");
	
	$filename_ext = strtolower(array_pop(explode('.',$file->name)));
	$allow_file = array("jpg", "png", "bmp", "gif"); 
	
	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$file->name;
	} else {
		$uploadDir = '../../../../attach/naverEditor/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}
		
		$newPath = $uploadDir.$file->name;

		$nameOK=1;
		$i=1;
		$fileName = $file->name;
		while($nameOK > 0){
			if(file_Exists($newPath)) { // 같은 파일명이 존재한다면
				$uploadDate = date('i');
				$fileName = $uploadDate.$i."_".$file->name;
				$newPath = $uploadDir.$fileName;
				$i++;
			} else {
				$nameOK = 0;
			}
		}

		if(file_put_contents($newPath, $file->content)) {
			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=".$fileName;
			$sFileInfo .= "&sFileURL=/attach/naverEditor/".$fileName;
		}
		
		echo $sFileInfo;
	}
?>