<?php
foreach(glob('php/*.php') as $phpFile) {
    require_once($phpFile);
}

function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function predie($msg) {
    echo "<pre>".$msg."</pre>";
    die();
}