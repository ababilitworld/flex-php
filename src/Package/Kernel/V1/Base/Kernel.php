<?php
namespace MyPlugin\App\Core;

use MyPlugin\App\Contracts\KernelInterface;
use MyPlugin\App\Contracts\ContainerInterface;

abstract class AbstractKernel implements KernelInterface
{
    protected ContainerInterface $container;
    protected array $modules = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->registerCoreServices();
    }

    abstract protected function registerCoreServices(): void;
    abstract protected function initializeModules(): void;

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function registerModule(string $moduleClass): void
    {
        if (!in_array(ModuleInterface::class, class_implements($moduleClass))) {
            throw new \InvalidArgumentException("Module must implement ModuleInterface");
        }
        $this->modules[] = $moduleClass;
    }
}