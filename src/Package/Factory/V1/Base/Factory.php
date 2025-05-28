<?php
namespace Ababilithub\FlexPhp\Package\Factory\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Contract\Factory as FactoryContract
};
use InvalidArgumentException;

/**
 * @template T of object
 */
abstract class Factory implements FactoryContract
{
    /** @var array<string, T> */
    protected array $registry = [];

    /** @var array<class-string> */
    protected array $supportedTypes = [];

    /** @var array<class-string> */
    protected array $bannedTypes = [];

    public function add(string $key, object $object): void
    {
        $this->validateObjectType($object);
        
        if ($this->has($key)) {
            throw new InvalidArgumentException(
                sprintf('Object with key "%s" already exists', $key)
            );
        }

        $this->registry[$key] = $object;
    }

    public function get(string $key, array $params = []): object
    {
        if ($this->has($key)) {
            return $this->registry[$key];
        }

        $object = $this->create($key, $params);
        $this->registry[$key] = $object;
        return $object;
    }

    public function has(string $key): bool
    {
        return isset($this->registry[$key]);
    }

    public function remove(string $key): void
    {
        if (!$this->has($key)) {
            throw new InvalidArgumentException(
                sprintf('No object found with key "%s"', $key)
            );
        }
        unset($this->registry[$key]);
    }

    public function createFromArray(array $data): object
    {
        if (!isset($data['type'])) {
            throw new InvalidArgumentException('Missing "type" in data array');
        }
        return $this->get($data['type'], $data);
    }

    public function createFromDto(object $dto): object
    {
        if (!method_exists($dto, 'toArray')) {
            throw new InvalidArgumentException('DTO must implement toArray()');
        }
        return $this->createFromArray($dto->toArray());
    }

    public function supports(string $type): bool
    {
        return empty($this->supportedTypes) || 
               in_array($type, $this->supportedTypes, true);
    }

    public function bans(string $type): bool
    {
        return in_array($type, $this->bannedTypes, true);
    }

    public function getRegisteredKeys(): array
    {
        return array_keys($this->registry);
    }

    public function clear(): void
    {
        $this->registry = [];
    }

    abstract protected function create(string $key, array $params): object;

    protected function validateObjectType(object $object): void
    {
        $className = get_class($object);

        if ($this->bans($className)) {
            throw new InvalidArgumentException(
                sprintf('Type "%s" is banned', $className)
            );
        }

        if (!$this->supports($className)) {
            throw new InvalidArgumentException(
                sprintf('Type "%s" not supported', $className)
            );
        }
    }

    protected function addSupportedTypes(array $types): void
    {
        $this->supportedTypes = array_unique(
            array_merge($this->supportedTypes, $types)
        );
    }

    protected function addBannedTypes(array $types): void
    {
        $this->bannedTypes = array_unique(
            array_merge($this->bannedTypes, $types)
        );
    }
}