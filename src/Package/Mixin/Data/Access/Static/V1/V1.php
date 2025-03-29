<?php

declare(strict_types=1);

namespace Ababilithub\FlexPhp\Package\Mixin\Data\Access\Static\V1;

trait V1
{
    /**
     * Get the value of a static property.
     *
     * @param string $variableName The name of the static property.
     * @return mixed|null The value of the static property, or null if not found.
     */
    public static function getStatic(string $variableName)
    {
        if (!property_exists(static::class, $variableName)) {
            trigger_error(
                sprintf("Property '%s' does not exist in class '%s'", $variableName, static::class),
                E_USER_WARNING
            );
            return null;
        }

        if (!self::isStaticProperty($variableName)) {
            trigger_error(
                sprintf("Property '%s' is not static in class '%s'", $variableName, static::class),
                E_USER_WARNING
            );
            return null;
        }

        return static::${$variableName};
    }

    /**
     * Set the value of a static property.
     *
     * @param string $variableName The name of the static property.
     * @param mixed $value The value to set.
     * @return void
     */
    public static function setStatic(string $variableName, mixed $value): void
    {
        if (!property_exists(static::class, $variableName)) {
            trigger_error(
                sprintf("Property '%s' does not exist in class '%s'", $variableName, static::class),
                E_USER_WARNING
            );
            return;
        }

        if (!self::isStaticProperty($variableName)) {
            trigger_error(
                sprintf("Property '%s' is not static in class '%s'", $variableName, static::class),
                E_USER_WARNING
            );
            return;
        }

        static::${$variableName} = $value;
    }

    /**
     * Check if a property is static.
     *
     * @param string $variableName The name of the property.
     * @return bool True if the property is static, false otherwise.
     */
    private static function isStaticProperty(string $variableName): bool
    {
        $reflection = new \ReflectionClass(static::class);
        if (!$reflection->hasProperty($variableName)) {
            return false;
        }

        $property = $reflection->getProperty($variableName);
        return $property->isStatic();
    }
}
