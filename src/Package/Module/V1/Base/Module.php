<?php
namespace MyPlugin\App\Core;

use MyPlugin\App\Contracts\ContainerInterface;

abstract class AbstractModule implements ModuleInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract public function register(): void;
    abstract public function boot(): void;
}