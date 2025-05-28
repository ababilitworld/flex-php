<?php
namespace Ababilithub\FlexPhp\Package\Dto\V1\Concrete\ColorScheme;

use Ababilithub\{
    FlexPhp\Package\Dto\V1\Base\Dto as BaseDto,
    FlexPhp\Package\Model\V1\Concrete\ColorScheme\Model as ColorSchemeModel
};
use InvalidArgumentException;

class Dto extends BaseDto
{
    public ?int $id = null;
    public string $name;
    public string $primaryColor;
    public string $secondaryColor;
    public string $backgroundColor;
    public string $textColor;
    public bool $isDarkMode = false;
    public array $additionalColors = [];
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public static function fromEntity(object $colorScheme): static
    {
        if (!$colorScheme instanceof ColorSchemeModel) {
            throw new InvalidArgumentException('Expected ColorSchemeModel instance');
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
        $dto->createdAt = $colorScheme->getCreatedAt()?->format('Y-m-d H:i:s');
        $dto->updatedAt = $colorScheme->getUpdatedAt()?->format('Y-m-d H:i:s');
        
        return $dto;
    }

    public function toEntity(): object
    {
        $this->validate();
        
        $colorScheme = new ColorSchemeModel();
        $colorScheme->setId($this->id);
        $colorScheme->setName($this->name);
        $colorScheme->setPrimaryColor($this->primaryColor);
        $colorScheme->setSecondaryColor($this->secondaryColor);
        $colorScheme->setBackgroundColor($this->backgroundColor);
        $colorScheme->setTextColor($this->textColor);
        $colorScheme->setIsDarkMode($this->isDarkMode);
        $colorScheme->setAdditionalColors($this->additionalColors);
        
        return $colorScheme;
    }

    public function validate(): void
    {
        $this->assertType('name', $this->name, 'string');
        $this->assertType('primaryColor', $this->primaryColor, 'string');
        $this->assertType('secondaryColor', $this->secondaryColor, 'string');
        $this->assertType('backgroundColor', $this->backgroundColor, 'string');
        $this->assertType('textColor', $this->textColor, 'string');
        $this->assertType('isDarkMode', $this->isDarkMode, 'boolean');
        $this->assertType('additionalColors', $this->additionalColors, 'array');
        $this->assertType('id', $this->id, 'integer', true);
        $this->assertType('createdAt', $this->createdAt, 'string', true);
        $this->assertType('updatedAt', $this->updatedAt, 'string', true);

        if (empty($this->name)) {
            throw new InvalidArgumentException('Name cannot be empty');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->primaryColor)) {
            throw new InvalidArgumentException('Primary color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->secondaryColor)) {
            throw new InvalidArgumentException('Secondary color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->backgroundColor)) {
            throw new InvalidArgumentException('Background color must be a valid hex color');
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $this->textColor)) {
            throw new InvalidArgumentException('Text color must be a valid hex color');
        }
    }
}