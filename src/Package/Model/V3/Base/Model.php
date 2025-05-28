<?php
namespace Ababilithub\FlexPhp\Package\Model\V3\Base;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Contract\Model as ModelContract,
};
use DateTimeImmutable;

abstract class Model implements ModelContract
{
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) 
        {
            if (property_exists($this, $key)) 
            {
                $this->{$key} = $value;
            }
        }
    }

    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) 
        {
            // if ($key !== 'attributes') 
            // { 
            //     $array[$key] = $value;
            // }
            $array[$key] = $value;
        }
        return $array;
    }

    abstract public function validate(): void;
}