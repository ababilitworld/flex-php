<?php
namespace Ababilithub\FlexPhp\Package\Dto\V1\Contract;

interface Dto
{
    /**
     * @param object $entity Any entity object
     * @return static
     */
    public static function fromEntity(object $entity): static;

    /**
     * @return object Returns the domain entity
     */
    public function toEntity(): object;

    public function toArray(): array;
    public function toJson(): string;
    public function validate(): void;
}