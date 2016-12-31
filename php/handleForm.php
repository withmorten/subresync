<?php
function handleForm() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) srtErrorMessage(UPLOAD_ERR_INI_SIZE); // catches line 0 file upload errors
        
        $timeLines = timeLinesToMs($_POST['timelines']);
        $timeLineCount = count($timeLines);
        $timeLineBlocks = array();
        $c = 0;
        
        foreach($timeLines as $timeLineNum => $timeLine) {
            if($timeLineNum !== 1 && (($timeLine['sync'] || $timeLine['dura']) == 0)) continue;
            
            if($timeLineNum === 1) {
                $timeLineBlocks[] = array(
                                        'from' => SRT_TIME_MAX - $timeLine['dura'],
                                        'sync' => $timeLine['sync']
                                    );
            } else {
                $timeLineBlocks[] = array(
                                        'from' => $timeLine['dura'] + $timeLineBlocks[$c-1]['from'] + $timeLineBlocks[$c-1]['sync'],
                                        'sync' => $timeLine['sync']
                                    );
            }

            $c++;
        }
        
        if(count($timeLineBlocks) === 1) echo "No timelines containing useful information found!";

        $srtFiles = reArrayFiles($_FILES['srtfiles']);
        foreach($srtFiles as $srtFile) {
            $srtError = srtError($srtFile);
            // COMMENTED FOR DEBUGGING:
            // if($srtError !== 0) srtErrorMessage($srtError);
            
            $srtFileArray = srtToArray(file_get_contents($srtFile["tmp_name"]));
            foreach($srtFileArray as $srtBlock) {
                // dump($srtBlock);
                
                $srtBlockStartMs = getMsFromSrtTime($srtBlock->startTime);
                $srtBlockStopMs  = getMsFromSrtTime($srtBlock->stopTime);
                $srtBlockDuraMs  = $srtBlockStopMs - $srtBlockStartMs;
        
                foreach($timeLineBlocks as $timeLineBlockNum => $timeLineBlock) {
                    // untertitel in blöcke geladen
                    // pro block wird timeline geladen
                    // in timeline foreach wird überprüft ob block in timeline ist
                    
                    dump($timeLineBlock);
                }
            }
        }
    }
}