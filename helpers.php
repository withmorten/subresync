<?php
foreach(glob('php/*.php') as $phpFile) {
    require_once($phpFile);
}

function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

// dump(mime_content_type("Constantine - 01x01 - Non Est Asylum.WEBRip-Anonymous.English.C.updated.Addic7ed.com.srt"));

// "text/plain"

// dump(getMsFromTime('01:00:00,000'));