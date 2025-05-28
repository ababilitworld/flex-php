<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Contract;

interface ColorScheme
{
    public function getName(): string;
    public function getType(): string;
    public function calculateContrastRatio(): float;
    public function isAccessible(): bool;
    public function toCssVariables(): array;
}