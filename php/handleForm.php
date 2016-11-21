<?php
function handleForm() {
    $srtfiles = reArrayFiles($_FILES['srtfiles']);
    foreach($srtfiles as $srtfile) {
        if($srtfile["error"] !== UPLOAD_ERR_OK) die("error on file upload.");
        if($srtfile["type"] !== "application/x-subrip" || mime_content_type($srtfile["tmp_name"]) !== "text/plain") die("not a srt or even a text file.");
        if(strtolower(end(explode('.', $srtfile['name']))) !== "srt") die ("no .srt extension.");
        move_uploaded_file($srtfile["tmp_name"], "uploads/".time()."_".uniqid()."_".$srtfile['name']);
    }
    
    foreach($_POST['timelines'] as $i => $timeline) {
        $sign = $timeline['sign'] === "+" ? 1 : -1;
        $sync = getMsFromTime($timeline['sync']) * $sign;
        $dura = getMsFromTime($timeline['dura']);
        
        if(($sync && $dura) == 0) break;
    }
}