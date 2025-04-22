<?php
namespace MyPlugin\App\Contract;

interface Kernel
{
    public function boot(): void;
    public function getContainer(): ContainerInterface;
    public function registerModule(string $moduleClass): void;
}