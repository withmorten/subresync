<?php
define('SRT_STATE_SUBNUMBER', 0);
define('SRT_STATE_TIME',      1);
define('SRT_STATE_TEXT',      2);
define('SRT_STATE_BLANK',     3);

function parseSrt($lines) {
    
    $subs    = array();
    $state   = SRT_STATE_SUBNUMBER;
    $subNum  = 0;
    $subText = '';
    $subTime = '';
    
    foreach($lines as $line) {
        switch($state) {
            case SRT_STATE_SUBNUMBER:
                $subNum = trim($line);
                $state  = SRT_STATE_TIME;
                break;

            case SRT_STATE_TIME:
                $subTime = trim($line);
                $state   = SRT_STATE_TEXT;
                break;

            case SRT_STATE_TEXT:
                if (trim($line) == '') {
                    $sub = new stdClass;
                    $sub->number = $subNum;
                    list($sub->startTime, $sub->stopTime) = explode(' --> ', $subTime);
                    // $sub->time   = $subTime;
                    $sub->text   = $subText;
                    $subText     = '';
                    $state       = SRT_STATE_SUBNUMBER;

                    $subs[]      = $sub;
                } else {
                    $subText .= $line;
                }
                break;
        }
    }
    
    return $subs;
}

// $change   = file($argv[1]);
// $source   = file($argv[2]);

// $result_srt = parseSrt($change);
// $source_srt = parseSrt($source);

// for($i = 0; $i < count($result_srt); $i++) {
    // if($result_srt[$i]->text !== $source_srt[$i]->text) {
        // echo $result_srt[$i]->number."\n";
        // echo $result_srt[$i]->text."\n";
        // echo $source_srt[$i]->text."\n";
    // }
    // $result_srt[$i]->time = $source_srt[$i]->time;
    // echo $result_srt[$i]->number."\r\n";
    // echo $result_srt[$i]->time."\r\n";
    // echo $result_srt[$i]->text."\r\n";
    #echo $result_srt[$i]->time."\n\n";
    #echo $source_srt[$i]->time."\n\n";
// }