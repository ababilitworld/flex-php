<?php
namespace Ababilithub\FlexPhp\Package\Contract\Security\Data\Validation\V1;

interface V1 
{
    public function validate(mixed $value): bool;
}