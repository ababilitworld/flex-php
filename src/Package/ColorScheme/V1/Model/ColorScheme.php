<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Model;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Base\Model as BaseModel
};
use InvalidArgumentException;

class ColorScheme extends BaseModel
{
    public string $name;
    public string $primaryColor;
    public string $secondaryColor;
    public string $backgroundColor;
    public string $textColor;
    public bool $isDarkMode = false;
    public array $additionalColors = [];
    public string $schemeType = 'custom';

    public function validate(): void
    {
        if (empty($this->name)) 
        {
            throw new InvalidArgumentException('Color scheme name cannot be empty');
        }

        $this->validateColor($this->primaryColor, 'primaryColor');
        $this->validateColor($this->secondaryColor, 'secondaryColor');
        $this->validateColor($this->backgroundColor, 'backgroundColor');
        $this->validateColor($this->textColor, 'textColor');

        foreach ($this->additionalColors as $color) 
        {
            $this->validateColor($color);
        }

        if ($this->isDarkMode && $this->isLightColor($this->backgroundColor)) 
        {
            throw new InvalidArgumentException(
                'Dark mode color scheme should have a dark background'
            );
        }
    }

    protected function validateColor(string $color, string $propertyName = ''): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $message = $propertyName 
                ? sprintf('%s must be a valid hex color, "%s" given', $propertyName, $color)
                : sprintf('Invalid hex color: "%s"', $color);
            throw new InvalidArgumentException($message);
        }
    }

    public function calculateContrastRatio(): float
    {
        $textLuminance = $this->calculateLuminance($this->textColor);
        $bgLuminance = $this->calculateLuminance($this->backgroundColor);
        return (max($textLuminance, $bgLuminance) + 0.05) / (min($textLuminance, $bgLuminance) + 0.05);
    }

    protected function calculateLuminance(string $hexColor): float
    {
        $r = hexdec(substr($hexColor, 1, 2)) / 255;
        $g = hexdec(substr($hexColor, 3, 2)) / 255;
        $b = hexdec(substr($hexColor, 5, 2)) / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : (($r + 0.055) / 1.055) ** 2.4;
        $g = $g <= 0.03928 ? $g / 12.92 : (($g + 0.055) / 1.055) ** 2.4;
        $b = $b <= 0.03928 ? $b / 12.92 : (($b + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    protected function isLightColor(string $hexColor): bool
    {
        $luminance = $this->calculateLuminance($hexColor);
        return $luminance > 0.5;
    }

    public function isAccessible(): bool
    {
        return $this->calculateContrastRatio() >= 4.5;
    }

    public function toCssVariables(): array
    {
        return [
            '--primary-color' => $this->primaryColor,
            '--secondary-color' => $this->secondaryColor,
            '--background-color' => $this->backgroundColor,
            '--text-color' => $this->textColor,
            '--scheme-type' => $this->schemeType,
        ];
    }

    public function toggleDarkMode(): self
    {
        $this->isDarkMode = !$this->isDarkMode;        
        return $this;
    }

    public function addAdditionalColor(string $name, string $color): self
    {
        $this->validateColor($color);
        $this->additionalColors[$name] = $color;        
        return $this;
    }

    public function removeAdditionalColor(string $name): self
    {
        if (array_key_exists($name, $this->additionalColors)) 
        {
            unset($this->additionalColors[$name]);            
        }
        return $this;
    }

    public function getColorPalette(): array
    {
        return [
            'primary' => $this->primaryColor,
            'secondary' => $this->secondaryColor,
            'background' => $this->backgroundColor,
            'text' => $this->textColor,
            'additional' => $this->additionalColors
        ];
    }
}