<?php

namespace App\Constants;

abstract class BaseConstant
{
    abstract protected static function labels(): array;

    public static function get(string $key = null): string|array
    {
        $labels = static::labels();

        if ($key === null) {
            return $labels;
        }

        return $labels[$key] ?? 'Unknown';
    }

    public static function getAsArrayOptions(): array
    {
        $labels = static::labels();

        return array_map(
            fn($key) => ['value' => $key, 'name' => $labels[$key]],
            array_keys($labels)
        );
    }
}
