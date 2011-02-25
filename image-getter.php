<?php
$title = str_replace(" ", "", $title);
$params['title']=$title;

$modx->runSnippet('thumbnail-gen',$params); //thumbnail gen file --> MODx call

$aFile = "{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$title."/sizes.txt";
$fh = fopen($aFile, 'w');

if (!function_exists('mime_content_type'))
{
    function mime_content_type($file, $method = 0)
    {
        if ($method == 0)
        {
            ob_start();
            system('/usr/bin/file -i -b ' . realpath($file));
            $type = ob_get_clean();
            $parts = explode(';', $type);
            return trim($parts[0]);
        }
    }
}

function sorter($a, $b) { 
	return strnatcmp($a['file'], $b['file']); 
} 

function listsize($thefile,$sizearray) {
	fwrite($thefile, $sizearray);
}

function iif($condition, $true_value, $false_value)
{
	return(($condition == $true_value) ? $true_value : $false_value);
}

function texter($galtitle) {
$parts = array();
$dscript_file = "{$_SERVER['DOCUMENT_ROOT']}/assets/galleries/".$galtitle."/description/".$galtitle.".txt";

	if (file_exists($dscript_file)) {
		$file_handle = file_get_contents($dscript_file, "r");
	}

	$parts = explode("\n", $file_handle);
	return $parts;
}

#image types to display
function getImages($hello) {

	$imagetypes = array("image/jpg","image/jpeg","image/png");
	$dir = "/assets/galleries/".$hello."/images/";
	$dir2 = "/assets/galleries/".$hello."/thumbs/";
	$dir3 = "/assets/galleries/".$hello."/downloads/";

	#array to hold return value
	$retval = array();

	#full server path to directory
	$fulldir = "{$_SERVER['DOCUMENT_ROOT']}$dir2"; 
	$idir = "{$_SERVER['DOCUMENT_ROOT']}$dir"; 

	$d = @dir($fulldir) or die("getImages: Failed opening directory $dir for reading"); 
	while(false !== ($entry = $d->read())) { 
		# skip hidden files 
		if($entry[0] == ".") continue; 

		# check for image files 
		if(in_array(mime_content_type("$fulldir$entry"), $imagetypes)) { 
			$retval[] = array( "file" => "$dir$entry", 
			"size" => getimagesize("$fulldir$entry"),
			"name" => "$entry",
			"thumb"=>"$dir2$entry",
			"download"=>"$dir3$entry",
			"isize" => getimagesize("$idir$entry")
			); 
		}
	}
	$d->close(); 
	return $retval;
}


$words = texter($title);
$wcount = 0;

$images = getImages($title);
# sort alphabetically by image name
usort($images, 'sorter');
foreach($images as $img) {

	$margAdd = $img[isize][1] + 34;

	echo "<li>\n";
	echo "<a class=\"thumb\" name=\"{$img['name']}\" href=\"http://www.lsc.org{$img['file']}\" title=\"{$img['name']}\">\n"; 
	echo "<img src=\"{$img['thumb']}\" alt=\"{$img['name']}\"/>\n"; 
	echo "</a>\n";
	echo "<div style=\"margin-top:{$margAdd}px;\" class=\"caption\">\n";
	echo "<div class=\"image-title\">$img[name]</div>\n";
	echo "<div class=\"image-desc\">$words[$wcount]</div>\n";
	echo "</div></li>\n";
	$wcount++;
	$sizedata = "{$img[isize][3]}\n";
	listsize($fh,$sizedata);
}
fclose($fh);
?>