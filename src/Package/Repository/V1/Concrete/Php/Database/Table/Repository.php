<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Concrete\Php\Database\Table;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Base\Repository as BaseRepository,
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
};

class Repository extends BaseRepository implements RepositoryContract
{
    protected function validateConfig(): void
    {
        if (!isset($this->config['table'])) 
        {
            throw new \InvalidArgumentException("Table name must be configured");
        }
    }

    public function find($id, array $options = [])
    {
        $table = $this->config['table'];
        return $this->connection->get_row(
            $this->connection->prepare("SELECT * FROM {$table} WHERE id = %d", $id)
        );
    }

    public function findBy(array $criteria, array $options = []): array
    {
        $table = $this->config['table'];
        $where = '';
        $params = [];
        
        if (!empty($criteria)) 
        {
            $conditions = [];
            foreach ($criteria as $field => $value) 
            {
                $conditions[] = "{$field} = %s";
                $params[] = $value;
            }
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }
        
        $limit = isset($options['limit']) ? "LIMIT %d" : "";
        if ($limit) 
        {
            $params[] = $options['limit'];
        }
        
        $query = $this->connection->prepare(
            "SELECT * FROM {$table} {$where} {$limit}",
            $params
        );
        
        return $this->connection->get_results($query) ?: [];
    }

    public function findAll(array $options = []): array
    {
        return $this->findBy([], $options);
    }

    public function create(array $data): int
    {
        $this->validateData($data, $this->config['validation_rules'] ?? []);
        
        $table = $this->config['table'];
        $this->connection->insert($table, $data);
        
        return $this->connection->insert_id;
    }
    
    public function update($id, array $data): bool
    {
        $table = $this->config['table'];
        return (bool) $this->connection->update(
            $table,
            $data,
            ['id' => $id]
        );
    }

    public function delete($id): bool
    {
        $table = $this->config['table'];
        return (bool) $this->connection->delete(
            $table,
            ['id' => $id]
        );
    }
}