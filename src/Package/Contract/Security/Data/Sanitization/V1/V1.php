<?php
namespace Ababilithub\FlexPhp\Package\Contract\Security\Data\Sanitization\V1;

interface V1 
{
    public function sanitize(mixed $value): bool;
}