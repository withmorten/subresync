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
                if(trim($srtLine) == '') {
                    $srtBlock = new stdClass;
                    $srtBlock->number = $srtNum;
                    list($srtBlock->startTime, $srtBlock->stopTime) = explode(' --> ', $srtTime);
                    // $srtBlock->time = $srtTime;
                    $srtBlock->text = $srtText;
                    $srtText = '';
                    $state = SRT_STATE_SUBNUMBER;

                    $srtArray[] = $srtBlock;
                } else {
                    $srtText .= $srtLine."\r\n";
                }
                break;
        }
    }
    
    return $srtArray;
}

const TIME_STATE_HOURS = 0;
const TIME_STATE_MINUTES = 1;
const TIME_STATE_SECONDS = 2;
const TIME_STATE_MILISECONDS = 3;

function getMsFromSrtTime($srtTime) {
    $timeExplode = explode(":", str_replace(",", ":", $srtTime));
    $msTime = 0;
    foreach($timeExplode as $timeState => $timeAmount) {
        switch($timeState) {
            case TIME_STATE_HOURS:
                $timeAmount *= 60;
            case TIME_STATE_MINUTES:
                $timeAmount *= 60;
            case TIME_STATE_SECONDS:
                $timeAmount *= 1000;
            case TIME_STATE_MILISECONDS:
                $msTime     += $timeAmount;
        }
    }
    
    return $msTime;
}

const SECONDS_MULTI = 1000;
const MINUTES_MULTI = 60 * SECONDS_MULTI;
const HOURS_MULTI   = 60 * MINUTES_MULTI;

function getSrtTimeFromMs($msTime) {
    $srtTime = '';
    
    $hh = (int)  ($msTime / HOURS_MULTI);
    $mm = (int) (($msTime % HOURS_MULTI) / MINUTES_MULTI);
    $ss = (int)((($msTime % HOURS_MULTI) % MINUTES_MULTI) / SECONDS_MULTI);
    $ms = (int)((($msTime % HOURS_MULTI) % MINUTES_MULTI) % SECONDS_MULTI);
    
    $srtTime.= str_pad($hh, 2, "0", 0).":";
    $srtTime.= str_pad($mm, 2, "0", 0).":";
    $srtTime.= str_pad($ss, 2, "0", 0).",";
    $srtTime.= str_pad($ms, 3, "0", 0);
    
    return $srtTime;
}

const SRT_TIME_MAX = 362439999; // 99:99:99,999

function timeLinesToMs($timeLines) {
    $timeLinesMs = array();
    foreach($timeLines as $i => $timeLine) {
        $timeLinesMs[$i+1]['sync'] = getMsFromSrtTime($timeLine['sync']) * ($timeLine['sign'] === "+" ? 1 : -1);
        $timeLineDuration = getMsFromSrtTime($timeLine['dura']);
        $timeLinesMs[$i+1]['dura'] = $i === $timeLineDuration ? SRT_TIME_MAX : $timeLineDuration;
    }
    return $timeLinesMs;
}

function srtFileArrayToMs($srtFileArray) {
    $s = 0;
    foreach($srtFileArray as $srtBlock) {
        $srtFileArrayMs[$s]["Num"]   = $srtBlock->number;
        $srtFileArrayMs[$s]["Start"] = getMsFromSrtTime($srtBlock->startTime);
        $srtFileArrayMs[$s]["Stop"]  = getMsFromSrtTime($srtBlock->stopTime);
        $srtFileArrayMs[$s]["Dura"]  = $srtFileArrayMs[$s]["Stop"] - $srtFileArrayMs[$s]["Start"];
        $srtFileArrayMs[$s]["Text"]  = $srtBlock->text;
        
        $s++;
    }
    return $srtFileArrayMs;
}

function msArrayToSrt($msArray) {
    $srtString = '';
    
    foreach($msArray as $srtBlock) {
        $srtString.= $srtBlock["Num"]."\r\n";
        $srtString.= getSrtTimeFromMs($srtBlock["Start"])." --> ".getSrtTimeFromMs($srtBlock["Stop"])."\r\n";
        $srtString.= $srtBlock["Text"]."\r\n";
    }
    
    return $srtString;
}

function arrayToSrt($srtArray) {
    $srtString = '';
    
    foreach($srtArray as $srtBlock) {
        $srtString.= $srtBlock->number."\r\n";
        $srtString.= $srtBlock->startTime." --> ".$srtBlock->stopTime."\r\n";
        $srtString.= $srtBlock->text."\r\n";
        // $srtString.= "\n";
    }
    
    return $srtString;
}

function z($int) {
    return max($int, 0);
}