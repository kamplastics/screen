<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Define the path to the images directory
    $imagesDirectory = './image/';

    // Path to the expected .bmp file in the directory
    $bmpFile = glob($imagesDirectory . '*.bmp')[0] ?? null;

    // Check if the file exists
    if ($bmpFile && file_exists($bmpFile)) 
    {
        // Get the last modification time of the file in Unix epoch format
        $lastModifiedTime = filemtime($bmpFile);

        // Get the current Unix epoch time
        $currentUnixEpoch = time();

        // Calculate the difference in seconds
        $timeDifference = $currentUnixEpoch - $lastModifiedTime;

        // Check if the file is modified within the last 5 minutes
        if ($timeDifference < 300) 
        {
            // Generate a new file name using UUID
            $newFileName = uniqid('', true) . '.bmp';

            // Rename the file
            rename($bmpFile, $imagesDirectory . $newFileName);
            
        }

        // Output the time difference for the renamed or existing file
        echo $timeDifference;
    } 
    else 
    {
        echo "No .bmp file found in the directory.";
    }
?>
