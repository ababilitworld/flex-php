<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Factory\Contract;

use Ababilithub\{
    FlexPhp\Package\Factory\V1\Contract\Factory as BaseFactoryContract,
    FlexPhp\Package\ColorScheme\V1\Model\ColorScheme as ColorSchemeModel
};

interface ColorScheme extends BaseFactoryContract
{
    public function create(array $data): ColorSchemeModel;
    public function createDefault(string $type): ColorSchemeModel;
    public function createFromArray(array $data): ColorSchemeModel;
    public function createFromDto(object $dto): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel from a hexadecimal color string
     */
    public function createFromHex(string $hex): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel from RGB values
     */
    public function createFromRgb(int $red, int $green, int $blue): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel from RGBA values
     */
    public function createFromRgba(int $red, int $green, int $blue, float $alpha): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel from HSL values
     */
    public function createFromHsl(float $hue, float $saturation, float $lightness): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel from HSLA values
     */
    public function createFromHsla(float $hue, float $saturation, float $lightness, float $alpha): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel by blending two existing schemes
     */
    public function createFromBlend(ColorSchemeModel $scheme1, ColorSchemeModel $scheme2, float $ratio = 0.5): ColorSchemeModel;
    
    /**
     * Creates a ColorSchemeModel with adjusted brightness
     */
    public function createWithAdjustedBrightness(ColorSchemeModel $scheme, float $amount): ColorSchemeModel;
    
    /**
     * Creates a complementary ColorSchemeModel
     */
    public function createComplementary(ColorSchemeModel $scheme): ColorSchemeModel;
    
    /**
     * Creates a monochromatic ColorSchemeModel palette
     * @return ColorSchemeModel[]
     */
    public function createMonochromaticPalette(ColorSchemeModel $scheme, int $count = 5): array;
    
    /**
     * Creates an analogous ColorSchemeModel palette
     * @return ColorSchemeModel[]
     */
    public function createAnalogousPalette(ColorSchemeModel $scheme, int $count = 3): array;
    
    /**
     * Creates a triadic ColorSchemeModel palette
     * @return ColorSchemeModel[]
     */
    public function createTriadicPalette(ColorSchemeModel $scheme): array;
}