<?php

namespace CisBv\OpenAi\Enum;

enum OpenAiModel: string
{
    case GPT_4O = 'gpt-4o';
    case GPT_4O_MINI = 'gpt-4o-mini';
    case GPT_4O_MINI_2024_07_18 = 'gpt-4o-mini-2024-07-08';
    case GPT_4_1 = 'gpt-4.1';
    case GPT_4_1_2025_04_14 = 'gpt-4.1-2025-04-14';
    case GPT_4_1_MINI = 'gpt-4.1-mini';
    case GPT_4_1_MINI_2025_04_14 = 'gpt-4.1-mini-2025-04-14';
    case GPT_4_1_NANO = 'gpt-4.1-nano';
    case GPT_4_1_NANO_2025_04_14 = 'gpt-4.1-nano-2025-04-14';

    case O4_MINI = 'o4-mini';
    case O4_MINI_2025_04_16 = 'o4-mini-2025-04-16';
}
