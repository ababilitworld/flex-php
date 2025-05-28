<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Contract;

use Throwable;

interface Exception extends Throwable
{
    /**
     * Get the exception category
     */
    public function getCategory(): string;

    /**
     * Get additional context data
     */
    public function getContext(): array;

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int;

    /**
     * Get the Error code
     */
    public function getErrorCode(): string;

    /**
     * Should the exception be reported?
     */
    public function shouldReport(): bool;
}

