<?php
function handleForm() {
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) srtErrorMessage(UPLOAD_ERR_INI_SIZE); // catches line 0 file upload errors
    
    if(($timeLineBlocks = json_decode($_POST["jsonarea"], TRUE)) == NULL) {
        $timeLines = timeLinesToMs($_POST['timelines']);
        $timeLineCount = count($timeLines);
        $timeLineBlocks = array();
        
        $c = 0;
        foreach($timeLines as $timeLineNum => $timeLine) {
            if($timeLineNum === 1 && $timeLine['sync'] !== 0) {
                $timeLineBlocks[] = array(
                                        'from' => SRT_TIME_MAX - $timeLine['dura'],
                                        'sync' => $timeLine['sync']
                                    );
            } else if(($timeLine['sync'] && $timeLine['dura']) != 0) {
                $timeLineBlocks[] = array(
                                        'from' => $timeLine['dura'] + $timeLineBlocks[$c-1]['from'] + $timeLineBlocks[$c-1]['sync'],
                                        'sync' => $timeLine['sync']
                                    );
            }
            $c++;
        }
    }
    
    if(count($timeLineBlocks) === 0) pre("No timelines containing useful information found!");
    else $GLOBALS["timeLineBlocksJson"] = json_encode($timeLineBlocks);

    $srtFiles = reArrayFiles($_FILES['srtfiles']);
    
    foreach($srtFiles as $srtFile) {
        $srtError = srtError($srtFile);
        if($srtError !== 0) srtErrorMessage($srtError);
        else {
            $srtFileArrayMs = srtFileArrayToMs(srtToArray(file_get_contents($srtFile["tmp_name"])));
            
            $newSrtFileArrayMs = array();
            
            foreach($timeLineBlocks as $timeLineBlock) {
                $c = 1;
                foreach($srtFileArrayMs as $srtNum => $srtBlockMs) {
                    if($srtBlockMs["Start"] >= $timeLineBlock["from"]) {
                        $newStart = z($srtBlockMs["Start"] + $timeLineBlock["sync"]);
                        $newStop  = z($srtBlockMs["Stop"]  + $timeLineBlock["sync"]);
                        
                        if(($newStop - $newStart) === 0) {   // delete srtBlock because it's in negative space
                            unset($srtFileArrayMs[$srtNum]); // keep lines that don't end at 0 and remove them manually
                        } else {
                            $srtFileArrayMs[$srtNum]["Num"]   = $c;
                            $srtFileArrayMs[$srtNum]["Start"] = $newStart;
                            $srtFileArrayMs[$srtNum]["Stop"]  = $newStop;
                        }
                    }
                    $c++;
                }
            }
            
            file_put_contents("uploads/".$srtFile["name"], msArrayToSrt($srtFileArrayMs));
        }
    }
}
}