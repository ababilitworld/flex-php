<?php
namespace Ababilithub\FlexPhp\Package\Model\V2\Base;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Contract\Model as ModelContract,
};
use DateTimeImmutable;

abstract class Model implements ModelContract
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) 
        {
            $this->attributes[$name] = $value;
        }
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) 
        {
            if (property_exists($this, $key)) 
            {
                $this->{$key} = $value; // Uses __set()
            }
        }
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    abstract public function validate(): void;
}