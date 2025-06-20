<?php 
namespace Ababilithub\FlexWordpress\Package\Factory\V1\Contract;

(defined('ABSPATH') && defined('WPINC')) || exit();
interface Factory
{
    /**
     * Get an instance of the class
     *
     * @param string $targetClass
     * @return mixed
     */
    public static function get(string $targetClass): mixed;    
}