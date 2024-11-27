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

        // Sanitise the file name
        $sanitizedFileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);

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

        // Check that the directory to save is there, if not make it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Create a unique file name
        $uniqueFileName = uniqid() . '_' . $sanitizedFileName;
        $uploadPath = '../../' . $uploadDir . $uniqueFileName;

        // Throw an error if it fails to move the file
        if (!move_uploaded_file($fileTmpPath, $uploadPath)) {
            throw new Exception('Failed to move uploaded file.');
        }

        // Return the file name to save in the DB
        return $uploadDir . $uniqueFileName;
    } catch (Exception $e) {
        return $e;
    }
}