<?php 
namespace Ababilithub\FlexPhp\Package\Manager\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Contract\Manager as ManagerContract
};

abstract class Manager implements ManagerContract
{
    protected array $items = [];
    public function __construct()
    {
        $this->init();
    }

    public function set_items(array $items = []): static
    {
        $this->items = $items;
        return $this;
    }

    protected function get_items(): array
    {
        return $this->items;
    }
    abstract public function boot(): void;
    
}
