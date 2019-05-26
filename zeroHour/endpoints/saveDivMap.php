<?php

$imageName = $_POST["imagename"];
$dataString = $_POST["datastring"];
$positionType = $_POST["positiontype"];

if (file_exists("../images/" . $imageName) && substr_count($dataString,",")%6==5 && $positionType) { // LAZY AS FUCK BUT ONLY I HAVE ACCESS TO THIS SCRIPT SO FUCKIT
    $data = $positionType . "\r\n" . $dataString;
    $filename = '../images/' . $imageName . '.map';
    copy($filename, $filename . date("Ymd") . ".old");
    if (file_exists($filename . date("Ymd") . ".old")) {
        unlink($filename);
        file_put_contents($filename, $data);
        if (file_exists($filename)) {
            echo 'SUCCESS';
        } else {
            echo 'ERROR: FILE COULDN\'T BE CREATED';
        }
    } else {
        echo "ERROR: UNABLE TO CREATE BACKUP";
    }
    
} else {
    echo 'ERROR: POST VARS FAILED (image name: ' . $imageName . ', data string: ' . $dataString . ', position type: ' . $positionType . ')';
}

?>