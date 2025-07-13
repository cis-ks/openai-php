<?php

namespace CisBv\OpenAi\Enum;

enum OpenAiResponseStatus: string
{
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case IN_PROGRESS = 'in_progress';
    case CANCELLED = 'cancelled';
    case QUEUED = 'queued';
    case INCOMPLETE = 'incomplete';
}
