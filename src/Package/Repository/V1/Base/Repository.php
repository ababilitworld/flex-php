<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract,
    FlexPhp\Package\Repository\V1\Exception\Repository as RepositoryException,
};

abstract class Repository implements RepositoryContract
{
    protected $connection;
    protected $config;

    public function __construct($connection, array $config = [])
    {
        $this->connection = $connection;
        $this->config = $config;
    }

    public function getConnection(): mixed
    {
        return $this->connection;
    }

    abstract public function find($id, array $options = []);
    abstract public function findBy(array $criteria, array $options = []);
    abstract public function findAll(array $criteria = []);
    abstract public function create(array $data);
    abstract public function update($id, array $data);
    abstract public function delete($id);

    public function resolveConnection(): void
    {

    }

    abstract protected function validateConfig(): void;

    protected function validateData(array $data, array $rules): bool
    {
        foreach ($rules as $field => $validator) 
        {
            if (!array_key_exists($field, $data)) 
            {
                throw RepositoryException::forMissingField($field);
            }
            
            if (is_callable($validator) && !$validator($data[$field])) 
            {
                throw RepositoryException::forValidationFailure($field, [
                    'value' => $data[$field],
                    'rule' => is_string($validator) ? $validator : 'custom'
                ]);
            }
        }
        return true;
    }
}