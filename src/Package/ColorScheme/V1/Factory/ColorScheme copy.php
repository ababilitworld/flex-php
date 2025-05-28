<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Factory;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Factory\Contract\ColorScheme as ColorSchemeFactoryContract,
    FlexPhp\Package\ColorScheme\V1\Model\ColorScheme as ColorSchemeModel,
    FlexPhp\Package\ColorScheme\V1\Dto\ColorScheme as ColorSchemeDto,
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
};

class ColorScheme extends BaseFactory implements ColorSchemeFactoryContract
{
    public function create(string $key, array $data): ColorSchemeModel
    {
        return new ColorSchemeModel(
            $data['hex'] ?? null,
            $data['rgb'] ?? null,
            $data['hsl'] ?? null,
            $data['name'] ?? null
        );
    }

    public function createDefault(string $type): ColorSchemeModel
    {
        return match(strtolower($type)) {
            'primary' => $this->createFromHex('#3490dc'),
            'secondary' => $this->createFromHex('#6574cd'),
            'success' => $this->createFromHex('#38c172'),
            'danger' => $this->createFromHex('#e3342f'),
            'warning' => $this->createFromHex('#ffed4a'),
            'info' => $this->createFromHex('#6cb2eb'),
            default => throw new \InvalidArgumentException("Unknown color scheme type: {$type}")
        };
    }

    public function createFromArray(array $data): ColorSchemeModel
    {
        return $this->create($data);
    }

    public function createFromDto(object $dto): ColorSchemeModel
    {
        if (!$dto instanceof ColorSchemeDto) {
            throw new \InvalidArgumentException('Expected instance of ColorSchemeDto');
        }

        return new ColorSchemeModel(
            $dto->hex,
            $dto->rgb,
            $dto->hsl,
            $dto->name
        );
    }

    public function createFromHex(string $hex): ColorSchemeModel
    {
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $hex)) {
            throw new \InvalidArgumentException('Invalid hex color format');
        }

        $rgb = $this->hexToRgb($hex);
        $hsl = $this->rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);

        return new ColorSchemeModel(
            $hex,
            $rgb,
            $hsl,
            null
        );
    }

    public function createFromRgb(int $red, int $green, int $blue): ColorSchemeModel
    {
        if ($red < 0 || $red > 255 || $green < 0 || $green > 255 || $blue < 0 || $blue > 255) {
            throw new \InvalidArgumentException('RGB values must be between 0 and 255');
        }

        $hex = $this->rgbToHex($red, $green, $blue);
        $hsl = $this->rgbToHsl($red, $green, $blue);

        return new ColorSchemeModel(
            $hex,
            ['r' => $red, 'g' => $green, 'b' => $blue],
            $hsl,
            null
        );
    }

    public function createFromRgba(int $red, int $green, int $blue, float $alpha): ColorSchemeModel
    {
        if ($alpha < 0 || $alpha > 1) {
            throw new \InvalidArgumentException('Alpha must be between 0 and 1');
        }

        $scheme = $this->createFromRgb($red, $green, $blue);
        $scheme->setAlpha($alpha);

        return $scheme;
    }

    public function createFromHsl(float $hue, float $saturation, float $lightness): ColorSchemeModel
    {
        if ($hue < 0 || $hue > 360 || $saturation < 0 || $saturation > 100 || $lightness < 0 || $lightness > 100) {
            throw new \InvalidArgumentException('Invalid HSL values');
        }

        $rgb = $this->hslToRgb($hue, $saturation, $lightness);
        return $this->createFromRgb($rgb['r'], $rgb['g'], $rgb['b']);
    }

    public function createFromHsla(float $hue, float $saturation, float $lightness, float $alpha): ColorSchemeModel
    {
        $scheme = $this->createFromHsl($hue, $saturation, $lightness);
        $scheme->setAlpha($alpha);
        return $scheme;
    }

    public function createFromBlend(ColorSchemeModel $scheme1, ColorSchemeModel $scheme2, float $ratio = 0.5): ColorSchemeModel
    {
        $rgb1 = $scheme1->getRgb();
        $rgb2 = $scheme2->getRgb();

        $r = $rgb1['r'] * $ratio + $rgb2['r'] * (1 - $ratio);
        $g = $rgb1['g'] * $ratio + $rgb2['g'] * (1 - $ratio);
        $b = $rgb1['b'] * $ratio + $rgb2['b'] * (1 - $ratio);

        return $this->createFromRgb((int)$r, (int)$g, (int)$b);
    }

    public function createWithAdjustedBrightness(ColorSchemeModel $scheme, float $amount): ColorSchemeModel
    {
        $hsl = $scheme->getHsl();
        $newLightness = max(0, min(100, $hsl['l'] + $amount));
        return $this->createFromHsl($hsl['h'], $hsl['s'], $newLightness);
    }

    public function createComplementary(ColorSchemeModel $scheme): ColorSchemeModel
    {
        $hsl = $scheme->getHsl();
        $complementaryHue = ($hsl['h'] + 180) % 360;
        return $this->createFromHsl($complementaryHue, $hsl['s'], $hsl['l']);
    }

    public function createMonochromaticPalette(ColorSchemeModel $scheme, int $count = 5): array
    {
        $hsl = $scheme->getHsl();
        $palette = [];
        $step = 100 / ($count + 1);

        for ($i = 1; $i <= $count; $i++) {
            $lightness = $i * $step;
            $palette[] = $this->createFromHsl($hsl['h'], $hsl['s'], $lightness);
        }

        return $palette;
    }

    public function createAnalogousPalette(ColorSchemeModel $scheme, int $count = 3): array
    {
        $hsl = $scheme->getHsl();
        $palette = [];
        $step = 30 / ($count - 1);

        for ($i = 0; $i < $count; $i++) {
            $hue = ($hsl['h'] + ($i * $step) - 15) % 360;
            $palette[] = $this->createFromHsl($hue, $hsl['s'], $hsl['l']);
        }

        return $palette;
    }

    public function createTriadicPalette(ColorSchemeModel $scheme): array
    {
        $hsl = $scheme->getHsl();
        return [
            $scheme,
            $this->createFromHsl(($hsl['h'] + 120) % 360, $hsl['s'], $hsl['l']),
            $this->createFromHsl(($hsl['h'] + 240) % 360, $hsl['s'], $hsl['l'])
        ];
    }

    // Helper methods for color conversion
    private function hexToRgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    private function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    private function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h = $s = $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0; // achromatic
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            
            switch ($max) {
                case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                case $g: $h = ($b - $r) / $d + 2; break;
                case $b: $h = ($r - $g) / $d + 4; break;
            }
            
            $h /= 6;
        }

        return [
            'h' => round($h * 360),
            's' => round($s * 100),
            'l' => round($l * 100)
        ];
    }

    private function hslToRgb(float $h, float $s, float $l): array
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        if ($s == 0) {
            $r = $g = $b = $l; // achromatic
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            
            $r = $this->hueToRgb($p, $q, $h + 1/3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1/3);
        }

        return [
            'r' => round($r * 255),
            'g' => round($g * 255),
            'b' => round($b * 255)
        ];
    }

    private function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }

    public function supports(string $type): bool
    {
        return in_array(strtolower($type), [
            'primary', 'secondary', 'success', 'danger', 'warning', 'info',
            'hex', 'rgb', 'rgba', 'hsl', 'hsla'
        ]);
    }
}