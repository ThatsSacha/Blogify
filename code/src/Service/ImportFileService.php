<?php

namespace App\Service;

use Exception;

class ImportFileService {
    /**
     * @return string
     * @throws Exception
     */
    public function verifyAndUploadFile(array $file): string {
        if (isset($file['name']) || isset($file['tmp_name']) || isset($file['type']) || isset($file['size'])) {
            if ($file['error'] === 0) {
                $extension = pathinfo($file['name'])['extension'];
                $authorizedExtensions = ['png', 'jpg', 'jpeg', 'gif'];

                $this->verifyFileExtension($authorizedExtensions, $extension);
                $this->verifyFileSize($file['size']);
                $fileName = strtoupper(time() . uniqid()) . '.' . $extension;
                $this->uploadFile($file['tmp_name'], $fileName);

                return $fileName;
            } else {
                throw new Exception('File upload error');
            }
        } else {
            throw new Exception('Fields missing in file');
        }
    }

    public function uploadFile(string $tmp, string $fileName): void {
        $filePath = './assets/img/blog/' . $fileName;
        move_uploaded_file($tmp, $filePath);     
    }

    /**
     * @throws Exception
     */
    public function verifyFileExtension(array $authorizedExtensions, string $extension): void {
        $extension = strtolower($extension);

        if (!in_array($extension, $authorizedExtensions)) {
            $extensions = implode(', ', $authorizedExtensions);
            throw new Exception("File extension not authorized. We only accept $extensions.");
        }
    }

    /**
     * @throws Exception
     */
    public function verifyFileSize(int $size): void {
        $sizeToMegabytes = $size / 1e+6;

        if ($sizeToMegabytes > 5) {
            throw new Exception('File size too big');
        }
    }
}