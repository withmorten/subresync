<?xml version="1.0" encoding="windows-1252" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>subresync</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
        <script src="js/helpers.js" type="text/javascript"></script>
        <script src="js/gui.js" type="text/javascript"></script>
    </head>
    <body>
<?php
require_once('helpers.php');
if(isset($_POST['submit']) && isset($_POST['timelines'])) {
    if($_FILES['srtfiles']['error'][0] !== UPLOAD_ERR_NO_FILE) handleForm();
    else echo 'No files were uploaded/selected.';
}
?>
    </body>
</html>