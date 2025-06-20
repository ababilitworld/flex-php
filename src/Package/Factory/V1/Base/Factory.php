<?php 
namespace Ababilithub\FlexWordpress\Package\Factory\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Factory\V1\Contract\Factory as FactoryContract
};

abstract class Factory implements FactoryContract
{
    /**
     * Hold all instances
     * @var array
     */
    protected static array $instances = [];

    /**
     * @param string $targetClass
     * @return mixed
     */
    public static function get(string $targetClass): mixed
    {
        if (!isset(static::$instances[$targetClass])) 
        {
            static::$instances[$targetClass] = static::resolve($targetClass);
        }

        return static::$instances[$targetClass];
    }

    /**
     * Resolve and return instance
     *
     * @param string $targetClass
     * @return mixed
     */
    abstract protected static function resolve(string $targetClass): mixed;    
}