<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Concrete\Dark;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Base\ColorScheme as BaseColorScheme
};

class ColorScheme extends BaseColorScheme
{
    public function __construct(array $attributes = [])
    {
        $defaults = [
            'name' => 'Default Dark',
            'primaryColor' => '#3498db',
            'secondaryColor' => '#2ecc71',
            'backgroundColor' => '#121212',
            'textColor' => '#ecf0f1',
            'isDarkMode' => true
        ];

        parent::__construct(array_merge($defaults, $attributes));
    }

    public function getType(): string
    {
        return 'dark';
    }

    public function validate(): void
    {
        parent::validate();
        
        if ($this->calculateLuminance($this->backgroundColor) > 0.15) 
        {
            throw new \InvalidArgumentException(
                'Dark scheme background should be truly dark (luminance < 0.15)'
            );
        }
    }
}