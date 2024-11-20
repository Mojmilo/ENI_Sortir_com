<?php

namespace App\Enum;

enum Status: string
{
    case CREATED = 'created';
    case OPEN = 'open';
    case CLOSED = 'closed';
    case IN_PROGRESS = 'in_progress';
    case PAST = 'past';
    case CANCELLED = 'cancelled';
}
