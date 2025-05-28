<?php
namespace Ababilithub\FlexPhp\Package\Dto\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Dto\V1\Contract\Dto as DTOContract
};

use JsonException;
use InvalidArgumentException;
use RuntimeException;
abstract class Dto implements DTOContract
{
    /**
     * Create DTO from array data
     */
    public static function fromArray(array $data): static
    {
        $dto = new static();
        foreach ($data as $key => $value) 
        {
            if (property_exists($dto, $key)) 
            {
                $dto->{$key} = $value;
            }
        }
        $dto->validate();
        return $dto;
    }

    /**
     * Convert DTO to array representation
     */
    public function toArray(): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) 
        {
            if ($value instanceof DTOContract) 
            {
                $data[$key] = $value->toArray();
            } 
            elseif (is_array($value)) 
            {
                $data[$key] = array_map(
                    fn($item) => $item instanceof DTOContract ? $item->toArray() : $item,
                    $value
                );
            } 
            else 
            {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * Convert DTO to JSON string
     *
     * @throws JsonException When JSON encoding fails
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Validate DTO data
     *
     * @throws InvalidArgumentException When validation fails
     */
    abstract public function validate(): void;

    /**
     * Helper method for property type validation
     */
    protected function assertType(
        string $property,
        $value,
        string $expectedType,
        bool $nullable = false
    ): void 
    {
        if ($nullable && $value === null) 
        {
            return;
        }

        $actualType = gettype($value);
        if ($actualType !== $expectedType) 
        {
            throw new InvalidArgumentException(
                sprintf(
                    'Property "%s" must be of type %s, %s given',
                    $property,
                    $expectedType,
                    $actualType
                )
            );
        }
    }
}