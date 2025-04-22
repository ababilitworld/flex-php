<?php
namespace MyPlugin\App\Modules\Database;

use MyPlugin\App\Core\AbstractModule;
use MyPlugin\App\Modules\Database\Contracts\DatabaseInterface;
use MyPlugin\App\Modules\Database\Services\DatabaseService;

class DatabaseModule extends AbstractModule
{
    public function register(): void
    {
        $this->container->singleton(DatabaseInterface::class, DatabaseService::class);
        $this->container->singleton('db', DatabaseInterface::class);
    }

    public function boot(): void
    {
        if ($this->container->has('migrator')) {
            $this->container->make('migrator')->run();
        }
    }
}