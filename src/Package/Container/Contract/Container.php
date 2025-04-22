<?php
namespace MyPlugin\App\Contracts;

interface ContainerInterface
{
    public function bind(string $abstract, $concrete): void;
    public function singleton(string $abstract, $concrete): void;
    public function make(string $abstract, array $parameters = []);
    public function has(string $abstract): bool;
}