<?php
const SRT_STATE_SUBNUMBER = 0;
const SRT_STATE_TIME = 1;
const SRT_STATE_TEXT = 2;
const SRT_STATE_BLANK = 3;

function srtToArray($srtString) {
    $srtArray = array();
    $state    = SRT_STATE_SUBNUMBER;
    
    $srtNum   = 0;
    $srtText  = '';
    $srtTime  = '';
    
    $srtLines = preg_split("/\r\n|\n|\r/", $srtString);
    
    foreach($srtLines as $srtLine) {
        switch($state) {
            case SRT_STATE_SUBNUMBER:
                $srtNum = trim($srtLine);
                $state  = SRT_STATE_TIME;
                break;

            case SRT_STATE_TIME:
                $srtTime = trim($srtLine);
                $state   = SRT_STATE_TEXT;
                break;

            case SRT_STATE_TEXT:
                if (trim($srtLine) == '') {
                    $srtBlock = new stdClass;
                    $srtBlock->number = $srtNum;
                    list($srtBlock->startTime, $srtBlock->stopTime) = explode(' --> ', $srtTime);
                    // $srtBlock->time = $srtTime;
                    $srtBlock->text = $srtText;
                    $srtText     = '';
                    $state       = SRT_STATE_SUBNUMBER;

                    $srtArray[] = $srtBlock;
                } else {
                    $srtText .= $srtLine;
                }
                break;
        }
    }
    
    return $srtArray;
}

function arrayToSrt($srtArray) {
    $srtString = '';
    
    foreach($srtArray as $srtBlock) {
        $srtString.= $srtBlock->number."\n";
        $srtString.= $srtBlock->startTime." --> ".$srtBlock->stopTime."\n";
        $srtString.= $srtBlock->text."\n";
        $srtString.= "\n";
    }
    
    return $srtString;
}