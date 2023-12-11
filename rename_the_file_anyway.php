$imagesDirectory = './image/';
$newFileName = uniqid('', true) . '.bmp';
$bmpFile = glob($imagesDirectory . '*.bmp')[0] ?? null;
// Rename the file
rename($bmpFile, $imagesDirectory . $newFileName);

