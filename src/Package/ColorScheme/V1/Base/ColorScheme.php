<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Base;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Contract\ColorScheme as ColorSchemeContract,
};

abstract class ColorScheme implements ColorSchemeContract
{
    protected ?int $id = null;
    protected string $name;
    protected string $type;
    protected string $primaryColor;
    protected string $secondaryColor;
    protected string $backgroundColor;
    protected string $textColor;
    protected array $additionalColors = [];
    protected bool $isDarkMode = false;
       
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->name)) 
        {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->validateColor('primaryColor', $this->primaryColor);
        $this->validateColor('secondaryColor', $this->secondaryColor);
        $this->validateColor('backgroundColor', $this->backgroundColor);
        $this->validateColor('textColor', $this->textColor);

        foreach ($this->additionalColors as $name => $color) {
            $this->validateColor($name, $color);
        }

        if (!($this->isAccessible())) 
        {
            throw new \RuntimeException('Color Scheme is not Accessible! Please change text and background colors to make the scheme accessible!!!');
        }
    }

    protected function validateColor(string $property, string $color): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            throw new \InvalidArgumentException(
                sprintf('%s must be a valid hex color, "%s" given', $property, $color)
            );
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

    public function isAccessible(): bool
    {
        return $this->calculateContrastRatio() >= 4.5;
    }

    public function toCssVariables(): array
    {
        return [
            '--scheme-name' => $this->getName(),
            '--scheme-type' => $this->getType(),
            '--primary-color' => $this->getPrimaryColor(),
            '--secondary-color' => $this->getSecondaryColor(),
            '--background-color' => $this->getBackgroundColor(),
            '--text-color' => $this->getTextColor(),            
        ];
    }

    // Common getters and setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }
    public function getPrimaryColor(): string { return $this->primaryColor; }
    public function setPrimaryColor(string $color): void { $this->primaryColor = $color; }
    public function getSecondaryColor(): string { return $this->secondaryColor; }
    public function setSecondaryColor(string $color): void { $this->secondaryColor = $color; }
    public function getBackgroundColor(): string { return $this->backgroundColor; }
    public function setBackgroundColor(string $color): void { $this->backgroundColor = $color; }
    public function getTextColor(): string { return $this->textColor; }
    public function setTextColor(string $color): void { $this->textColor = $color; }
    public function isDarkMode(): bool { return $this->isDarkMode; }
    public function setIsDarkMode(bool $mode): void { $this->isDarkMode = $mode; }
    public function getAdditionalColors(): array { return $this->additionalColors; }
    public function setAdditionalColors(array $colors): void { $this->additionalColors = $colors; }
    
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'primary_color' => $this->getPrimaryColor(),
            'secondary_color' => $this->getSecondaryColor(),
            'background_color' => $this->getBackgroundColor(),
            'text_color' => $this->getTextColor(),
            'is_dark_mode' => $this->isDarkMode(),
            'additional_colors' => $this->getAdditionalColors(),
            'is_accessible' => $this->isAccessible(),
            'contrast_ratio' => $this->calculateContrastRatio(),
            'css_variables' => $this->toCssVariables()
        ];
    }

    protected function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) 
        {
            if (property_exists($this, $key)) 
            {
                $this->{$key} = $value;
            }
        }
    }
}