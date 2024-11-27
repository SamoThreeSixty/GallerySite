<?php

// Logic for downloading staff images in multiple resolution to be used on different devices.

/**
 * Handles file upload and sanitization.
 *
 * @param array $file The uploaded file from $_FILES.
 * @param array $allowedExtensions List of allowed file extensions.
 * @param array $allowedMimeTypes List of allowed MIME types.
 * @param string $uploadDir Directory to store uploaded files.
 * @param int $maxFileSize Maximum allowed file size in bytes.
 * @return string The file name as a string to store in the DB.
 */
function sanitizeAndUploadFile(
    $file,
    $allowedExtensions,
    $allowedMimeTypes,
    $uploadDir,
): string {
    try {
        // Throw an error if there was a problem with the file
        if (!$file['error'] === UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Extract variables from the file object
        $fileName = basename($file['name']);
        $fileTmpPath = $file['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Create a unique id for the file when saving
        $fileUid = uniqid();
        $fileUidName = $fileUid . '.' . $fileExtension;

        // Error if it is not an allowed extension
        if (
            !in_array(
                $fileExtension,
                $allowedExtensions
            ) || !in_array(
                $fileType,
                $allowedMimeTypes
            )
        ) {
            throw new Exception('File does not have an allowed extension :' . $file['extension']);
        }

        // Check that there is the media directory available. If not create it.
        $uploadPath = '../../' . $uploadDir;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            // Create the three versions of the image
            downloadImage($fileTmpPath, 'desktop', 1200, 800, $uploadPath, uid: $fileUidName);
            downloadImage($fileTmpPath, 'tablet', 800, 600, $uploadPath, uid: $fileUidName);
            downloadImage($fileTmpPath, 'phone', 400, 300, $uploadPath, uid: $fileUidName);
        } catch (Exception $e) {
            throw new Exception('Failed to move uploaded file: ' . $e->getMessage());

        }

        // Return the file name to save in the DB
        return $fileUid;
    } catch (Exception $e) {
        return $e;
    }
}

/**
 * Handles the resizing of images using the GD library included in PHP
 *
 * @param array $source The uploaded file from $_FILES.
 * @param string $device What device is it for.
 * @param int $width In px.
 * @param int $height In px.
 * @param string $uploadPath Where the file should be downloaded to.
 * @param string $uid This is the unique name given for the file to be retrieved.
 * @return void Nothing returned from function
 */
function downloadImage($source, $device, $width, $height, $uploadPath, $uid)
{
    list($originalWidth, $originalHeight, $imageType) = getimagesize($source);

    // Create image resource based on the image type
    // Currently only handling jpg or png
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $srcImg = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $srcImg = imagecreatefrompng($source);
            break;

        default:
            throw new Exception("Unsupported media type");
    }

    // Calculate the scaling factor
    $scaleRatio = min($width / $originalWidth, $height / $originalHeight);
    $newWidth = floor($originalWidth * $scaleRatio);
    $newHeight = floor($originalHeight * $scaleRatio);

    // Create a new empty image with the new dimensions
    $dstImg = imagecreatetruecolor($newWidth, $newHeight);

    // Copy and resize the image into the new image
    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, src_y: 0, dst_width: $newWidth, dst_height: $newHeight, src_width: $originalWidth, src_height: $originalHeight);

    // Save the resized image to the appropriate file
    // This will be able to use the unique id split on the _ symbol since it does not use it in generating.
    $outputPath = $uploadPath . $device . "_" . $uid;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($dstImg, $outputPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($dstImg, $outputPath);
            break;
    }

    // Clean up the created images from memory
    imagedestroy($srcImg);
    imagedestroy($dstImg);
}