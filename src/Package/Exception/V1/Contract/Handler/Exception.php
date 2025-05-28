<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Contract\Handler;

use Throwable;

interface Exception
{
    public function handle(Throwable $exception): void;
    public function registerStrategy(string $exceptionClass, callable $handler): void;
}

