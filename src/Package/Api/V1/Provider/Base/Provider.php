<?php
namespace Ababilithub\FlexPhp\Package\Api\v1\Provider\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || die();

use Ababilithub\{
    FlexPhp\Package\Api\v1\Provider\Contract\Provider as ApiProvider
};

abstract class Provider implements ApiProvider 
{
    protected string $secret;
    protected string $algo = 'HS256';

    public function __construct(string $secret) 
    {
        $this->secret = $secret;
    }

    abstract public function generateToken(array $data): string;

    abstract public function validateToken(string $token): bool;

    abstract public function getPayload(string $token): array;
}
