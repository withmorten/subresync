<?php
foreach(glob('php/*.php') as $phpFile) {
    require_once($phpFile);
}

$timeLineBlocksJson = '';

function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function pre($msg) {
    echo "<pre>".$msg."</pre>";
}

function predie($msg) {
    pre($msg);
    die();
}