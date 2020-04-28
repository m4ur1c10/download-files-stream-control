<?php

function getMimeType($filename) {
    $filename = realpath($filename);

    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    $ctype="application/force-download";

    switch ($file_extension) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpe": case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        default: $ctype="application/force-download";
    }

    return $ctype;
}

$headers = getallheaders();

if (!isset($headers['pos']) || !isset($headers['filesize']) || !$_GET['filename']) {
	http_send_status(500);
	die('params not found!');
}

$filepath = 'imgs'.DIRECTORY_SEPARATOR.$_GET['filename'];
$filesize = $headers['filesize'];
$pos = $headers['pos'];

$stream = fopen($filepath, 'r');

if (!$stream) {
	die("cannot open: " . $filepath);
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: ".getMimeType($filepath));
header("Content-Disposition: attachment; filename=\"".basename($filepath)."\";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".@filesize($filepath));
set_time_limit(0);

echo stream_get_contents($stream, $filesize, $pos);
fclose($stream);