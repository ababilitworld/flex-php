<?php
namespace MyPlugin\App\Core;

use MyPlugin\App\Contracts\KernelInterface;
use MyPlugin\App\Contracts\ContainerInterface;

class Kernel extends AbstractKernel
{
    protected function registerCoreServices(): void
    {
        $this->container->singleton(KernelInterface::class, fn() => $this);
        $this->container->singleton(ContainerInterface::class, fn() => $this->container);
    }

    protected function initializeModules(): void
    {
        foreach ($this->modules as $moduleClass) {
            $module = new $moduleClass($this->container);
            $module->register();
            $module->boot();
        }
    }

    public function boot(): void
    {
        $this->initializeModules();
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        if ($this->container->has('router')) {
            $router = $this->container->make('router');
            // Route registration logic here
        }
    }
}