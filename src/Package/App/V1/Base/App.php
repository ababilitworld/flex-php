<?php
namespace MyPlugin\CentralApp;

use MyPlugin\CentralApp\Contracts\AppInterface;
use Ababilithub\{
    FlexPhp\Package\
};

class App 
{
    private static ?App $instance = null;
    private array $apps = [];

    private function __construct() {}

    public static function getInstance(): App 
    {
        return self::$instance ??= new self();
    }

    public function registerApp(AppInterface $app): void 
    {
        $this->apps[$app->name()] = $app;
        $app->register();
    }

    public function bootApps(): void 
    {
        foreach ($this->apps as $app) 
        {
            $app->boot();
        }
    }

    public function getApp(string $name): ?AppInterface 
    {
        return $this->apps[$name] ?? null;
    }
}

<?php
namespace MyPlugin;

defined('ABSPATH') || exit;

class Container
{
    private $services = [];
    private $instances = [];

    public function register(string $name, callable $resolver): void
    {
        $this->services[$name] = $resolver;
    }

    public function get(string $name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->services[$name])) {
            throw new \Exception("Service {$name} not found");
        }

        $this->instances[$name] = $this->services[$name]($this);
        return $this->instances[$name];
    }
}
