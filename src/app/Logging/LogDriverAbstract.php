<?php

namespace App\Logging;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;

abstract class LogDriverAbstract
{
    /**
     * The Log levels.
     *
     * @var array
     */
    protected array $levels = [
        'debug'     => Monolog::DEBUG,
        'info'      => Monolog::INFO,
        'notice'    => Monolog::NOTICE,
        'warning'   => Monolog::WARNING,
        'error'     => Monolog::ERROR,
        'critical'  => Monolog::CRITICAL,
        'alert'     => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    /**
     * Apply the configured taps for the logger.
     * @param array           $config
     * @param LoggerInterface $logger
     * @return LoggerInterface
     * @throws BindingResolutionException
     */
    protected function tap(array $config, LoggerInterface $logger): LoggerInterface
    {
        foreach ($config['tap'] ?? [] as $tap) {
            [$class, $arguments] = $this->parseTap($tap);

            app()->make($class)->__invoke($logger, ...explode(',', $arguments));
        }

        return $logger;
    }

    /**
     * Parse the given tap class string into a class name and arguments string.
     * @param string $tap
          * @return array
     */
    protected function parseTap(string $tap): array
    {
        return Str::contains($tap, ':') ? explode(':', $tap, 2) : [$tap, ''];
    }

    /**
     * Prepare the handler for usage by Monolog.
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler): HandlerInterface
    {
        return $handler->setFormatter($this->formatter());
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return FormatterInterface
     */
    protected function formatter(): FormatterInterface
    {
        return tap(new LineFormatter(null, null, true, true), function ($formatter) {
            $formatter->includeStacktraces();
        });
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     *
     * @return string
     */
    protected function parseChannel(array $config): string
    {
        if (!isset($config['name'])) {
            return app()->bound('env') ? app()->environment() : 'production';
        }

        return $config['name'];
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     *
     * @return int
     *
     * @throws InvalidArgumentException
     */
    protected function level(array $config): int
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }
}
