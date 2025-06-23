<?php
namespace Ababilithub\FlexPhp\Package\Form\Field\V1\Factory;

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexPhp\Package\Form\Field\V1\Contract\Field as FieldContract,
};

class Field extends BaseFactory
{
    /**
     * Resolve the shortcode class instance
     *
     * @param string $targetClass
     * @return FieldContract
     */
    protected static function resolve(string $targetClass): FieldContract
    {
        $instance = new $targetClass();

        if (!$instance instanceof FieldContract) 
        {
            throw new \InvalidArgumentException("{$targetClass} must implement FieldContract");
        }

        return $instance;
    }
}