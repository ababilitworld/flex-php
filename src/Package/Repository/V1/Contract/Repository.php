<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Contract;

interface Repository
{
    public function find($id, array $options = []);
    public function findBy(array $criteria, array $options = []);
    public function findAll(array $options = []);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function resolveConnection();
}