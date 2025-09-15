<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Mixin;

trait ColorScheme
{
    public function toCss(array $color_scheme = []): string
    {
        $css = ':root {';

        foreach($color_scheme as $key=>$value)
        {
            $css .= '--'.$key.':'.$value.';';
        }

        $css .= '}';

        return $css;
    }
}
