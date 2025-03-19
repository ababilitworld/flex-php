<?php

namespace Ababilithub\FlexPhp\Package\Mixin\Instance\V1;

trait V1
{
    /**
     * Initializes the class
     *
     * Create instance if not exist.
     *
     * @return object The class instance
     */
    public static function instance() 
    {
        static $instance = null;

        if (!$instance) 
        {
            $instance = new static();
        }

        return $instance;
    }
}