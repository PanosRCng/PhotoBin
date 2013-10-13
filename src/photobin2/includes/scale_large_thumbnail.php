<?php
    header('Content-Type: image/jpeg');
    include('SimpleImage.php');
    $image = new SimpleImage();
    $image->load($_GET['id']);
    $image->output();
?>

