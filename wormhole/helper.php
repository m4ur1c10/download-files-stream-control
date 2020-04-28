<?php

function m_sleep($milliseconds) {
  return usleep($milliseconds * 1000); // Microseconds->milliseconds
}

function dirSize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
        $size+=$file->getSize();
    }
    return $size;
}

function numFilesInDir($directory) {
    $num = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
        $num++;
    }
    return $num;
}

function deleteTempFolder($dirName) {
    //DELTE TEMP FOLDER
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirName, RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dirName);
}

function transferChunckToFile($dirName, $filename) {
    //CREATE IF NOT EXISTS, IF EXISTS, EMPTY THE FILE.
    $newFile = fopen($filename, 'w');

    if (!$newFile) {
        echo "cannot open file: $filename".PHP_EOL;
        exit;
    }
    //FILL NEW OBJECT
    $files = array();
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirName, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
        $files[] = $file->getFilename();
    }

    foreach ($files as $file) {
        $stream = fopen($dirName . DIRECTORY_SEPARATOR . $file, 'r');
        //echo "Reading " . $file.PHP_EOL;
        fwrite($newFile, fread($stream, filesize($dirName . DIRECTORY_SEPARATOR . $file)));
        fclose($stream);
    }
    fclose($newFile);
}