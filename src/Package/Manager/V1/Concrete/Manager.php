<?php
namespace Ababilithub\FlexPhp\Package\Manager\V1\Concrete\Manager;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Manager\V1\Base\Manager as BaseManager,
    FlexWordpress\Package\Shortcode\V1\Factory\Shortcode as ShortcodeFactory,
    FlexWordpress\Package\Shortcode\V1\Concrete\StaticFilter\Shortcode as StaticFilterShortcode,

};

class Manager extends BaseManager
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->set_items(
                [
                StaticFilterShortcode::class,
            ]
        );
    }

    public function boot(): void 
    {
        foreach ($this->get_items() as $item) 
        {
            $shortcode = ShortcodeFactory::get($item);
            
            if ($shortcode instanceof ShortcodeContract) 
            {
                $shortcode->register();
            }
        }
    }
}