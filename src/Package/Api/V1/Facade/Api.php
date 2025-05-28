<?php

namespace Ababilithub\FlexPhp\Package\Api\v1\Facade;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardPhpMixin,
    FlexPhp\Package\Api\v1\Manager\Api as Manager,
};

class Api
{
    use StandardPhpMixin;
    private Manager $manager;
    
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function login($request)
    {
        return $this->manager->handleLogin($request);
    }

    public function logout($request)
    {
        return $this->manager->handleLogout($request);
    }
}

