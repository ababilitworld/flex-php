<?php
namespace Ababilithub\FlexPhp\Package\Contract\App\Controller\Crud\V1;
interface V1 
{
    public function create(array $data);
    public function save(array $data);
    public function getById(int $id);
    public function edit(int $id, array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}