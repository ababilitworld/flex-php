<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Concrete\Light;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Base\ColorScheme as BaseColorScheme
};

class ColorScheme extends BaseColorScheme
{
    public function __construct(array $attributes = [])
    {
        $defaults = [
            'name' => 'Default Light',
            'primaryColor' => '#3498db',
            'secondaryColor' => '#2ecc71',
            'backgroundColor' => '#ffffff',
            'textColor' => '#2c3e50',
            'isDarkMode' => false
        ];

        parent::__construct(array_merge($defaults, $attributes));
    }

    public function getType(): string
    {
        return 'light';
    }
}