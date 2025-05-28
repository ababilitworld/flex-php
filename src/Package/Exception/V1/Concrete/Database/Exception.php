<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Concrete\Database;

use Ababilithub\{
    FlexPhp\Package\Exception\V1\Base\Exception as BaseException
};
use Throwable;

class Exception extends BaseException
{
    protected string $category = 'database';

    public function __construct(
        string $message = "Database error occurred",
        array $context = [],
        int $code = 0,
        Throwable $previous = null
    ) 
    {
        parent::__construct($message, $context, $code, $previous);
    }
}