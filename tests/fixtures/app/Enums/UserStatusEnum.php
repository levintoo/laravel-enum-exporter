<?php

namespace App\Enums;

enum UserStatusEnum: int
{
    case Inactive = 0;
    case Active = 1;
    case Left = 3;
    case Banned = 4;
    case Unbanned = 5;
}
