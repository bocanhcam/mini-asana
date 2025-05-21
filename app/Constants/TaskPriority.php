<?php

namespace App\Constants;

class TaskPriority extends BaseConstant
{
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const HIGH = 'high';

    public static function labels(): array
    {
        return [
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        ];
    }
}
