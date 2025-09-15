<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Cache\Transient;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseRepository
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['expiration'])) 
        {
            $this->config['expiration'] = 0; // Default: no expiration
        }
    }

    public function find($key, array $options = []): mixed
    {
        return get_transient($this->getPrefixedKey($key));
    }

    public function findBy(array $criteria, array $options = []): array
    {
        // Transients don't support querying, but we can simulate for multiple keys
        $results = [];
        foreach ($criteria['keys'] ?? [] as $key) {
            $results[$key] = $this->find($key, $options);
        }
        return $results;
    }

    public function findAll(array $options = []): array
    {
        // Not directly supported by WordPress transients
        return [];
    }

    public function create(array $data): bool
    {
        return $this->update(null, $data);
    }
    
    public function update($prefix, array $data): bool
    {
        $success = true;
        foreach ($data as $key => $value) {
            $fullKey = $this->getPrefixedKey($prefix ? "{$prefix}_{$key}" : $key);
            if (!set_transient($fullKey, $value, $this->config['expiration'])) {
                $success = false;
            }
        }
        return $success;
    }

    public function delete($key): bool
    {
        return delete_transient($this->getPrefixedKey($key));
    }

    protected function getPrefixedKey(string $key): string
    {
        return ($this->config['prefix'] ?? '') . $key;
    }
}