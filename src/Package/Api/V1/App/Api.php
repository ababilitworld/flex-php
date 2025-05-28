<?php

namespace Ababilithub\FlexPhp\Package\Api\v1\App;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardPhpMixin,
    FlexPhp\Package\Api\v1\Controller\Api as Controller
};


class Api
{
    use StandardPhpMixin;
    private Controller $controller;

    private function __construct()
    {
        $this->controller = new Controller();
    }

    public function run(string $containerId): void
    {
        $this->controller->render($containerId);
    }
}
