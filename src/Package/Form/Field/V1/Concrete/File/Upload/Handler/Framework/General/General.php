<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\File\Upload\Handler\Framework;

use Ababilithub\FlexPhp\Package\Form\Field\V1\File\Upload\Contract\Upload as FileUploadHandler;

class General implements FileUploadHandler
{
    protected string $uploadDir;
    protected string $baseUrl;
    
    public function __construct(string $uploadDir, string $baseUrl = '')
    {
        $this->uploadDir = rtrim($uploadDir, '/');
        $this->baseUrl = rtrim($baseUrl, '/');
        
        if (!is_dir($this->uploadDir) && !mkdir($this->uploadDir, 0755, true)) {
            throw new \RuntimeException("Failed to create upload directory");
        }
    }
    
    public function upload(array $file, array $options = [])
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        $filename = $this->generateFilename($file['name']);
        $targetPath = $this->uploadDir . '/' . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return false;
        }
        
        return [
            'url' => $this->baseUrl . '/' . $filename,
            'path' => $targetPath,
            'type' => $file['type'],
            'size' => $file['size']
        ];
    }
    
    public function uploadMultiple(array $files, array $options = []): array
    {
        $results = [];
        
        foreach ($files as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $result = $this->upload($file, $options);
                if ($result !== false) {
                    $results[] = $result;
                }
            }
        }
        
        return $results;
    }
    
    protected function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '.' . $extension;
    }
}