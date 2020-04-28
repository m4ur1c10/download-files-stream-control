<?php

header("content-type: text/plain");

require 'helper.php';

/* CONFIG WORMHOLE */

$config = json_decode(file_get_contents("config.json"));

$urlServer = $config->url;
//KB/S
$kb = (1024 * ($config->kbs * ($config->interval / 1000)));

/* FIM CONFIG */

$filesToDownload = json_decode(file_get_contents("files-download-list.json"));

$dirNameDownload = 'downloads';

foreach ($filesToDownload as $filename) {

    if (file_exists($dirNameDownload . DIRECTORY_SEPARATOR . $filename) && !$config->forceDownload) {
        echo "$filename already downloaded. ignoring...".PHP_EOL;
        continue;
    }

    //echo "Downloading $filename...".PHP_EOL;

    $dirName = $dirNameDownload . DIRECTORY_SEPARATOR . str_replace(".", "-", $filename);

    $filesize = file_get_contents($urlServer . 'getfilesize.php?filename='.$filename);

    $numRepDownload = strlen((string) round($filesize / $kb)); //to calculate how many files are be downloaded.

    if (is_dir($dirName)) {
        deleteTempFolder($dirName);
    }
    mkdir($dirName);

    $pos = dirSize($dirName);

    $numFiles = numFilesInDir($dirName);

    while ($pos < $filesize) {

        $context = stream_context_create([
            'http'=> [
                'method' => 'GET',
                'header'=> [
                	'filesize: '. $kb,
                	'pos: '.$pos
                ],
            ]
        ]);

        $url = $urlServer . 'index.php?'.http_build_query(
        	['filename' => $filename]
        );

        $resp = file_get_contents($url, false, $context);

        $numPos = round($pos / $kb);

        $afeFile = $dirName.DIRECTORY_SEPARATOR.$config->suffixTempFile.'-'.str_pad($numFiles, $numRepDownload, "0", STR_PAD_LEFT).'.'.$config->extTempFile;

        $handle = fopen($afeFile, 'w');
        fwrite($handle, $resp);

        fclose($handle);

        $pos = dirSize($dirName);

        $numFiles++;

        m_sleep($config->interval);
    }

    transferChunckToFile($dirName, $dirNameDownload . DIRECTORY_SEPARATOR . $filename);
    deleteTempFolder($dirName);

}