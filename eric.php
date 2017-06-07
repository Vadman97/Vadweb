<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $arr = explode('.', basename($_FILES['file']['name']));
    $name = current($arr);
    $ext = strtolower(end($arr));

    $uploadDir = '/home/vadwebData/eric/';
    $file = $uploadDir . $name . "." . $ext;
    $counter = 1;
    while (file_exists($file)) {
        $file = $uploadDir . $name . "_" . $counter . "." . $ext;
        $counter++;
    }
    echo $file . "\n";
    if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
       echo "File is valid, and was successfully uploaded.\n";
    }
}

?>
