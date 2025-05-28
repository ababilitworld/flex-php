<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Contract\Log;

use Throwable;

interface Exception
{
    public function log(Throwable $exception, array $context = []): void;
}

