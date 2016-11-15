<?php
include('subparse.php');

?>
<?xml version="1.0" encoding="windows-1252" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>subresync</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
        <script src="jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="jquery.maskedinput.min.js" type="text/javascript"></script>
        <script src="gui.js" type="text/javascript"></script>
    </head>
    <body>
        <form action="index.php" method="post" id="timeform">
            <table id="formtable">
                <tr id="theader"><th></th><th colspan="2">resyncs subs via timings</th></tr>
                <tr id="tfooter"><th></th><th><input type="submit" value="resync" /></th><th><input type="reset" value="reset" onclick="return confirm('Really?');" /></th><th></th></tr>
            </table>
        </form>
    </body>
</html>