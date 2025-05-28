<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Contract\Model as ModelContract,
};

use Exception, InvalidArgumentException;
use DateTimeInterface, DateTimeImmutable;
use JsonSerializable;

abstract class Model implements Arrayable, Jsonable, JsonSerializable
{
    protected string $primaryKey = 'id';
    protected bool $incrementing = true;
    protected array $attributes = [];
    protected array $original = [];
    protected array $changes = [];
    protected array $fillable = [];
    protected array $guarded = ['*'];
    protected array $casts = [];
    protected array $dates = [];
    protected ?DateTimeImmutable $createdAt = null;
    protected ?DateTimeImmutable $updatedAt = null;
    protected bool $exists = false;
    protected static ?RepositoryInterface $repository = null;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) 
        {
            if ($this->isFillable($key)) 
            {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    protected function isFillable(string $key): bool
    {
        if (in_array($key, $this->guarded) || $this->guarded === ['*']) 
        {
            return in_array($key, $this->fillable);
        }
        return !in_array($key, $this->guarded);
    }

    public function setAttribute(string $key, mixed $value): static
    {
        if ($this->hasCast($key)) 
        {
            $value = $this->castAttribute($key, $value);
        }

        if (in_array($key, $this->dates) && $value !== null) 
        {
            $value = $this->asDateTime($value);
        }

        $this->attributes[$key] = $value;
        $this->trackChange($key, $value);
        
        return $this;
    }

    public function getAttribute(string $key): mixed
    {
        if (array_key_exists($key, $this->attributes)) 
        {
            return $this->attributes[$key];
        }

        if (method_exists($this, $method = 'get'.Str::studly($key).'Attribute')) 
        {
            return $this->{$method}();
        }

        return null;
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     */
    protected function hasCast(string $key): bool
    {
        return array_key_exists($key, $this->casts);
    }

    protected function castAttribute(string $key, mixed $value): mixed
    {
        $castType = $this->casts[$key];
        
        switch ($castType) 
        {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'date':
            case 'datetime':
                return $this->asDateTime($value);
            default:
                return $value;
        }
    }

    protected function trackChange(string $key, mixed $value): void
    {
        if (!array_key_exists($key, $this->original)) 
        {
            $this->original[$key] = $this->attributes[$key] ?? null;
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

    public function isDirty(): bool
    {
        return !empty($this->changes);
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function save(): bool
    {
        if ($this->exists) 
        {
            return $this->performUpdate();
        }

        return $this->performInsert();
    }

    protected function performUpdate(): bool
    {
        $this->updatedAt = new DateTimeImmutable();
        
        if (static::getRepository()->update($this->getKey(), $this->getDirtyAttributes())) 
        {
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    protected function performInsert(): bool
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        
        if ($id = static::getRepository()->create($this->getAttributes())) 
        {
            $this->setAttribute($this->primaryKey, $id);
            $this->exists = true;
            $this->syncOriginal();
            return true;
        }
        
        return false;
    }

    public function delete(): bool
    {
        if (!$this->exists) 
        {
            return false;
        }
        
        if (static::getRepository()->delete($this->getKey())) 
        {
            $this->exists = false;
            return true;
        }
        
        return false;
    }

    public static function find(int|string $id): ?static
    {
        return static::getRepository()->find($id);
    }

    public static function all(): array
    {
        return static::getRepository()->all();
    }

    public static function getRepository(): RepositoryInterface
    {
        if (static::$repository === null) 
        {
            throw new RuntimeException('No repository configured for model');
        }
        return static::$repository;
    }

    public static function setRepository(RepositoryInterface $repository): void
    {
        static::$repository = $repository;
    }

    

    /**
     * Convert the given value to a DateTime instance.
     */
    protected function asDateTime(mixed $value): DateTimeInterface
    {
        // If this value is already a DateTime instance, we'll just return it
        if ($value instanceof DateTimeInterface) 
        {
            return $value;
        }

        // If this value is an integer, we'll assume it's a UNIX timestamp
        if (is_numeric($value)) 
        {
            return new DateTimeImmutable('@' . $value);
        }

        // If the value is in standard date format, parse it directly
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) 
        {
            return DateTimeImmutable::createFromFormat('Y-m-d', $value)
                ->setTime(0, 0, 0);
        }

        // Otherwise parse with default format
        try 
        {
            return new DateTimeImmutable($value);
        }
        catch (Exception $e) 
        {
            throw new InvalidArgumentException(
                "Unable to parse date/time value: [{$value}]"
            );
        }
    }

    /**
     * Convert a string to studly caps case (e.g., "foo_bar" becomes "FooBar")
     */
    public static function studly(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }

    /**
     * Get the value of the model's primary key.
     */
    public function getKey(): mixed
    {
        return $this->getAttribute($this->primaryKey);
    }

    /**
     * Get all of the current attributes on the model.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get the attributes that have been changed since the last sync.
     */
    public function getDirtyAttributes(): array
    {
        $dirty = [];

        foreach ($this->attributes as $key => $value) 
        {
            if (!array_key_exists($key, $this->original))
            {
                $dirty[$key] = $value;
            }
            elseif (
                $value !== $this->original[$key] &&
                !$this->originalIsNumericallyEquivalent($key)
            ) 
            {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Determine if the new and old values for a given key are numerically equivalent.
     */
    protected function originalIsNumericallyEquivalent(string $key): bool
    {
        $current = $this->attributes[$key];
        $original = $this->original[$key];

        return is_numeric($current) && 
               is_numeric($original) && 
               strcmp((string) $current, (string) $original) === 0;
    }

    /**
     * Sync the original attributes with the current.
     */
    public function syncOriginal(): static
    {
        $this->original = $this->attributes;
        $this->changes = [];
        return $this;
    }

    /**
     * Sync a single original attribute with its current value.
     */
    public function syncOriginalAttribute(string $attribute): static
    {
        $this->original[$attribute] = $this->attributes[$attribute];
        unset($this->changes[$attribute]);
        return $this;
    }

    abstract public function validate(): void;

    // ... additional helper methods for dates, JSON, etc.
}