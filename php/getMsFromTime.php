<?php
const TIME_STATE_HOURS = 0;
const TIME_STATE_MINUTES = 1;
const TIME_STATE_SECONDS = 2;
const TIME_STATE_MILISECONDS = 3;

function getMsFromTime($time) {
    $time_explode = explode(":", str_replace(",", ":", $time));
    $time_ms = 0;
    foreach($time_explode as $time_state => $time_amount) {
        switch($time_state) {
            case TIME_STATE_HOURS:
                $time_amount *= 60;
            case TIME_STATE_MINUTES:
                $time_amount *= 60;
            case TIME_STATE_SECONDS:
                $time_amount *= 1000;
            case TIME_STATE_MILISECONDS:
                $time_ms += $time_amount;
        }
    }
    
    return $time_ms;
}