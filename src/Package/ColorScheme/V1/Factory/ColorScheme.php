<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Factory;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Factory\Contract\ColorScheme as ColorSchemeFactoryContract,
    FlexPhp\Package\ColorScheme\V1\Model\ColorScheme as ColorSchemeModel,
    FlexPhp\Package\ColorScheme\V1\Dto\ColorScheme as ColorSchemeDto,
    FlexPhp\Package\Factory\V1\Base\Factory as BaseFactory,
};
use InvalidArgumentException;

class ColorScheme extends BaseFactory
{
    protected array $defaultSchemes = [
        'primary' => '#3490dc',
        'secondary' => '#6574cd',
        'success' => '#38c172',
        'danger' => '#e3342f',
        'warning' => '#ffed4a',
        'info' => '#6cb2eb'
    ];

    public function __construct()
    {
        $this->addSupportedTypes([ColorSchemeModel::class]);
    }

    protected function create(string $key, array $params = []): ColorSchemeModel
    {
        if (array_key_exists($key, $this->defaultSchemes)) {
            return $this->createDefault($key);
        }

        return new ColorSchemeModel([
            'name' => $params['name'] ?? $key,
            'primaryColor' => $params['primaryColor'] ?? $this->defaultSchemes['primary'],
            'secondaryColor' => $params['secondaryColor'] ?? $this->adjustColor($params['primaryColor'] ?? $this->defaultSchemes['primary'], 20),
            'backgroundColor' => $params['backgroundColor'] ?? '#ffffff',
            'textColor' => $params['textColor'] ?? '#333333',
            'isDarkMode' => $params['isDarkMode'] ?? false,
            'additionalColors' => $params['additionalColors'] ?? [],
            'schemeType' => $params['schemeType'] ?? 'custom'
        ]);
    }

    public function createDefault(string $type): ColorSchemeModel
    {
        if (!array_key_exists($type, $this->defaultSchemes)) {
            throw new InvalidArgumentException("Invalid default scheme type: $type");
        }

        return $this->create($type, [
            'primaryColor' => $this->defaultSchemes[$type],
            'name' => ucfirst($type) . ' Scheme'
        ]);
    }

    protected function adjustColor(string $hex, int $amount): string
    {
        // Convert hex to RGB
        $r = hexdec(substr($hex, 1, 2));
        $g = hexdec(substr($hex, 3, 2));
        $b = hexdec(substr($hex, 5, 2));

        // Adjust brightness
        $r = max(0, min(255, $r + $amount));
        $g = max(0, min(255, $g + $amount));
        $b = max(0, min(255, $b + $amount));

        // Convert back to hex
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}