<?php
namespace Ababilithub\FlexPhp\Package\Exception\V1\Base;

use Ababilithub\{
    FlexPhp\Package\Exception\V1\Contract\Exception as ExceptionContract
};

use Throwable;

abstract class Exception extends \Exception implements ExceptionContract
{
    protected string $category = 'application';
    protected array $context = [];
    protected int $statusCode = 500;
    protected string $errorCode = 'GENERIC_ERROR';
    protected bool $shouldReport = true;

    public function __construct(
        string $message = "",
        array $context = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function shouldReport(): bool
    {
        return $this->shouldReport;
    }
}