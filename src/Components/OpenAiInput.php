<?php

namespace CisBv\OpenAi\Components;

use CisBv\OpenAi\Enum\OpenAiRole;

class OpenAiInput
{
    public function __construct(
        public OpenAiRole $role,
        public string $content,
    ) {
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->content,
        ];
    }
}