<?php

namespace Ababilithub\FlexPhp\Package\Api\v1\Service;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Mixin\V1\Standard\Mixin as StandardPhpMixin,
    FlexPhp\Package\Api\v1\Repository\Api as Repository,
    FlexPhp\Package\Api\v1\Factory\Api as Factory,
};

class Api
{
    use StandardPhpMixin;
    private Repository $repository;
    private Factory $factory;

    public function __construct()
    {
        $this->repository = new Repository();
        $this->factory = new Factory();
    }

    public function login($request)
    {
        $creds = $request->get_json_params();
        return (new UserRepository())->validateUser($creds['username'], $creds['password']);
    }

    public function logout($request)
    {
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
}

