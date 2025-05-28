<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Service;

use Ababilithub\{
    FlexPhp\Package\Repository\V1\Contract\Repository as RepositoryContract
};

class Repository
{
    private $repository;

    public function __construct(RepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findAll(array $criteria = [])
    {
        return $this->repository->findAll($criteria);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}