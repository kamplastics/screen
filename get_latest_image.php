<?php
    // Define the path to the images directory
    $imagesDirectory = './image/';

    // Fetch the latest .bmp file
    $bmpFiles = glob($imagesDirectory . '*.bmp');

    // Check if there are any files
    if (!empty($bmpFiles)) 
    {
        // Sort files by last modified time, latest first
        usort($bmpFiles, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Return the name of the latest file
        echo basename($bmpFiles[0]);
    } 
    else 
    {
        echo "No image found";
    }
?>
