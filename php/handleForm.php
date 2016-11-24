<?php

function handleForm() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) srtErrorMessage(UPLOAD_ERR_INI_SIZE); // catches line 0 file upload errors

        $srtFiles = reArrayFiles($_FILES['srtfiles']);
        foreach($srtFiles as $srtFile) {
            $srtError = srtError($srtFile);
            if($srtError !== 0) srtErrorMessage($srtError);
            
            $srtFileArray = srtToArray(file_get_contents($srtFile["tmp_name"]));
            dump(arrayToSrt($srtFileArray));
            foreach($srtFileArray as $srtBlock) {
                // dump($srtBlock);
            }
        
            foreach($_POST['timelines'] as $i => $timeline) {
                $sign = $timeline['sign'] === "+" ? 1 : -1;
                $sync = getMsFromTime($timeline['sync']) * $sign;
                $dura = getMsFromTime($timeline['dura']);
                
                if(($sync && $dura) == 0) continue;
            }
        }
    }
}