<?php
namespace Ababilithub\FlexPhp\Package\Factory\V1\Contract;

/**
 * @template T of object
 */
interface Factory
{
    /**
     * Add a pre-configured object instance
     * @param string $key Identifier for the object
     * @param T $object The object instance to store
     */
    public function add(string $key, object $object): void;

    /**
     * Get an object by key (creates if not exists)
     * @param string $key Identifier for the object
     * @param array $params Optional constructor parameters
     * @return T
     */
    public function get(string $key, array $params = []): object;

    /**
     * Check if an object exists
     */
    public function has(string $key): bool;

    /**
     * Remove an object from the factory
     */
    public function remove(string $key): void;

    /**
     * Create and add from array
     * @return T
     */
    public function createFromArray(array $data): object;

    /**
     * Create and add from DTO
     * @return T
     */
    public function createFromDto(object $dto): object;

    /**
     * Check if factory supports a type
     */
    public function supports(string $type): bool;

    /**
     * Check if factory bans a type
     */
    public function bans(string $type): bool;

    /**
     * Get all registered keys
     * @return array<string>
     */
    public function getRegisteredKeys(): array;

    /**
     * Clear all objects
     */
    public function clear(): void;
}