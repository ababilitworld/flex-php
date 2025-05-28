<?php
namespace Ababilithub\FlexPhp\Package\Repository\V1\Exception;

class Repository extends \RuntimeException
{
    // Standard repository error codes
    public const MISSING_FIELD = 100;
    public const VALIDATION_FAILED = 101;
    public const NOT_FOUND = 102;
    public const CONNECTION_FAILED = 103;
    public const QUERY_FAILED = 104;
    public const CONFIG_ERROR = 105;
    
    // Additional context data
    protected array $context;

    public function __construct(
        string $message, 
        int $code = 0, 
        array $context = [], 
        ?\Throwable $previous = null
    ) 
    {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public static function forMissingField(string $field, array $context = []): self
    {
        return new self(
            "Required field '{$field}' is missing",
            self::MISSING_FIELD,
            array_merge(['field' => $field], $context)
        );
    }

    public static function forValidationFailure(string $field, array $context = []): self
    {
        return new self(
            "Validation failed for field '{$field}'",
            self::VALIDATION_FAILED,
            array_merge(['field' => $field], $context)
        );
    }

    public static function forNotFound(string $identifier, array $context = []): self
    {
        return new self(
            "Resource not found: {$identifier}",
            self::NOT_FOUND,
            array_merge(['identifier' => $identifier], $context)
        );
    }

    public static function forConnectionError(\Throwable $previous): self
    {
        return new self(
            "Repository connection failed: {$previous->getMessage()}",
            self::CONNECTION_FAILED,
            [],
            $previous
        );
    }

    public static function forQueryError(string $query, array $errorInfo = []): self
    {
        return new self(
            "Repository query failed",
            self::QUERY_FAILED,
            [
                'query' => $query,
                'error_info' => $errorInfo
            ]
        );
    }

    public static function forConfigError(string $key, array $context = []): self
    {
        return new self(
            "Invalid repository configuration: {$key}",
            self::CONFIG_ERROR,
            array_merge(['config_key' => $key], $context)
        );
    }
}