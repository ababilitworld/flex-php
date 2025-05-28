<?php 

namespace Ababilithub\FlexPhp\Package\Mixin\V1\Singleton\Instance\Multiple;

trait Mixin  
{
    /**
     * Stores multiple instances based on configuration keys
     */
    private static array $instances = []; 
    private array $config = []; 

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get or create an instance based on configuration
     */
    public static function getInstance(array $config = []): static
    {
        $key = md5(json_encode($config)); // Generate a unique key for config

        if (!isset(self::$instances[$key]) || self::$instances[$key]->needsUpdate($config)) 
        {
            self::$instances[$key] = new static($config);
        }
        
        return self::$instances[$key];
    }

    /**
     * Check if configuration has changed
     */
    private function needsUpdate(array $config = []): bool
    {
        return empty($this->config) || $this->config !== $config;
    }
}