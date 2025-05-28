<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Dto;

use Ababilithub\{
    FlexPhp\Package\Dto\V1\Base\Dto as BaseDto,
    FlexPhp\Package\ColorScheme\V1\Model\ColorScheme as ColorSchemeModel
};

class ColorScheme extends BaseDto
{
    public ?int $id = null;
    public string $name;
    public string $primaryColor;
    public string $secondaryColor;
    public string $backgroundColor;
    public string $textColor;
    public bool $isDarkMode = false;
    public array $additionalColors = [];
    public string $schemeType = 'custom';
    
    public ?float $contrastRatio = null;
    public ?bool $isAccessible = null;

    public static function fromEntity(object $colorScheme): static
    {
        if (!$colorScheme instanceof ColorSchemeModel) 
        {
            throw new \InvalidArgumentException('Expected ColorSchemeModel instance');
        }

        $dto = new static();
        $dto->id = $colorScheme->getId();
        $dto->name = $colorScheme->getName();
        $dto->primaryColor = $colorScheme->getPrimaryColor();
        $dto->secondaryColor = $colorScheme->getSecondaryColor();
        $dto->backgroundColor = $colorScheme->getBackgroundColor();
        $dto->textColor = $colorScheme->getTextColor();
        $dto->isDarkMode = $colorScheme->isDarkMode();
        $dto->additionalColors = $colorScheme->getAdditionalColors();
        $dto->schemeType = $colorScheme->getSchemeType();
        $dto->createdAt = $colorScheme->getCreatedAt()?->format('Y-m-d H:i:s');
        $dto->updatedAt = $colorScheme->getUpdatedAt()?->format('Y-m-d H:i:s');
        $dto->contrastRatio = $colorScheme->calculateContrastRatio();
        $dto->isAccessible = $colorScheme->isAccessible();
        
        return $dto;
    }

    public function toEntity(): ColorSchemeModel
    {
        $this->validate();
        
        return new ColorSchemeModel([
            'id' => $this->id,
            'name' => $this->name,
            'primaryColor' => $this->primaryColor,
            'secondaryColor' => $this->secondaryColor,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'isDarkMode' => $this->isDarkMode,
            'additionalColors' => $this->additionalColors,
            'schemeType' => $this->schemeType,
            'createdAt' => $this->createdAt ? new \DateTimeImmutable($this->createdAt) : null,
            'updatedAt' => $this->updatedAt ? new \DateTimeImmutable($this->updatedAt) : null
        ]);
    }

    public function validate(): void
    {
        // Basic type validation
        $this->assertType('name', $this->name, 'string');
        $this->assertType('primaryColor', $this->primaryColor, 'string');
        $this->assertType('secondaryColor', $this->secondaryColor, 'string');
        $this->assertType('backgroundColor', $this->backgroundColor, 'string');
        $this->assertType('textColor', $this->textColor, 'string');
        $this->assertType('isDarkMode', $this->isDarkMode, 'boolean');
        $this->assertType('additionalColors', $this->additionalColors, 'array');
        $this->assertType('schemeType', $this->schemeType, 'string');
        $this->assertType('id', $this->id, 'integer', true);
        $this->assertType('createdAt', $this->createdAt, 'string', true);
        $this->assertType('updatedAt', $this->updatedAt, 'string', true);
        $this->assertType('contrastRatio', $this->contrastRatio, 'double', true);
        $this->assertType('isAccessible', $this->isAccessible, 'boolean', true);

        // Business rule validation
        if (empty($this->name)) 
        {
            throw new \InvalidArgumentException('Color scheme name cannot be empty');
        }

        $this->validateColorFormat('primaryColor', $this->primaryColor);
        $this->validateColorFormat('secondaryColor', $this->secondaryColor);
        $this->validateColorFormat('backgroundColor', $this->backgroundColor);
        $this->validateColorFormat('textColor', $this->textColor);

        foreach ($this->additionalColors as $name => $color) 
        {
            $this->validateColorFormat($name, $color);
        }

        if ($this->isDarkMode && $this->isLightColor($this->backgroundColor)) 
        {
            throw new \InvalidArgumentException(
                'Dark mode color scheme should have a dark background'
            );
        }
    }

    protected function validateColorFormat(string $property, string $color): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            throw new \InvalidArgumentException(
                sprintf('%s must be a valid hex color (e.g. #RRGGBB), "%s" given', $property, $color)
            );
        }
    }

    protected function isLightColor(string $hexColor): bool
    {
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));
        return (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255 > 0.5;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'background_color' => $this->backgroundColor,
            'text_color' => $this->textColor,
            'is_dark_mode' => $this->isDarkMode,
            'additional_colors' => $this->additionalColors,
            'scheme_type' => $this->schemeType,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'contrast_ratio' => $this->contrastRatio,
            'is_accessible' => $this->isAccessible
        ];
    }
}