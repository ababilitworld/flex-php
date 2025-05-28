<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Cache\Object;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseRepository implements RepositoryContract
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['default_group'])) 
        {
            $this->config['default_group'] = '';
        }
        
        if (!isset($this->config['expiration'])) 
        {
            $this->config['expiration'] = 0;
        }
        
    }

    public function find($key, array $options = []): mixed
    {
        return wp_cache_get(
            $this->getPrefixedKey($key),
            $options['group'] ?? $this->config['default_group']
        );
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $group = $options['group'] ?? $this->config['default_group'];
        $results = [];
        
        foreach ($criteria['keys'] ?? [] as $key) 
        {
            $results[$key] = wp_cache_get(
                $this->getPrefixedKey($key),
                $group
            );
        }
        
        return $results;
    }

    public function findAll(array $options = []): array
    {
        // Object cache doesn't support retrieving all items
        return [];
    }

    public function create(array $data): bool
    {
        return $this->update(null, $data);
    }
    
    public function update($group, array $data): bool
    {
        $success = true;
        $targetGroup = $group ?? $this->config['default_group'];
        
        foreach ($data as $key => $value) 
        {
            if (!wp_cache_set(
                $this->getPrefixedKey($key),
                $value,
                $targetGroup,
                $this->config['expiration']
            )) 
            {
                $success = false;
            }
        }
        
        return $success;
    }

    public function delete($key, string $group = ''): bool
    {
        return wp_cache_delete(
            $this->getPrefixedKey($key),
            $group ?: $this->config['default_group']
        );
    }

    protected function getPrefixedKey(string $key): string
    {
        return ($this->config['prefix'] ?? '') . $key;
    }
}