<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Wordpress\Database\Option;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseRepository implements RepositoryContract
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['autoload'])) 
        {
            $this->config['autoload'] = null;
        }
    }

    public function find($key, array $options = []): mixed
    {
        return get_option($key, $options['default'] ?? null);
    }

    public function findBy(array $criteria, array $options = []): array
    {
        global $wpdb;
        
        $where = [];
        $params = [];
        
        foreach ($criteria as $field => $value) 
        {
            if ($field === 'autoload') {
                $where[] = 'autoload = %s';
                $params[] = $value;
            } elseif ($field === 'option_name') {
                $where[] = 'option_name LIKE %s';
                $params[] = $value;
            }
        }
        
        $query = "SELECT option_name, option_value FROM {$wpdb->options}";
        if (!empty($where)) 
        {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }
        
        if (isset($options['limit'])) 
        {
            $query .= ' LIMIT %d';
            $params[] = $options['limit'];
        }
        
        $prepared = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared);
        
        $options = [];
        foreach ($results as $row) 
        {
            $options[$row->option_name] = maybe_unserialize($row->option_value);
        }
        
        return $options;
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): bool
    {
        $success = true;
        foreach ($data as $key => $value) 
        {
            if (!add_option(
                $key, 
                $value, 
                '', 
                $this->config['autoload']
            )) 
            {
                $success = false;
            }
        }
        return $success;
    }
    
    public function update($key, array $data): bool
    {
        if (is_array($key)) 
        {
            // Bulk update
            $success = true;
            foreach ($key as $k => $value) 
            {
                if (!update_option($k, $value)) 
                {
                    $success = false;
                }
            }
            return $success;
        }
        
        // Single key update
        return update_option($key, $data);
    }

    public function delete($key): bool
    {
        if (is_array($key)) 
        {
            // Bulk delete
            $success = true;
            foreach ($key as $k) 
            {
                if (!delete_option($k)) 
                {
                    $success = false;
                }
            }
            return $success;
        }
        
        // Single key delete
        return delete_option($key);
    }
}