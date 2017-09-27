<?php
foreach(glob('php/*.php') as $phpFile) {
    require_once($phpFile);
}

if(!is_dir('uploads')) mkdir('uploads');
if(!is_dir('json')) mkdir('json');

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