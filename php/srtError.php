<?php
const UPLOAD_ERR_MIME_TYPE = 9;

function srtError($srtFile) {
    if($srtFile["error"] === UPLOAD_ERR_NO_FILE) return $srtFile["error"];
    if($srtFile["type"] !== "application/x-subrip" || mime_content_type($srtFile["tmp_name"]) !== "text/plain") return UPLOAD_ERR_MIME_TYPE;
    if(strtolower(end(explode('.', $srtFile['name']))) !== "srt") return UPLOAD_ERR_MIME_TYPE;
    return $srtFile["error"];
}

function srtErrorMessage($srtError) {
    $srtErrors[UPLOAD_ERR_INI_SIZE] = 'UPLOAD_ERR_INI_SIZE';
    $srtErrors[UPLOAD_ERR_FORM_SIZE] = 'UPLOAD_ERR_FORM_SIZE';
    $srtErrors[UPLOAD_ERR_PARTIAL] = 'UPLOAD_ERR_PARTIAL';
    $srtErrors[UPLOAD_ERR_NO_FILE] = 'UPLOAD_ERR_NO_FILE';
    $srtErrors[UPLOAD_ERR_NO_TMP_DIR] = 'UPLOAD_ERR_NO_TMP_DIR';
    $srtErrors[UPLOAD_ERR_CANT_WRITE] = 'UPLOAD_ERR_CANT_WRITE';
    $srtErrors[UPLOAD_ERR_EXTENSION] = 'UPLOAD_ERR_EXTENSION';
    $srtErrors[UPLOAD_ERR_MIME_TYPE] = 'UPLOAD_ERR_MIME_TYPE';
    
    predie($srtErrors[$srtError]);
}