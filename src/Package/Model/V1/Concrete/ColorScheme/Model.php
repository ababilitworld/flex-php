<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Concrete\ColorScheme;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Base\Model as BaseModel
};

class Model extends BaseModel
{
    public string $name;
    public string $primaryColor;
    public string $secondaryColor;
    public string $backgroundColor;
    public string $textColor;
    public bool $isDarkMode = false;
    public array $additionalColors = [];

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
        
        foreach ($this->additionalColors as $name => $color) 
        {
            $this->validateColor($name, $color);
        }
    }

    protected function validateColor(string $name, string $color): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) 
        {
            throw new \InvalidArgumentException(
                sprintf('%s must be a valid hex color (got: %s)', $name, $color)
            );
        }
    }

    // Model-specific methods
    public function toggleDarkMode(): self
    {
        $this->isDarkMode = !$this->isDarkMode;        
        return $this;
    }

    public function addAdditionalColor(string $name, string $color): self
    {
        $this->validateColor($name, $color);
        $this->additionalColors[$name] = $color;        
        return $this;
    }

    public function removeAdditionalColor(string $name): self
    {
        unset($this->additionalColors[$name]);        
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