<?php
namespace FlexPhp\Package\Api\v1\Provider\Concrete\Jwt;

use Ababilithub\{
    FlexPhp\Package\Api\V1\Provider\Base\Provider as BaseProvider,
};
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\JWT as FireJWT;
use Firebase\JWT\Key as FireJWTKEY;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use UnexpectedValueException;

class Provider extends BaseProvider
{

    public function __construct(string $secret) 
    {
        $this->secret = $secret;
    }

    public function generateToken(array $data): string 
    {
        $issuedAt = time();
        $data['iat'] = $issuedAt;
        $data['exp'] = $issuedAt + 3600;
        return FireJWT::encode($data, $this->secret, $this->algo);
    }

    public function validateToken(string $token): bool 
    {
        try 
        {
            FireJWT::decode($token, new FireJWTKEY($this->secret, $this->algo));
            return true;
        } 
        catch (\Exception $e) 
        {
            return false;
        }
    }

    public function getPayload(string $token): array 
    {
        try 
        {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algo));
            return (array) $decoded;
        } 
        catch (\Exception $e) 
        {
            return [];
        }
    }
}
