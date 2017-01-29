<?php
function handleForm() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) srtErrorMessage(UPLOAD_ERR_INI_SIZE); // catches line 0 file upload errors
        
        if(($timeLineBlocks = json_decode($_POST["jsonarea"])) == NULL) {
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
        }
        
        // $timeLineBlocks = array();
        // $timeLineBlocks[] = array(
                                // 'from' => 0,
                                // 'sync' => -1000
                            // );
        
        dump($timeLineBlocks);
        
        if(count($timeLineBlocks) === 1) echo "No timelines containing useful information found!";
        
        $GLOBALS["timeLineBlocksJson"] = json_encode($timeLineBlocks);
        // die();

        $srtFiles = reArrayFiles($_FILES['srtfiles']);
        
        foreach($srtFiles as $srtFile) {
            $srtError = srtError($srtFile);
            // if($srtError !== 0) srtErrorMessage($srtError);
            
            // $srtFileArray = srtFileArrayToMs(srtToArray(file_get_contents($srtFile["tmp_name"])));
            $srtFileArrayMs = srtFileArrayToMs(srtToArray(file_get_contents("uploads/01_arrow_clean.srt")));
            // dump($srtFileArrayMs);
            $newSrtFileArrayMs = array();
            
            $c = 1;
            foreach($srtFileArrayMs as $srtBlockMs) {
                // if($c === 3) break;
        
                foreach($timeLineBlocks as $timeLineBlockNum => $timeLineBlock) {
                    if($srtBlockMs["Start"] <= $timeLineBlock["from"]) break; // srtBlock is not within this timeLineBlock's range
                    
                    $newStart = z($srtBlockMs["Start"] + $timeLineBlock["sync"]);
                    $newStop  = z($srtBlockMs["Stop"]  + $timeLineBlock["sync"]);
                    
                    if(($newStop - $newStart) === 0) continue 2; // delete srtBlock because it's in negative space
                    
                    $newSrtBlockMs = $srtBlockMs;
                    $newSrtBlockMs["Num"]   = $c;
                    $newSrtBlockMs["Start"] = $newStart;
                    $newSrtBlockMs["Stop"]  = $newStop;
                }
                
                $newSrtFileArrayMs[] = $newSrtBlockMs;
                
                $c++;
            }
            
            // dump(msArrayToSrt($newSrtFileArrayMs));
            file_put_contents("uploads/02_arrow_edit.srt", msArrayToSrt($newSrtFileArrayMs));
        }
    }
}