<?php

namespace CisBv\OpenAi\Enum;

enum OpenAiRole: string
{
    case DEVELOPER = 'developer';
    case USER = 'user';
    case ASSISTANT = 'assistant';
}