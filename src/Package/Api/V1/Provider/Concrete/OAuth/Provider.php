<?php
namespace FlexPhp\Package\Api\v1\Provider\Concrete\Jwt;

use Ababilithub\{
    FlexPhp\Package\Api\v1\Provider\Contract\Provider as ApiProvider
};
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Provider implements ApiProvider 
{

    public function __construct(string $secret) 
    {
        $this->secret = $secret;
    }

    public function generateToken(array $data): string 
    {
        throw new \Exception("OAuth2 does not support local token generation.");
    }

    public function validateToken(string $token): bool 
    {
        // Validate token against external provider
        return true;
    }

    public function getPayload(string $token): array 
    {
        // Get user info from the provider
        return ['user_id' => 123];
    }
}
