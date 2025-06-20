<?php 
namespace Ababilithub\FlexPhp\Package\Manager\V1\Contract;

interface Manager
{
    public function set_items(array $items = []): static;
    public function boot(): void; 
}