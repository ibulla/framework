<?php
//header('Content-Type: application/json');

$imageFolder = "../assets/uploads/";

function generateDatePrefix() {
    return date('d-m-Y');
}

function generateRandomName($length = 4) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomName = '';
    for ($i = 0; $i < $length; $i++) {
        $randomName .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomName;
}

  reset ($_FILES);
  $temp = current($_FILES);
  if (is_uploaded_file($temp['tmp_name'])){

    // Verify extension
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "jpeg", "png"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

$originalFilename = $temp['name'];
$extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
$newFilename = 'iBulla-works-' . generateDatePrefix(). '_' .generateRandomName() . '.' . $extension;

$uploadedImagePath = $temp['tmp_name'];

// Load the uploaded image based on its format
if ($extension === 'jpg' || $extension === 'jpeg') {
    $sourceImage = imagecreatefromjpeg($uploadedImagePath);
} elseif ($extension === 'png') {
    $sourceImage = imagecreatefrompng($uploadedImagePath);
} elseif ($extension === 'gif') {
    $sourceImage = imagecreatefromgif($uploadedImagePath);
} else {
    // Unsupported image format
    exit("Unsupported image format.");
}

// Get the original dimensions
$originalWidth = imagesx($sourceImage);
$originalHeight = imagesy($sourceImage);

// Calculate the new dimensions based on the conditions
$newWidth = $originalWidth;
$newHeight = $originalHeight;

if ($originalWidth > 1920) {
    $newWidth = 1920;
    $newHeight = ($originalHeight / $originalWidth) * $newWidth;
} elseif ($originalHeight > 1080) {
    $newHeight = 1080;
    $newWidth = ($originalWidth / $originalHeight) * $newHeight;
}

// Create a new image with the calculated dimensions
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

imagealphablending($resizedImage, false);
imagesavealpha($resizedImage, true);

// Resize and copy the original image to the new image
imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

// Determine the appropriate save function based on the image format
if ($extension === 'jpg' || $extension === 'jpeg') {
    imagejpeg($resizedImage, $imageFolder . $newFilename, 80);
} elseif ($extension === 'png') {
    imagepng($resizedImage, $imageFolder . $newFilename, 9); // 8 is compression level
} elseif ($extension === 'gif') {
    imagegif($resizedImage, $imageFolder . $newFilename);
}

// Free up memory
imagedestroy($sourceImage);
imagedestroy($resizedImage);

    header('Content-Type: application/text');
    echo json_encode(array('location' => $_SERVER['HTTP_HOST']."/i/assets/uploads/" . $newFilename));
  } else {
    // Notify editor that the upload failed
    header("HTTP/1.1 500 Server Error");
  }
?>
