<?php
    header('Content-Type: image/jpeg');
    include('SimpleImage.php');
    $image = new SimpleImage();
    $image->load_from_tmp();
    $image->resizeToWidth(150);
    $image->output();
?>

