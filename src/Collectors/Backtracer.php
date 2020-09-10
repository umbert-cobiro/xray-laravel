<?php

declare(strict_types=1);

namespace Napp\Xray\Collectors;

trait Backtracer
{
    public function getBacktrace(): array
    {
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT, 50);
        $sources = [];
        foreach ($stack as $index => $trace) {
            $trace = $this->parseTrace($index, $trace);
            if ($trace !== '') {
                $sources[] = $trace;
            }
        }

        return array_slice(array_filter($sources), 0, 10);
    }

    /**
     * @return string|false
     */
    public function getCallerClass(array $backtrace)
    {
        $arr = explode('\\', $backtrace[0]);

        return end($arr);
    }

    /**
     * @param int|string$index
     */
    protected function parseTrace($index, array $trace): string
    {
        if (isset($trace['class']) && ! $this->isExcludedClass($trace['class'])) {
            return $trace['class'] . ':' . ($trace['line'] ?? '?');
        }

        return '';
    }

    protected function isExcludedClass(string $className): bool
    {
        $excludedPaths = [
            'Illuminate/Database',
            'Illuminate/Events',
            'Napp/Xray',
        ];

        $normalizedPath = str_replace('\\', '/', $className);
        foreach ($excludedPaths as $excludedPath) {
            if (strpos($normalizedPath, $excludedPath) !== false) {
                return true;
            }
        }

        return false;
    }
}
