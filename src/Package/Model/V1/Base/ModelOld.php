<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Contract\Model as ModelContract,
};

abstract class Model implements ModelContract
{
    /** @var array Stores original attributes for dirty checking */
    private array $original = [];

    /** @var array Stores changed attributes */
    private array $changes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    public function buildFromArray(array $data): static
    {
        $this->fill($data);
        return $this;
    }

    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) 
        {
            if ($key !== 'original' && $key !== 'changes') 
            {
                $array[$key] = $value instanceof ModelContract ? $value->toArray() : $value;
            }
        }
        return $array;
    }

    public function buildFromDTO(object $dto): static
    {
        if (!method_exists($dto, 'toArray')) 
        {
            throw new \InvalidArgumentException('DTO must implement toArray() method');
        }
        $this->fill($dto->toArray());
        return $this;
    }

    public function toDTO(): object
    {
        $data = $this->toArray();
        return (object)$data;
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

    public function __get(string $name)
    {
        if (property_exists($this, $name)) 
        {
            return $this->{$name};
        }
        return null;
    }

    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) 
        {
            $this->trackChange($name, $value);
            $this->{$name} = $value;
        }
    }

    public function getOriginal(string $key = null)
    {
        if ($key === null) 
        {
            return $this->original;
        }
        return $this->original[$key] ?? null;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function isDirty(): bool
    {
        return !empty($this->changes);
    }

    protected function trackChange(string $key, $value): void
    {
        if (!array_key_exists($key, $this->original)) 
        {
            $this->original[$key] = $this->{$key} ?? null;
        }

        if ($this->original[$key] !== $value) 
        {
            $this->changes[$key] = $value;
        }
        else 
        {
            unset($this->changes[$key]);
        }
    }

    abstract public function validate(): void;
}