<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\FileSystem;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseRepository
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['directory'])) {
            throw new \InvalidArgumentException("Directory must be configured");
        }
        
        if (!wp_mkdir_p($this->config['directory'])) {
            throw new \RuntimeException("Could not create directory");
        }
    }

    public function find($filename, array $options = [])
    {
        $file = $this->getFilePath($filename);
        if (!file_exists($file)) {
            return $options['default'] ?? null;
        }
        
        $content = file_get_contents($file);
        return $options['json'] ?? false ? json_decode($content, true) : $content;
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $files = glob($this->getFilePath('*'));
        $results = [];
        
        foreach ($files as $file) {
            $filename = basename($file);
            if ($this->matchesCriteria($filename, $criteria)) {
                $results[$filename] = $this->find($filename, $options);
            }
        }
        
        return $results;
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): string
    {
        $filename = $data['filename'] ?? uniqid() . '.' . ($data['extension'] ?? 'txt');
        $this->writeFile($filename, $data['content']);
        
        return $filename;
    }
    
    public function update($filename, array $data): bool
    {
        if (!isset($data['content'])) {
            throw new \InvalidArgumentException("Content must be provided for update");
        }
        
        return $this->writeFile($filename, $data['content']) !== false;
    }

    public function delete($filename): bool
    {
        $file = $this->getFilePath($filename);
        return file_exists($file) ? unlink($file) : false;
    }

    protected function getFilePath(string $filename): string
    {
        return trailingslashit($this->config['directory']) . $filename;
    }

    protected function writeFile(string $filename, $content): int
    {
        $file = $this->getFilePath($filename);
        return file_put_contents($file, is_array($content) ? json_encode($content) : $content);
    }

    protected function matchesCriteria(string $filename, array $criteria): bool
    {
        foreach ($criteria as $key => $value) 
        {
            if ($key === 'extension' && pathinfo($filename, PATHINFO_EXTENSION) !== $value) 
            {
                return false;
            }
            
            if ($key === 'pattern' && !fnmatch($value, $filename)) 
            {
                return false;
            }
        }
        
        return true;
    }
}