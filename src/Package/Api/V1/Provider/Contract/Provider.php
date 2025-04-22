<?php
namespace Ababilithub\FlexPhp\Package\Api\v1\Provider\Contract;

interface Provider 
{
    public function generateToken(array $data): string;
    public function validateToken(string $token): bool;
    public function getPayload(string $token): array;
}
