<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Concrete\HighContrast;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Base\ColorScheme as BaseColorScheme
};

class ColorScheme extends BaseColorScheme
{
    public function __construct(array $attributes = [])
    {
        $defaults = [
            'name' => 'High Contrast',
            'primaryColor' => '#0000ff',
            'secondaryColor' => '#ff0000',
            'backgroundColor' => '#ffffff',
            'textColor' => '#000000',
            'isDarkMode' => false,
            'additionalColors' => [
                'warning' => '#ffff00',
                'success' => '#00ff00'
            ]
        ];

        parent::__construct(array_merge($defaults, $attributes));
    }

    public function getType(): string
    {
        return 'high_contrast';
    }

    public function validate(): void
    {
        parent::validate();
        
        if ($this->calculateContrastRatio() < 7) 
        {
            throw new \InvalidArgumentException(
                'High contrast scheme must have contrast ratio of at least 7:1'
            );
        }
    }
}