<?php 

namespace Ababilithub\FlexPhp\Package\Mixin\V1\Singleton\Instance\Single;

trait Mixin
{ 
    public static ?self $instance = null; 
    public array $arrayConfig = [];

    public static function getInstance(array $arrayConfig = []): static
    {
        if (is_null(self::$instance) || self::$instance->needsUpdate($arrayConfig)) 
        {
            self::$instance = new static($arrayConfig);
        }
        
        return self::$instance;
    }
    
    public function needsUpdate(array $arrayConfig = []): bool
    {
        return empty($this->arrayConfig) || $this->arrayConfig !== $arrayConfig;
    }
}
