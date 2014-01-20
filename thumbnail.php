<?php

	$u = urldecode($_GET['url']);
	$usr = urldecode($_GET['user']);  
	$pw = urldecode($_GET['password']);
	$desired_width = urldecode($_GET['desired_width']);
	$file_extension = urldecode($_GET['file_extension']);
	$context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode("$usr:$pw"))));
	$image = file_get_contents('https://api-stage.submittable.com'.$u, false, $context);
	$im = imagecreatefromstring($image);
	if ($im !== false) {
		if ($desired_width !== "") {
			$width = imagesx($im);
			$height = imagesy($im);
			$desired_height = floor($height * ($desired_width / $width));
			$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
			imagecopyresampled($virtual_image, $im, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
			if ($file_extension == "jpg") {
				header('Content-type: image/jpeg');
				imagejpeg($virtual_image);
			}elseif ($file_extension == "gif") {
				header('Content-type: image/gif');
				imagegif($virtual_image);
			}else{
				header('Content-type: image/png');
				imagepng($virtual_image);
			}
			imagedestroy($virtual_image);
			imagedestroy($im);
		}else{
			if ($file_extension == "jpg") {
				header('Content-type: image/jpeg');
				imagejpeg($im);
			}elseif ($file_extension == "gif") {
				header('Content-type: image/gif');
				imagegif($im);
			}else{
				header('Content-type: image/png');
				imagepng($im);
			}
			imagedestroy($im);
		}
	}	

?>
