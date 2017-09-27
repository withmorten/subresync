<?php
const UPLOAD_ERR_MIME_TYPE = 9;

function srtError($srtFile) {
    if($srtFile["error"] === UPLOAD_ERR_NO_FILE)    return $srtFile["error"];
    if($srtFile["type"] !== "application/x-subrip") return UPLOAD_ERR_MIME_TYPE;
    if(pathinfo($srtFile['name'], 4) !== "srt")     return UPLOAD_ERR_MIME_TYPE;
    return $srtFile["error"];
}

function srtErrorMessage($srtError) {
    $srtErrors[UPLOAD_ERR_INI_SIZE]   = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
    $srtErrors[UPLOAD_ERR_FORM_SIZE]  = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
    $srtErrors[UPLOAD_ERR_PARTIAL]    = 'The uploaded file was only partially uploaded';
    $srtErrors[UPLOAD_ERR_NO_FILE]    = 'No file was uploaded';
    $srtErrors[UPLOAD_ERR_NO_TMP_DIR] = 'Missing a temporary folder';
    $srtErrors[UPLOAD_ERR_CANT_WRITE] = 'Failed to write file to disk';
    $srtErrors[UPLOAD_ERR_EXTENSION]  = 'File upload stopped by extension';
    $srtErrors[UPLOAD_ERR_MIME_TYPE]  = 'The uploaded file is not a SubRip/srt file';

    pre($srtErrors[$srtError]);
}