<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Concrete\ColorScheme;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Base\Model as BaseModel
};

class Model extends BaseModel
{
    protected string $name;
    protected string $primaryColor;
    protected string $secondaryColor;
    protected string $backgroundColor;
    protected string $textColor;
    protected bool $isDarkMode = false;
    protected array $additionalColors = [];

    public function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->primaryColor)) {
            throw new \InvalidArgumentException('Primary color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->secondaryColor)) {
            throw new \InvalidArgumentException('Secondary color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->backgroundColor)) {
            throw new \InvalidArgumentException('Background color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->textColor)) {
            throw new \InvalidArgumentException('Text color must be a valid hex color');
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'name' => $this->name,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'background_color' => $this->backgroundColor,
            'text_color' => $this->textColor,
            'is_dark_mode' => $this->isDarkMode,
            'additional_colors' => $this->additionalColors
        ]);
    }

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrimaryColor(): string
    {
        return $this->primaryColor;
    }

    public function getSecondaryColor(): string
    {
        return $this->secondaryColor;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function isDarkMode(): bool
    {
        return $this->isDarkMode;
    }

    public function getAdditionalColors(): array
    {
        return $this->additionalColors;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrimaryColor(string $primaryColor): void
    {
        $this->primaryColor = $primaryColor;
    }

    public function setSecondaryColor(string $secondaryColor): void
    {
        $this->secondaryColor = $secondaryColor;
    }

    public function setBackgroundColor(string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function setTextColor(string $textColor): void
    {
        $this->textColor = $textColor;
    }

    public function setIsDarkMode(bool $isDarkMode): void
    {
        $this->isDarkMode = $isDarkMode;
    }

    public function setAdditionalColors(array $additionalColors): void
    {
        $this->additionalColors = $additionalColors;
    }

    // Model-specific methods
    public function toggleDarkMode(): void
    {
        $this->isDarkMode = !$this->isDarkMode;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addAdditionalColor(string $name, string $color): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            throw new \InvalidArgumentException('Color must be a valid hex color');
        }
        $this->additionalColors[$name] = $color;
        $this->updatedAt = new \DateTimeImmutable();
    }
}