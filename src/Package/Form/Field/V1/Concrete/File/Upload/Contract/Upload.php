<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\File\Upload\Contract;

interface Upload
{
    /**
     * Handle file upload
     * 
     * @param array $file File data from $_FILES
     * @param array $options Additional options
     * @return array|false Uploaded file data or false on failure
     */
    public function upload(array $file, array $options = []);
    
    /**
     * Handle multiple file uploads
     * 
     * @param array $files Array of file data from $_FILES
     * @param array $options Additional options
     * @return array Array of uploaded file data
     */
    public function uploadMultiple(array $files, array $options = []): array;
}