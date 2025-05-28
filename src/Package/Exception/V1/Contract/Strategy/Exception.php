<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Contract\Strategy;

use Throwable;
interface Exception
{
    public function handle(Throwable $exception): bool;
}