<?php

namespace App\Constants;

use App\Traits\ConstantsTrait;

enum FileConstants: string
{
    use ConstantsTrait;
    case FILE_TYPE_USER_AVATAR = 'user_avatar';
    case FILE_TYPE_USER_COVER = 'user_cover';

    public static function fileableTypes(): array
    {
        return [
            'User'
        ];
    }
}
