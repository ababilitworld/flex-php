<?php
namespace Core\Exception\Base;

use Core\Contracts\Exception\ExceptionHandlerInterface;
use Core\Contracts\Exception\ExceptionLoggerInterface;

abstract class ExceptionHandler implements ExceptionHandlerInterface
{
    protected ExceptionLoggerInterface $logger;
    protected array $strategies = [];
    protected array $dontReport = [];

    public function __construct(ExceptionLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(\Throwable $exception): void
    {
        if ($this->shouldReport($exception)) {
            $this->report($exception);
        }

        $this->render($exception);
    }

    public function registerStrategy(string $exceptionClass, callable $handler): void
    {
        $this->strategies[$exceptionClass] = $handler;
    }

    protected function shouldReport(\Throwable $exception): bool
    {
        foreach ($this->dontReport as $type) {
            if ($exception instanceof $type) {
                return false;
            }
        }

        if ($exception instanceof CoreException) {
            return $exception->isReportable();
        }

        return true;
    }

    protected function report(\Throwable $exception): void
    {
        $context = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];

        if ($exception instanceof CoreException) {
            $context['meta'] = $exception->getMetaData();
        }

        $this->logger->log($exception, $context);
    }

    protected function render(\Throwable $exception): void
    {
        foreach ($this->strategies as $exceptionClass => $handler) {
            if ($exception instanceof $exceptionClass) {
                $handler($exception);
                return;
            }
        }

        $this->fallbackRender($exception);
    }

    abstract protected function fallbackRender(\Throwable $exception): void;
}