<?php

namespace App\Constants;

class TaskStatus extends BaseConstant
{
    public const TODO = 'todo';
    public const IN_PROGRESS = 'in_progress';
    public const DONE = 'done';

    public static function labels(): array
    {
        return [
            self::TODO => 'To do',
            self::IN_PROGRESS => 'In progress',
            self::DONE => 'Done',
        ];
    }
}
