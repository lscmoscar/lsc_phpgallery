<?php

$dlfolder="{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$title."/downloads/";
$thumbsfolder="{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$title."/thumbs/";
$imagesfolder="{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$title."/images/";

$pics=directory($dlfolder,"jpg,JPG,JPEG,jpeg,png,PNG");
if (is_array($pics))
{
	foreach ($pics as $p)	{
    	if (!file_exists($thumbsfolder.$p)) {
       		createimage($dlfolder.$p,$thumbsfolder.$p,75,75);
            }
        if (!file_exists($imagesfolder.$p)) {
            createimage($dlfolder.$p,$imagesfolder.$p,400,400);
                 }                                                            
			}
}

function ditchtn($arr,$thumbname) {
	foreach ($arr as $item) {
	        if (!preg_match("/^".$thumbname."/",$item)){$tmparr[]=$item;}
	        }
	        return $tmparr;
}
        

function createimage($name,$filename,$new_w,$new_h)
{
        $system=explode(".",$name);
        if (preg_match("/jpg|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}
        if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
        if (preg_match("/JPG|JPEG/",$system1)){$src_img=imagecreatefromjpeg($name);}
        if (preg_match("/PNG/",$system1)){$src_img=imagecreatefrompng($name);}
        list($old_x, $old_y, $type, $attr) = getimagesize($name);                    
        //compare aspect ratios
        if ($old_x/$old_y > $new_w/$new_h) {
        $thumb_w=$new_w;
        $thumb_h=$new_w*$old_y/$old_x;
        }
        else {
        $thumb_h = $new_h;
        $thumb_w=$new_h*$old_x/$old_y;
        }
        
        $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
                                                        
if (preg_match("/png/",$system[1])) {
        imagepng($dst_img,$filename); 
        } 
else {
       	imagejpeg($dst_img,$filename); 
      	}

          imagedestroy($dst_img); 
          imagedestroy($src_img); 
}




function directory($dir,$filters) {
	$handle=opendir($dir);
    $files=array();
    if ($filters == "all"){while(($file = readdir($handle))!==false){$files[] = $file;}}
    if ($filters != "all") {
        $filters=explode(",",$filters);
        while (($file = readdir($handle))!==false) {
        	for ($f=0;$f<sizeof($filters);$f++):
            	$system=explode(".",$file);
            	if ($system[1] == $filters[$f]){$files[] = $file;}
            endfor;
             }
       	}
 	closedir($handle);
 	return $files;
}
?>