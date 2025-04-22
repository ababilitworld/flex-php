<?php

namespace Ababilithub\FlexPhp\Package\Api\v1\Manager;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\Standard\V1\V1 as StandardPhpMixin,
    FlexPhp\Package\Api\v1\Service\Api as Service,
};

class Api
{
    use StandardPhpMixin;
    private Service $service;
    
    public function __construct()
    {
        $this->service = new Service();
    }

    public function handleLogin($request)
    {
        return $this->service->login($request);
    }

    public function handleLogout($request)
    {
        return $this->service->logout($request);
    }
}

