<?php
$title = str_replace(" ", "", $title);
$params['title']=$title;
$params['id']=$id;
$img_folder ="{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$title."/images/";
$shortdir = "/assets/galleries/".$title."/images/";

$images = array();
if ( (is_dir($img_folder)) && ($files = @scandir($img_folder)) && (count($files) > 2) )  { 
	if ( $img_dir = @opendir($img_folder)) {  
		 while ( false !== ($img_file = readdir($img_dir)) ) {
			 // checks for gif, jpg, png
	         if ( preg_match("/(\.gif|\.jpg|\.png)$/", $img_file) ) {
	             $images[] = $img_file;
	         }
		}
	closedir($img_dir);
	}
 
     mt_srand( (double)microtime() * 1000000 );
     $num = array_rand($images);
     list($width, $height, $type, $attr) = getimagesize($img_folder.$images[$num]);
     $height = $height / 2;
     $width = $width / 2;
     echo "<img src='$shortdir$images[$num]' alt='$title' height='$height' width='$width' border='1' />";
}
?>