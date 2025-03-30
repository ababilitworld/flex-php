<?php 

namespace Ababilithub\FlexPhp\Package\Mixin\Singleton\Instance\Single\V1;

trait V1
{ 
    public static ?self $instance = null; 
    protected array $config = [];

    public static function getInstance(array $config = []): static
    {
        if (is_null(self::$instance) || self::$instance->needsUpdate($config)) 
        {
            self::$instance = new static($config);
        }
        
        return self::$instance;
    }
    
    public function needsUpdate(array $config = []): bool
    {
        return empty($this->config) || $this->config !== $config;
    }
}
