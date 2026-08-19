<?php
namespace App\Core;

class Upload {
    private $allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png'
    ];
    private $maxFileSize = 2 * 1024 * 1024; // 2MB

    public function handle($file, $subFolder = '') {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new \Exception('Invalid parameters.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Exception('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Exception('Exceeded filesize limit.');
            default:
                throw new \Exception('Unknown errors.');
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception('Exceeded filesize limit.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        
        if (!in_array($mime, $this->allowedMimeTypes, true)) {
            throw new \Exception('Invalid file format. Only PDF, JPG, and PNG are allowed.');
        }

        // Generate safe random filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;

        $targetDir = rtrim(UPLOAD_DIR . '/' . $subFolder, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception('Failed to move uploaded file.');
        }

        // Return relative path for database storage
        return $subFolder ? $subFolder . '/' . $filename : $filename;
    }
}
