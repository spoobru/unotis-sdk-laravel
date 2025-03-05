<?php namespace Spoob\UnotisLaravel\Serializers;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Throwable;

class ExceptionSerializer
{
    public function serialize(Throwable $exception): string
    {
        $data = [
            'class' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $this->cleanStackTrace($exception->getTrace()),
            'previous' => $exception->getPrevious() ? $this->serialize($exception->getPrevious()) : null,
        ];

        return serialize($data);
    }

    /**
     * @throws ReflectionException
     */
    public function unserialize(string $serializedData): Throwable
    {
        $data = unserialize($serializedData);

        if (!isset($data['class'])) {
            throw new InvalidArgumentException('Invalid serialized exception data.');
        }

        $previous = $data['previous'] ? $this->unserialize($data['previous']) : null;

        $reflection = new ReflectionClass($data['class']);
        $exception = $reflection->newInstanceArgs([
            $data['message'],
            $data['code'],
            $previous,
        ]);

        $exception->file = $data['file'];
        $exception->line = $data['line'];

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        array_shift($trace);
        $exception->trace = array_merge($trace, $data['trace']);

        return $exception;
    }

    public function cleanStackTrace(array $trace): array
    {
        $cleanedTrace = [];

        foreach ($trace as $frame) {
            $cleanedFrame = $frame;

            if (isset($cleanedFrame['object']) && $cleanedFrame['object'] instanceof Closure) {
                unset($cleanedFrame['object']);
            }

            if (isset($cleanedFrame['args'])) {
                $cleanedFrame['args'] = array_map(function ($arg) {
                    if ($arg instanceof Closure) {
                        return '[Closure]';
                    }
                    if (is_object($arg)) {
                        return '[Object: ' . get_class($arg) . ']';
                    }
                    return $arg;
                }, $cleanedFrame['args']);
            }

            $cleanedTrace[] = $cleanedFrame;
        }

        return $cleanedTrace;
    }
}
