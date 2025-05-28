<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Contract;

interface Model
{
    /**
     * Build the model from an array and return the current instance
     * 
     * @param array $data Input data
     * @return static Returns the current model instance for method chaining
     */
    public function buildFromArray(array $data): static;

    /**
     * Convert the model to an array representation
     * 
     * @return array Array representation of the model
     */
    public function toArray(): array;

    /**
     * Build the model from a Data Transfer Object and return the current instance
     * 
     * @param object $dto Data Transfer Object
     * @return static Returns the current model instance for method chaining
     * @throws \InvalidArgumentException If DTO doesn't implement required methods
     */
    public function buildFromDTO(object $dto): static;

    /**
     * Convert the model to a Data Transfer Object
     * 
     * @return object Plain object representation of the model
     */
    public function toDTO(): object;

    /**
     * Validate the model data
     * 
     * @throws \InvalidArgumentException If validation fails
     */
    public function validate(): void;
}