<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Handler;

use Ababilithub\FlexPhp\Package\Form\Field\V1\File\Upload\Contract\Upload as FileUploadHandler;

class WordPress implements FileUploadHandler
{
    public function upload(array $file, array $options = [])
    {
        if (!function_exists('wp_handle_upload')) {
            throw new \RuntimeException('WordPress functions not available');
        }
        
        $upload = wp_handle_upload($file, ['test_form' => false]);
        
        if (isset($upload['error'])) {
            return false;
        }
        
        return [
            'url' => $upload['url'],
            'path' => $upload['file'],
            'type' => $upload['type'],
            'size' => filesize($upload['file'])
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
}