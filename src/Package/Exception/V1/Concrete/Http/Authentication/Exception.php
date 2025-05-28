<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Concrete\Database\Connection;

use Ababilithub\{
    FlexPhp\Package\Exception\V1\Concrete\Database\Exception as DatabaseException
};

class Exception extends DatabaseException
{
    protected int $statusCode = 401;

    public function __construct(array $context = [])
    {
        parent::__construct("Http Authentication failed", $context);
    }
}